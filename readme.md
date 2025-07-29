# 🎓 eNilai — Student Grade Management Platform

<div align="center">

![eNilai Banner](https://img.shields.io/badge/eNilai-Student%20Grade%20Management-blue?style=for-the-badge&logo=education)

*A modern, intuitive web application for seamless academic grade management*

[![PHP](https://img.shields.io/badge/PHP-Native-777BB4?style=flat-square&logo=php)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=flat-square&logo=mysql)](https://mysql.com)
[![Apache](https://img.shields.io/badge/Apache-Server-D22128?style=flat-square&logo=apache)](https://apache.org)
[![MVC](https://img.shields.io/badge/Architecture-MVC-FF6B6B?style=flat-square)](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller)

</div>

---

## 🌟 What is eNilai?

**eNilai** isn't just another grade management system — it's a comprehensive academic companion that transforms how educational institutions handle student assessments. Built with passion as a school project, this PHP-powered platform demonstrates that powerful software doesn't always need complex frameworks.

> *"Simplicity is the ultimate sophistication"* — Leonardo da Vinci

---

## 🚀 Core Features That Make a Difference

### 🔐 **Smart Authentication System**
- **Military-grade security**: Session-based authentication with encrypted password protection
- **Role-based access control**: Each user sees exactly what they need, nothing more
- **Auto-logout protection**: Secure sessions that protect sensitive academic data

### 📊 **Intelligent Dashboard**
- **Real-time analytics**: Instant overview of grades, users, and system activity
- **Quick-action navigation**: One-click access to frequently used features
- **Responsive design**: Perfect experience on desktop, tablet, and mobile

### 👥 **Multi-Role User Management**

| Role | ID Range | Capabilities | Icon |
|------|----------|-------------|------|
| **Admin** | `1` | Complete system control, user management, full CRUD operations | 👨‍💼 |
| **Teacher** | `2-5` | Subject-specific grade management, student progress tracking | 👩‍🏫 |
| **Student** | `6` | Personal grade viewing, limited gallery access | 🎓 |

### 📝 **Advanced Grade Management**
- **Intuitive CRUD operations**: Create, read, update, and delete grades with ease
- **Bulk operations**: Handle multiple grades simultaneously
- **Grade analytics**: Track student progress over time
- **Export capabilities**: Generate reports in various formats

### 🖼️ **Dynamic Media Gallery**
- **Drag & drop uploads**: Modern file upload experience
- **Smart compression**: Automatic image optimization for faster loading
- **Secure storage**: File validation and safe storage practices
- **Gallery views**: Beautiful, responsive image display

### 🔔 **Interactive Notifications**
- **SweetAlert2 integration**: Beautiful, non-intrusive user feedback
- **Real-time updates**: Instant confirmation of actions
- **Error handling**: Clear, helpful error messages

---

## 🏗️ Architecture & Design Philosophy

### **MVC Pattern Implementation**
```
📁 eNilai/
├── 📁 models/          # Data logic and database interactions
├── 📁 views/           # User interface templates
├── 📁 controllers/     # Business logic and request handling
├── 📁 config/          # Configuration files
├── 📁 assets/          # CSS, JS, and image files
└── 📁 uploads/         # User-uploaded content
```

### **Security-First Approach**
- **SQL Injection Protection**: Prepared statements throughout
- **File Upload Security**: Type validation, size limits, and secure storage
- **Session Management**: Proper session handling and timeout controls
- **Access Control**: Role-based permissions at every level

---

## 🛠️ Technology Stack

<div align="center">

| Frontend | Backend | Database | Server |
|----------|---------|----------|---------|
| ![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white) | ![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white) | ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white) | ![Apache](https://img.shields.io/badge/Apache-D22128?style=for-the-badge&logo=apache&logoColor=white) |
| ![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white) | ![cURL](https://img.shields.io/badge/cURL-073551?style=for-the-badge&logo=curl&logoColor=white) | ![phpMyAdmin](https://img.shields.io/badge/phpMyAdmin-6C78AF?style=for-the-badge&logo=phpmyadmin&logoColor=white) | ![XAMPP](https://img.shields.io/badge/XAMPP-FB7A24?style=for-the-badge&logo=xampp&logoColor=white) |
| ![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black) | | | |

</div>

---

## 🚀 Quick Start Guide

### Prerequisites
- **XAMPP** (or similar LAMP stack)
- **Web browser** (Chrome, Firefox, Safari, Edge)
- **Basic PHP knowledge** (for customization)

### 📦 Installation Steps

#### 1. **Get the Code**
```bash
# Clone the repository
git clone <repository-url> eNilai

# Or download and extract ZIP to your htdocs folder
# Location: C:\xampp\htdocs\eNilai (Windows)
# Location: /Applications/XAMPP/htdocs/eNilai (macOS)
```

#### 2. **Database Setup**
```sql
-- Create database in phpMyAdmin
CREATE DATABASE enilai CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Import the provided schema
-- File: enilai.sql (included in repository)
```

#### 3. **Configuration**
```php
// config/database.php
$host = 'localhost';
$dbname = 'enilai';
$username = 'root';
$password = ''; // Default XAMPP password is empty
```

#### 4. **Launch & Enjoy**
1. Start **XAMPP** Control Panel
2. Start **Apache** and **MySQL** services
3. Navigate to `http://localhost/eNilai`
4. Login with default credentials (see documentation)

---

## 🎯 Use Cases & Benefits

### **For Educational Institutions**
- Streamline grade management across multiple classes
- Reduce paperwork and administrative overhead
- Improve communication between teachers and students
- Generate comprehensive academic reports

### **For Teachers**
- Efficient grade entry and modification
- Track student progress over time
- Secure access to only relevant student data
- Quick grade distribution and updates

### **For Students**
- Instant access to current grades
- Historical grade tracking
- Transparent academic progress monitoring
- Secure, personalized learning dashboard

---

## 🔧 Advanced Features

### **API-Ready Architecture**
- RESTful design principles
- cURL-based inter-controller communication
- JSON response formatting
- Extensible for mobile app integration

### **Performance Optimizations**
- Efficient database queries with indexing
- Image compression and caching
- Minimal resource footprint
- Fast loading times across all devices

### **Customization Options**
- Modular CSS for easy theming
- Configurable user roles and permissions
- Extensible plugin architecture
- Multi-language support ready

---

## 📚 Documentation & Support

### **Default Login Credentials**
| Role | Username | Password |
|------|----------|----------|
| Admin | `admin` | `admin123` |
| Teacher | `teacher1` | `teacher123` |
| Student | `student1` | `student123` |

### **File Structure Guide**
```
eNilai/
├── 🔧 config/
│   ├── database.php      # Database configuration
│   └── settings.php      # Application settings
├── 🎮 controllers/
│   ├── AuthController.php
│   ├── GradeController.php
│   └── GalleryController.php
├── 🏗️ models/
│   ├── User.php
│   ├── Grade.php
│   └── Gallery.php
├── 🎨 views/
│   ├── dashboard/
│   ├── grades/
│   └── gallery/
├── 📁 assets/
│   ├── css/
│   ├── js/
│   └── images/
└── 📄 uploads/           # User uploaded files
```

---

## 🌟 What Makes eNilai Special?

✨ **Educational Focus**: Built by students, for students — understanding real academic needs

🛡️ **Security-First**: Every feature designed with data protection in mind

🎨 **User Experience**: Clean, intuitive interface that anyone can master

🔧 **Maintainable Code**: Well-documented, organized codebase following best practices

🚀 **Scalable Design**: Ready to grow with your institution's needs

---

## 🤝 Contributing & Future Roadmap

This project represents the beginning of something bigger. Whether you're a student, teacher, or developer, your contributions can help make academic management better for everyone.

### **Planned Features**
- 📱 Mobile app companion
- 📊 Advanced analytics dashboard
- 🔗 LMS integration capabilities
- 🌐 Multi-language support
- 📧 Email notification system

---

<div align="center">

**Built with ❤️ for the education community**

*Transform your academic management experience with eNilai*

---

*"Education is the most powerful weapon which you can use to change the world."* — Nelson Mandela

</div>