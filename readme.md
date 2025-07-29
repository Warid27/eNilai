eNilai — Student Grade Management Web Application
eNilai is a simple, PHP-based web application designed for managing student grades and an image gallery, developed as a school project using native PHP without frameworks. It follows the Model-View-Controller (MVC) pattern for organized, maintainable, and scalable code.
✨ Key Features
✅ User Authentication

Secure login system with session-based authentication using username and password.
Role-based access control for different user types.

📊 Dashboard

Admin-exclusive dashboard with a summary of data and quick navigation to core features.

🧑‍💼 User Roles

Admin (id_role: 1): Full access to manage users, grades, and gallery.
Teacher (id_role: 2-5): Add and edit student grades based on assigned subjects.
Student (id_role: 6): View personal grades and limited gallery access.

✏️ Student Grades CRUD

Create: Add new grades for students.
Read: Display grades in a tabular format.
Update: Modify existing grades.
Delete: Remove grades from the system.

🖼️ Image Gallery CRUD

Create: Upload images (e.g., profile photos or documents).
Read: Display images in a visual gallery.
Update: Replace existing images.
Delete: Remove images from the gallery and server.

🔔 Notifications

Interactive success/error messages using SweetAlert2 for a better user experience.

🔒 Security

Session-based authentication for protected pages like the dashboard.
Prepared statements to prevent SQL injection.
File type and size validation for secure image uploads.
Configuration and debugging utilities stored outside the public directory.

🛠️ Technologies

Backend: Native PHP (MVC pattern)
Database: MySQL (managed via phpMyAdmin)
Frontend: HTML, CSS, JavaScript (SweetAlert2 via CDN)
API Client: cURL for inter-controller communication
Server: Apache (XAMPP recommended for local development)

🚀 Setup Instructions
1. Clone or Download the Repository
Clone the repository or download it as a ZIP and extract it to your htdocs folder (if using XAMPP):
git clone <repository-url>

2. Set Up the Database

Create a database named enilai in phpMyAdmin.
Import the following schema (save as enilai.sql):