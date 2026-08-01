<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/db.php";

$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user_name"];

$message = "";


/* =========================
   SAVE / UPDATE PROFILE
   ========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = trim($_POST["full_name"] ?? "");
    $title = trim($_POST["title"] ?? "");
    $bio = trim($_POST["bio"] ?? "");
    $education = trim($_POST["education"] ?? "");
    $skills = trim($_POST["skills"] ?? "");
    $projects = trim($_POST["projects"] ?? "");
    $experience = trim($_POST["experience"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $location = trim($_POST["location"] ?? "");
    $linkedin = trim($_POST["linkedin"] ?? "");
    $github = trim($_POST["github"] ?? "");


    /* =========================
       CHECK EXISTING PROFILE
       ========================= */

    $check_sql = "SELECT * FROM profiles WHERE user_id = ?";

    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();

    $check_result = $check_stmt->get_result();

    $existing_profile = $check_result->fetch_assoc();

    $check_stmt->close();


    /* Keep existing photo if user doesn't upload a new one */

    $profile_photo = $existing_profile["profile_photo"] ?? "";


    /* =========================
       HANDLE PROFILE PHOTO
       ========================= */

    if (
        isset($_FILES["profile_photo"]) &&
        $_FILES["profile_photo"]["error"] !== UPLOAD_ERR_NO_FILE
    ) {

        $file = $_FILES["profile_photo"];


        if ($file["error"] === UPLOAD_ERR_OK) {

            /* Maximum file size: 5 MB */

            if ($file["size"] <= 5 * 1024 * 1024) {

                /* Check actual MIME type */

                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime_type = finfo_file(
                    $finfo,
                    $file["tmp_name"]
                );
                finfo_close($finfo);


                $allowed_types = [
                    "image/jpeg" => "jpg",
                    "image/png" => "png",
                    "image/webp" => "webp"
                ];


                if (isset($allowed_types[$mime_type])) {

                    /* Create uploads folder */

                    $upload_dir = "../uploads/";

                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }


                    /* Create unique filename */

                    $extension = $allowed_types[$mime_type];

                    $filename =
                        "profile_" .
                        $user_id .
                        "_" .
                        time() .
                        "." .
                        $extension;


                    $target_file =
                        $upload_dir . $filename;


                    /* Move image */

                    if (
                        move_uploaded_file(
                            $file["tmp_name"],
                            $target_file
                        )
                    ) {

                        /*
                         * Store path relative to
                         * Portfolio-builder
                         */

                        $profile_photo =
                            "uploads/" . $filename;

                    } else {

                        $message =
                            "Unable to upload the photo.";

                    }

                } else {

                    $message =
                        "Only JPG, PNG and WebP images are allowed.";

                }

            } else {

                $message =
                    "Image size must be less than 5 MB.";

            }

        } else {

            $message =
                "There was a problem uploading the image.";

        }
    }


    /* =========================
       UPDATE EXISTING PROFILE
       ========================= */

    if ($existing_profile) {

        $profile_id = $existing_profile["id"];


        $sql = "UPDATE profiles SET
                full_name = ?,
                title = ?,
                bio = ?,
                education = ?,
                skills = ?,
                projects = ?,
                experience = ?,
                phone = ?,
                location = ?,
                linkedin = ?,
                github = ?,
                profile_photo = ?
                WHERE id = ? AND user_id = ?";


        $stmt = $conn->prepare($sql);


        $stmt->bind_param(
            "ssssssssssssii",
            $full_name,
            $title,
            $bio,
            $education,
            $skills,
            $projects,
            $experience,
            $phone,
            $location,
            $linkedin,
            $github,
            $profile_photo,
            $profile_id,
            $user_id
        );


        if ($stmt->execute()) {

            $message =
                "Profile updated successfully! 🎉";

        } else {

            $message =
                "Failed to update profile.";

        }


        $stmt->close();


    } else {

        /* =========================
           CREATE NEW PROFILE
           ========================= */

        $sql = "INSERT INTO profiles
                (
                    user_id,
                    full_name,
                    title,
                    bio,
                    education,
                    skills,
                    projects,
                    experience,
                    phone,
                    location,
                    linkedin,
                    github,
                    profile_photo
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


        $stmt = $conn->prepare($sql);


        $stmt->bind_param(
            "issssssssssss",
            $user_id,
            $full_name,
            $title,
            $bio,
            $education,
            $skills,
            $projects,
            $experience,
            $phone,
            $location,
            $linkedin,
            $github,
            $profile_photo
        );


        if ($stmt->execute()) {

            $message =
                "Profile created successfully! 🎉";

        } else {

            $message =
                "Failed to create profile.";

        }


        $stmt->close();
    }
}


/* =========================
   LOAD PROFILE
   ========================= */

$sql = "SELECT * FROM profiles WHERE user_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

$profile = $result->fetch_assoc();

$stmt->close();


/* =========================
   PROFILE VALUES
   ========================= */

$full_name = $profile["full_name"] ?? "";
$title = $profile["title"] ?? "";
$bio = $profile["bio"] ?? "";
$education = $profile["education"] ?? "";
$skills = $profile["skills"] ?? "";
$projects = $profile["projects"] ?? "";
$experience = $profile["experience"] ?? "";
$phone = $profile["phone"] ?? "";
$location = $profile["location"] ?? "";
$linkedin = $profile["linkedin"] ?? "";
$github = $profile["github"] ?? "";
$profile_photo = $profile["profile_photo"] ?? "";

