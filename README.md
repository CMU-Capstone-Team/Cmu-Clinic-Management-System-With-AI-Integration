# Cmu-Clinic-Management-System-With-AI-Integration
This project is intended for educational purposes 


cmu-clinic-ai/
│
├── 📁 resources/views/           # [FRONTEND PAGES]
│   ├──  layouts/
│   │   └── app.blade.php         # [SHARED] Main layout (Sidebar, Header) - DONE ✔️
│   │
│   ├── 📁 auth/                  # [JOVIC] Login, Register, Forgot Password pages
│   │   ├── login.blade.php
│   │   └── register.blade.php    # Student Pre-reg form UI
│   │
│   ├── 📁 admin/                 # [MARKEOZI] Admin Dashboard & Management Pages
│   │   ├── dashboard.blade.php   # Stats, Pending Approvals Card
│   │   ├── students/
│   │   │   ├── index.blade.php   # Listahan ng students + Approval buttons
│   │   │   └── show.blade.php    # View Student Profile/Medical History
│   │   └── visits/
│   │       └── index.blade.php   # Clinic Visit Logbook
│   │
│   └── 📁 student/               # [JOVIC] Student Portal Pages
│       ├── pre-register.blade.php # Pre-reg Form Page
│       ├── status.blade.php      # "Waiting for Approval" page
│       └── profile.blade.php     # View own medical records
│
── 📁 app/Http/Controllers/      # [BACKEND LOGIC]
│   ├── Auth/
│   │   ├── LoginController.php   # [JOVIC] Handle login logic
│   │   └── RegisterController.php# [JOVIC] Handle pre-reg submission (Status: pending)
│   │
│   ├── Admin/
│   │   ├── DashboardController.php # [MARKEOZI] Fetch stats, pending count
│   │   ├── StudentController.php   # [MARKEOZI] Approve/Reject students, Manage records
│   │   └── ClinicVisitController.php # [MARKEOZI] Record visits, Generate reports
│   │
│   └── Student/
│       └── PortalController.php  # [JOVIC] Show registration status, View own profile
│
├──  routes/                    # [SHARED] URL Definitions
│   └── web.php                   # Hatiin ang routes: Auth, Admin, Student
│
├──  database/migrations/       # [SHARED] Database Schema
│   ├── create_users_table.php    # [SHARED] Admin/Staff accounts
│   ├── create_students_table.php # [JOVIC] Student info + 'status' column
│   └── create_clinic_visits_table.php # [MARKEOZI] Visit logs, complaints, diagnosis
│
├── 📁 database/seeders/          # [SHARED] Dummy Data
│   ├── AdminSeeder.php           # [MARKEOZI] Default admin account
│   ── StudentSeeder.php         # [JOVIC] Sample students for testing
│
└── 📄 .env                       # [SHARED] DB Config, Mail Settings (for approval email)
