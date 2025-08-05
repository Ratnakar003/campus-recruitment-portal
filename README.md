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
<img width="1912" height="909" alt="image" src="https://github.com/user-attachments/assets/ddbb7c3c-37d1-4e6a-b15e-c329bb4f72c4" />
<img width="1919" height="900" alt="image" src="https://github.com/user-attachments/assets/7545ef20-f5de-49a1-92db-4cc066a7032e" />


## 📄 License
MIT

## 🙋‍♂️ Author

**Ratnakar Lalam**  
📍 India  
💼 [LinkedIn Profile](https://www.linkedin.com/in/lalam-ratnakar-a807402b3/)    
🔗 GitHub: [github.com/ratnakar-lalam](https://github.com/Ratnakar003)


