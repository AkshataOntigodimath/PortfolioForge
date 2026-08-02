# 🚀 PortfolioForge

**PortfolioForge** is a dynamic web-based portfolio builder that allows users to create, manage, customize, preview, and download their professional portfolios.

The platform is designed for **students, developers, and professionals** who want to create a structured and professional portfolio without building a website from scratch.

Users can manage their personal information, education, skills, projects, experience, extracurricular activities, certifications, and contact details. They can also customize their portfolio, preview the final design, and generate an **A4-sized PDF** of their portfolio.

---

## ✨ Features

### 🔐 Authentication
- User registration
- User login and logout
- Session-based authentication
- User-specific portfolio data

### 👤 Profile Management
- Personal information
- Profile photo
- Education details
- Skills
- Experience
- Contact information
- LinkedIn and GitHub profiles

### 🚀 Project Management
- Add projects
- Edit projects
- Delete projects
- Project descriptions
- Technologies used
- GitHub/project links

### 🏆 Activities & Certifications
- Add extracurricular activities
- Add certifications
- Manage career-related achievements

### 🎨 Portfolio Customization
- Customize portfolio appearance
- Multiple layout/template options
- Theme customization
- Personalized portfolio design

### 👀 Portfolio Preview
- Preview the complete portfolio
- View profile information
- View education and skills
- View projects
- View experience
- View activities and certifications
- View contact information

### 📄 PDF Generation
- Generate portfolio as an A4-sized PDF
- Download portfolio directly
- Professional resume-style formatting
- Profile and portfolio information included in the generated PDF

### 🗄️ Database
- MySQL database integration
- User-specific data storage
- CRUD operations for portfolio information

### 📱 User Interface
- Clean and simple interface
- Responsive design
- Student-friendly portfolio creation workflow

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
│   ├── Profile management files
│   └── activities.php
│
├── projects/
│   └── Project management files
│
├── customize/
│   └── Portfolio customization files
│
├── preview/
│   └── portfolio.php
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

> **Note:** The folder structure may change as the project continues to evolve.

---

# ⚙️ How to Run the Project

## 1. Install XAMPP

Install [XAMPP](https://www.apachefriends.org/) and start:

- Apache
- MySQL

---

## 2. Clone the Repository

Open the terminal and run:

```bash
git clone https://github.com/AkshataOntigodimath/PortfolioForge.git
```

Then enter the project directory:

```bash
cd PortfolioForge
```

---

## 3. Move the Project to XAMPP

Place the project folder inside:

```text
C:\xampp\htdocs\
```

The final path should look like:

```text
C:\xampp\htdocs\PortfolioForge\
```

---

## 4. Install Composer Dependencies

Make sure Composer is installed on your system.

Inside the project directory, run:

```bash
composer install
```

This installs the required PHP dependencies, including **Dompdf** for PDF generation.

---

## 5. Create the Database

Open phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Create the required MySQL database.

Import the project's SQL database file if one is included in the repository.

---

## 6. Configure Database Connection

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

---

## 7. Start the Project

Make sure **Apache** and **MySQL** are running in XAMPP.

Then open:

```text
http://localhost/PortfolioForge/
```

You can now:

1. Create an account
2. Log in
3. Complete your profile
4. Add education and skills
5. Add projects
6. Add experience
7. Add activities and certifications
8. Customize your portfolio
9. Preview your portfolio
10. Download the portfolio as a PDF

---

# 📄 PDF Generation

PortfolioForge uses **Dompdf** to generate a downloadable A4 PDF.

The PDF generation workflow is:

```text
Profile Information
        ↓
Portfolio Data
        ↓
Portfolio Preview
        ↓
PDF Generation
        ↓
A4 PDF Download
```

The generated PDF includes relevant portfolio information such as:

- Name
- Professional title
- Contact details
- About Me
- Education
- Skills
- Projects
- Experience
- Other portfolio information

The PDF is designed with a clean, professional layout suitable for sharing as a resume or portfolio document.

---

# 🎨 Portfolio Customization

PortfolioForge provides customization options that allow users to personalize the appearance of their portfolio.

Users can modify the portfolio design while maintaining a clean and professional presentation.

The project currently supports different portfolio styles/templates, including:

- Classic
- Modern
- Minimal

---

# 👀 Portfolio Preview

The preview section allows users to see how their portfolio will appear before downloading or sharing it.

The preview can display:

- 👤 Personal information
- 🎓 Education
- 💻 Skills
- 🚀 Projects
- 💼 Experience
- 🏆 Activities and certifications
- 📞 Contact information
- 🔗 LinkedIn and GitHub links
- 🎨 Selected portfolio customization

---

# 🔐 Security

PortfolioForge uses PHP session-based authentication and server-side processing to manage user access.

Current security-related functionality includes:

- User registration
- User login
- Session management
- Logout functionality
- User-specific portfolio data
- Server-side database queries

### Future Security Improvements

Possible improvements include:

- 🔒 Stronger password hashing and security practices
- 🛡️ Improved input validation
- 🛡️ CSRF protection
- 🔐 Additional authentication security
- 🔒 Improved database security

---

# 🗄️ Database & CRUD Operations

PortfolioForge uses **MySQL** to store and manage user portfolio information.

The application demonstrates CRUD operations such as:

- **Create** portfolio information
- **Read** stored information
- **Update** existing information
- **Delete** selected information

This includes data related to:

- Profiles
- Projects
- Activities
- Certifications
- Portfolio customization

---

# 🎯 Project Purpose

PortfolioForge was developed as a practical **Web Development project** to demonstrate the integration of multiple technologies into a complete web application.

The project demonstrates skills in:

- Frontend development
- Backend development
- PHP
- MySQL
- Authentication
- CRUD operations
- Database management
- PDF generation
- Responsive UI development
- Session management
- Git and GitHub

The project combines frontend, backend, database, authentication, portfolio customization, preview, and document-generation functionality into a single application.

---

# 📌 Project Status

## Core Development Completed ✅

The current version supports:

- ✅ User registration and login
- ✅ Session-based authentication
- ✅ Profile management
- ✅ Education management
- ✅ Skills management
- ✅ Project management
- ✅ Experience management
- ✅ Activities and certifications
- ✅ Portfolio customization
- ✅ Portfolio preview
- ✅ MySQL database integration
- ✅ CRUD operations
- ✅ A4 PDF generation
- ✅ PDF download
- ✅ Responsive interface

The project is functional from **registration/login through portfolio creation, customization, preview, and PDF download**.

Future enhancements can be added in subsequent versions.

---

# 🔮 Future Enhancements

Possible future improvements include:

- 🎨 Additional portfolio templates
- 🌐 Custom portfolio URLs
- 📊 Portfolio analytics
- 🌓 Dark/light mode
- 📱 Further mobile optimization
- 🔗 Additional social media integration
- 🖼️ Enhanced profile and project image management
- 🤖 AI-assisted portfolio content generation
- ☁️ Online deployment
- 📧 Contact form and email integration
- 🔒 Additional security improvements

---


# 👩‍💻 Developer

## Akshata R.O.

**Electronics and Communication Engineering**

### Areas of Interest

- 💻 Web Development
- 📊 Data Science
- 🤖 AI/ML
- 🌐 IoT
- 🔧 Embedded Systems
- 💾 VLSI

---

## ⭐ Project

PortfolioForge was developed as a practical learning project to strengthen skills in:

**Full-Stack Web Development • PHP • MySQL • Authentication • CRUD • PDF Generation • Git & GitHub**

If you find this project useful or interesting, consider giving the repository a ⭐.

---

© 2026 **PortfolioForge** | Developed by **Akshata R.O.**
