# Campus Recruitment Portal 🎓💼

A full-stack web application for campus recruitment built using PHP, MySQL, and Bootstrap.

## 👥 User Roles
- **Student**: Register, upload resume, apply for jobs, track applications
- **Recruiter**: Post jobs, view applicants, shortlist or reject
- **Admin**: View charts, monitor jobs, users, and applications

## 🔐 Features
- Login/Registration with Role Selection
- Resume Upload
- Job Posting & Application System
- Shortlist / Reject Applicants
- Password Hashing & Security
- Change Password & Forgot Password
- Admin Dashboard with Charts (Chart.js)

## 🛠 Tech Stack
- PHP
- MySQL
- HTML/CSS + Bootstrap
- Chart.js
- XAMPP (for local testing)

## 🧪 Setup Instructions
1. Clone the repo
2. Import `campus_portal.sql` into phpMyAdmin
3. Start Apache + MySQL (XAMPP)
4. Visit `http://localhost/Campus-recruitment-portal/`

## 📂 Database Structure
- `users` (id, name, email, password, role, phone, ...)
- `jobs` (id, title, recruiter_id, description, location)
- `applications` (id, student_id, job_id, resume_path, status)

## 🙋‍♂️ Demo Accounts
- Admin: `admin@example.com` / `admin123`

## 📸 Screenshots



## 📄 License
MIT
