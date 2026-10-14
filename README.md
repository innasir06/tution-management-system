# 🎓 Tuition Management System

A web-based application that helps tuition academies manage students, attendance, fees, and exam results from one place. It replaces manual record keeping with a central database and role-based access.

Built as a team project (3 members) for the Software Construction and Development course at Fatima Jinnah Women University.

## ✨ Features
- **Role-based login:** admin and student accounts
- **Dashboard:** total enrollment, today's attendance, total revenue, and low-attendance alerts
- **Student management:** add, edit, delete, search, and view student profiles
- **Attendance:** daily roll call (present/absent) with alerts for absent students
- **Fee management:** total fee, paid amount, balance due, fee updates, and quick pay
- **Exam results:** enter marks, automatic grade and pass/fail status, SMS alert for failed students
- **Student portal:** students log in to view their own attendance and outstanding balance

## 🛠 Tech Stack
PHP • MySQL • HTML • CSS • Bootstrap • JavaScript • Apache (XAMPP)

## 🗄 Database
MySQL database `tuition_db` with tables for users, students, teachers, courses, classes, enrollments, attendance, fees, payments, exams, results, and notifications.

## ▶️ Run Locally
1. Install [XAMPP](https://www.apachefriends.org/) and start Apache and MySQL
2. Copy this project into `C:\xampp\htdocs\tuition_management_system`
3. Create a database named `tuition_db` in phpMyAdmin and create the tables
4. Check the database settings in `db.php`
5. Open `http://localhost/tuition_management_system/index.php`

## 📸 Screenshots & Documentation
📄 [Project report with screenshots (PDF)](project%20report%20with%20screenshot.pdf)
## 👥 Team
Inshal Nasir • Alina Iman • Bushra Iman