?><!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>My Profile | Portfolia</title>


<style>

    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #fff8dc;
    }


    .navbar {
        background: #6b4f2a;
        color: white;
        padding: 18px 40px;
        font-size: 24px;
        font-weight: bold;
    }


    .container {
        max-width: 800px;
        margin: 40px auto;
        background: white;
        padding: 35px;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }


    h1 {
        color: #6b4f2a;
        margin-bottom: 10px;
    }


    .subtitle {
        color: #777;
        margin-bottom: 30px;
    }


    .message {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: bold;
    }


    label {
        display: block;
        margin-top: 18px;
        margin-bottom: 7px;
        font-weight: bold;
        color: #4d3925;
    }


    input,
    textarea {
        width: 100%;
        padding: 12px;
        box-sizing: border-box;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 15px;
        font-family: Arial, sans-serif;
    }


    textarea {
        min-height: 100px;
        resize: vertical;
    }


    input:focus,
    textarea:focus {
        outline: none;
        border-color: #c9a227;
    }


    input[type="file"] {
        padding: 10px;
        background: #fffdf3;
    }


    .photo-preview {
        margin-top: 15px;
        text-align: center;
    }


    .photo-preview img {
        width: 130px;
        height: 130px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #c9a227;
    }


    button {
        width: 100%;
        margin-top: 30px;
        padding: 14px;
        border: none;
        border-radius: 8px;
        background: #c9a227;
        color: white;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
    }


    button:hover {
        background: #a88316;
    }

</style>

</head><body><div class="navbar">
    Portfolia
</div><div class="container"><h1>
    Build Your Profile 👤
</h1>


<p class="subtitle">

    Hi <?php echo htmlspecialchars($user_name); ?>!

    Add your information to create your portfolio.

</p>


<?php if ($message != "") { ?>

    <div class="message">

        <?php echo htmlspecialchars($message); ?>

    </div>

<?php } ?>


<form
    method="POST"
    enctype="multipart/form-data"
>


    <!-- PROFILE PHOTO -->

    <label for="profile_photo">
        Profile Photo
    </label>


    <input
        type="file"
        id="profile_photo"
        name="profile_photo"
        accept=".jpg,.jpeg,.png,.webp"
    >


    <?php if (!empty($profile_photo)) { ?>

        <div class="photo-preview">

            <p>
                Current photo:
            </p>

            <img
                src="../<?php echo htmlspecialchars($profile_photo); ?>"
                alt="Profile Photo"
            >

        </div>

    <?php } ?>


    <!-- FULL NAME -->

    <label for="full_name">
        Full Name
    </label>


    <input
        type="text"
        id="full_name"
        name="full_name"
        value="<?php echo htmlspecialchars($full_name); ?>"
        placeholder="Enter your full name"
        required
    >


    <!-- TITLE -->

    <label for="title">
        Professional Title
    </label>


    <input
        type="text"
        id="title"
        name="title"
        value="<?php echo htmlspecialchars($title); ?>"
        placeholder="Example: Electronics & Communication Engineer"
    >


    <!-- BIO -->

    <label for="bio">
        About Me
    </label>


    <textarea
        id="bio"
        name="bio"
        placeholder="Write a short introduction about yourself..."
    ><?php echo htmlspecialchars($bio); ?></textarea>


    <!-- EDUCATION -->

    <label for="education">
        Education
    </label>


    <textarea
        id="education"
        name="education"
        placeholder="Enter your education details..."
    ><?php echo htmlspecialchars($education); ?></textarea>


    <!-- SKILLS -->

    <label for="skills">
        Skills
    </label>


    <textarea
        id="skills"
        name="skills"
        placeholder="Example: Python, HTML, CSS, IoT, Embedded C..."
    ><?php echo htmlspecialchars($skills); ?></textarea>


    <!-- PROJECTS -->

    <label for="projects">
        Projects
    </label>


    <textarea
        id="projects"
        name="projects"
        placeholder="Describe your projects..."
    ><?php echo htmlspecialchars($projects); ?></textarea>


    <!-- EXPERIENCE -->

    <label for="experience">
        Experience
    </label>


    <textarea
        id="experience"
        name="experience"
        placeholder="Internships, work experience, etc."
    ><?php echo htmlspecialchars($experience); ?></textarea>


    <!-- PHONE -->

    <label for="phone">
        Phone
    </label>


    <input
        type="text"
        id="phone"
        name="phone"
        value="<?php echo htmlspecialchars($phone); ?>"
        placeholder="Enter your phone number"
    >


    <!-- LOCATION -->

    <label for="location">
        Location
    </label>


    <input
        type="text"
        id="location"
        name="location"
        value="<?php echo htmlspecialchars($location); ?>"
        placeholder="Example: Bengaluru, India"
    >


    <!-- LINKEDIN -->

    <label for="linkedin">
        LinkedIn
    </label>


    <input
        type="url"
        id="linkedin"
        name="linkedin"
        value="<?php echo htmlspecialchars($linkedin); ?>"
        placeholder="https://linkedin.com/in/yourname"
    >


    <!-- GITHUB -->

    <label for="github">
        GitHub
    </label>


    <input
        type="url"
        id="github"
        name="github"
        value="<?php echo htmlspecialchars($github); ?>"
        placeholder="https://github.com/yourname"
    >


    <button type="submit">
        Save Profile
    </button>


</form>

</div></body></html>