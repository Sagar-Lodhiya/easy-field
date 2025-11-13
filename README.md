📊 EasyField – Admin Panel (Web Dashboard)

The EasyField Admin Panel is a web-based dashboard built to manage field operations, assign tasks, monitor real-time agent activity, review expenses, and analyze overall workforce performance.
It syncs seamlessly with the EasyField Android Application using Firebase backend services.

🚀 Features
🧑‍💼 Admin Features

Assign, update, and manage tasks

Monitor real-time agent tracking

Approve or reject expenses

Review geo-tagged visit logs

Generate performance reports

👨‍🏫 Supervisor Features

View team activity and analytics

Validate completed tasks and visits

Oversee expense submissions

🔐 Authentication

Firebase Authentication (Admin & Supervisor login)

Role-based access control

| Component       | Technology                         |
| --------------- | ---------------------------------- |
| Framework       | PHP (Yii Framework)                |
| Frontend        | HTML5, CSS3, Bootstrap, JavaScript |
| Backend         | PHP (MVC Architecture)             |
| Database (Web)  | MySQL                              |
| Realtime Sync   | Firebase Firestore                 |
| Authentication  | Firebase Authentication            |
| File Storage    | Firebase Cloud Storage             |
| Maps            | Google Maps API                    |
| CMS Integration | WordPress (optional)               |


🧩 System Architecture (Admin Panel)

The Admin Panel follows the MVC Architecture:

Model

Handles MySQL CRUD operations

Integrates Firebase Admin SDK for real-time sync

Manages tasks, agents, visits, and expenses

View

Dashboard UI built with Bootstrap

Responsive pages for tasks, visits, expenses, analytics

Controller

Processes admin & supervisor actions

Handles routing and data validation

Connects views with backend logic

AdminPanel/
├── assets/               # CSS, JS, media files
├── config/               # Yii framework configuration
├── controllers/          # Controllers (Admin, Task, Expense, Visit)
├── models/               # Web app + Firebase models
├── views/                # UI templates (dashboard, tasks, expenses, etc.)
├── firebase/             # Firebase Admin SDK setup
├── index.php             # Entry point
└── README.md

🔧 Installation & Setup
1️⃣ Clone the repository

git clone https://github.com/yourusername/easyfield-admin-panel.git
cd easyfield-admin-panel

2️⃣ Install dependencies

Requirements:

PHP 7.4+

Composer

MySQL Server

Firebase Admin SDK

Install dependencies:

composer install

3️⃣ Setup MySQL Database

Create database:

mysql -u root -p easyfield < database/schema.sql
Configure config/db.php:

php
Copy code
'dsn' => 'mysql:host=localhost;dbname=easyfield',
'username' => 'root',
'password' => '',
4️⃣ Configure Firebase
Place Firebase key:

bash
Copy code
firebase/serviceAccountKey.json
Initialize in:

bash
Copy code
firebase/firebase_init.php
5️⃣ Run the Application
bash
Copy code
php yii serve
Visit:

arduino
Copy code
http://localhost:8080
🌐 User Roles
🔹 Admin
Full access

Task creation & monitoring

Expense approval

Detailed analytics

🔹 Supervisor
View dashboards

Review visit logs

Validate tasks

🔐 Security
Firebase-secured login

RBAC (Admin / Supervisor)

MySQL prepared statements

Protected API keys

Firebase Security Rules

🧪 Testing
Unit testing for models and controllers

Firebase Emulator for safe testing

Browser testing (Chrome & Firefox)

MySQL test database for integration tests

🚧 Future Enhancements
AI-based workload prediction

Auto-task assignment

In-app chat (admin ↔ agent)

PDF/Excel analytics exports

Multi-language support

👨‍💻 Developer
Lodhiya Sagar Mahendrabhai (M25CSE017)
Indian Institute of Technology, Jodhpur
Guided by Dr. Sumit Karla

📜 License
This Admin Dashboard is part of the EasyField Major Project for
Software Data Engineering (CSL7090).
