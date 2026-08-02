# 🚀 PortfolioForge

PortfolioForge is a dynamic web-based portfolio builder that allows users to create, manage, customize, and showcase their professional profiles through a personalized portfolio website.

The platform is designed especially for students, developers, and professionals who want to create a structured and professional online portfolio without building everything from scratch.

Users can manage their personal information, education, skills, projects, experience, achievements, and contact details, customize their portfolio, preview it, and download their portfolio as an A4-sized PDF.

---

## ✨ Features

- 👤 User registration and login
- 🔐 Session-based authentication
- 📝 Personal profile management
- 🎓 Education details
- 💻 Skills management
- 🚀 Project management
- 🏆 Achievements and certifications
- 💼 Experience details
- 🎨 Portfolio customization
- 👀 Portfolio preview
- 📄 A4 PDF portfolio generation and download
- 🗄️ MySQL database integration
- 🔄 Update existing portfolio information
- 📱 Responsive user interface
- 🔒 User-specific portfolio data
- 📊 CRUD operations for portfolio information

---

## 🛠️ Technologies Used

### Frontend

- HTML5
- CSS3
- JavaScript

### Backend

- PHP

### Database

- MySQL
- phpMyAdmin

### PDF Generation

- Dompdf
- Composer

### Development Environment

- XAMPP
- Apache
- MySQL
- Visual Studio Code

### Version Control

- Git
- GitHub

---

## 📂 Project Structure

```text
PortfolioForge/
│
├── auth/
│   ├── login.php
│   ├── register.php
│   └── logout.php
│
├── config/
│   └── db.php
│
├── css/
│   └── style.css
│
├── js/
│   └── JavaScript files
│
├── images/
│   └── Images and assets
│
├── profile/
│   └── Profile management files
│
├── projects/
│   └── Project management files
│
├── customize/
│   └── Portfolio customization files
│
├── preview/
│   └── Portfolio preview files
│
├── pdf/
│   └── generate_pdf.php
│
├── vendor/
│   └── Composer dependencies
│
├── index.php
├── dashboard.php
├── composer.json
├── composer.lock
└── README.md
```

> Note: The folder structure may change as the project continues to evolve.

---

## ⚙️ How to Run the Project

### 1. Install XAMPP

Install XAMPP and start:

- Apache
- MySQL

### 2. Clone the Repository

```bash
git clone https://github.com/AkshataOntigodimath/PortfolioForge.git
```

### 3. Move the Project

Place the project folder inside:

```text
C:\xampp\htdocs\
```

### 4. Install Composer Dependencies

Open the project folder in the terminal:

```bash
cd PortfolioForge
```

Then run:

```bash
composer install
```

This installs the required PHP dependencies, including Dompdf for PDF generation.

### 5. Create the Database

Open phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Create the required MySQL database and import the provided `.sql` database file.

### 6. Configure Database Connection

Open:

```text
config/db.php
```

Update the database credentials according to your local XAMPP setup.

Example:

```php
$host = "localhost";
$username = "root";
$password = "";
$database = "portfolio_db";
```

### 7. Run the Website

Open your browser and visit:

```text
http://localhost/PortfolioForge/
```

---

## 📄 PDF Generation

PortfolioForge includes an A4 PDF generation feature using **Dompdf**.

Users can:

1. Create their portfolio
2. Add personal information
3. Add education and skills
4. Add projects and experience
5. Preview their portfolio
6. Download their portfolio as an A4 PDF

The generated PDF uses a clean and professional layout suitable for sharing and documentation.

---

## 🔐 Security

The project uses PHP session-based authentication and server-side processing to manage user access and portfolio information.

Current authentication features include:

- User registration
- User login
- Session management
- Logout functionality
- User-specific portfolio information

Future security improvements may include:

- 🔒 Stronger password security
- 🛡️ Improved input validation
- 🛡️ CSRF protection
- 🔐 Additional authentication security

---

## 🎨 Portfolio Customization

PortfolioForge allows users to customize their portfolio according to their preferences.

Users can access the customization section to modify the appearance of their portfolio while maintaining a simple and professional design.

---

## 👀 Portfolio Preview

Users can preview their portfolio before downloading or sharing it.

The preview allows users to verify:

- Personal information
- Education
- Skills
- Projects
- Experience
- Contact information
- Overall portfolio appearance

---


## 🎯 Project Purpose

PortfolioForge was developed as a practical Web Development project to demonstrate skills in:

- Frontend development
- Backend development
- PHP
- MySQL
- Authentication
- CRUD operations
- Database management
- PDF generation
- Responsive UI development
- Git and GitHub

The project demonstrates the integration of frontend, backend, database, authentication, and document-generation functionality into a complete web application.

---

## 🔮 Future Enhancements

Possible future improvements include:

- 🎨 Multiple portfolio templates
- 🌐 Custom portfolio URLs
- 📊 Portfolio analytics
- 🌓 Dark/light mode
- 📱 Further mobile responsiveness
- 🔗 Social media integration
- 🖼️ Enhanced profile and project image uploads
- 🤖 AI-assisted portfolio content generation
- ☁️ Online deployment
- 📧 Contact form and email integration
- 🔒 Additional security improvements

---

## 📌 Project Status

**Core development completed. ✅**

The current version supports:

- ✅ User authentication
- ✅ Profile management
- ✅ Project management
- ✅ Portfolio customization
- ✅ Portfolio preview
- ✅ MySQL database integration
- ✅ A4 PDF generation and download

Additional features and improvements can be added in future versions.

---

## 👩‍💻 Developer

### Akshata R.O.

**Electronics and Communication Engineering**

Interested in:

- Web Development
- Data Science
- IoT
- AI/ML
- Embedded Systems
- VLSI

---


PortfolioForge was developed as a practical learning project to strengthen full-stack web development, database management, authentication, PDF generation, and software development skills.

If you find this project useful, consider giving the repository a ⭐.
