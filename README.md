📊 EasyField – Admin Panel (Web Dashboard)

The EasyField Admin Panel is a web-based dashboard designed for administrators and supervisors to manage field operations, assign tasks, track agent activity, review expenses, and monitor real-time field performance.
It acts as the central control system of the EasyField platform and works in sync with the Android mobile application via Firebase backend services.

🚀 Features
🧑‍💼 Admin Features

Create, assign, update, and delete tasks for field agents

Approve or reject expense submissions

Monitor real-time agent tracking and geo-tagged visits

View activity timelines, visit logs, and task progress

Download or export reports (tasks, visits, expenses)

👨‍🏫 Supervisor Features

Review team performance

View summarized analytics and dashboards

Access logs of completed tasks & visits

Validate field activity for auditing

🔐 Authentication

Firebase Authentication (Admin + Supervisor access)

Secure role-based access control

🛠️ Technology Stack (Admin Panel)
Component	Technology
Framework	PHP (Yii Framework)
Frontend	HTML5, CSS3, JavaScript, Bootstrap
Backend	PHP (MVC Architecture)
Database	MySQL
Realtime Sync	Firebase Firestore
Authentication	Firebase Auth (Admin Login)
Storage	Firebase Cloud Storage
Maps	Google Maps API (for location & tracking)
CMS Integration	WordPress (optional for content pages)
🧩 System Architecture (Admin Panel Only)

The Admin Panel follows MVC (Model–View–Controller):

Model

Handles MySQL database operations

Integrates Firebase Admin SDK for real-time updates

Manages tasks, expenses, agent profiles, and analytics

View

Responsive UI built with Bootstrap

Displays dashboards, tables, statistics, visit logs

Admin forms for task creation and expense review

Controller

Handles incoming admin/supervisor requests

Processes data and serves responses to views

Manages routing between pages

📂 Project Structure
AdminPanel/
├── assets/               # CSS, JS, images
├── config/               # Yii configuration files
├── controllers/          # Main controllers (TaskController, AdminController)
├── models/               # Database models (Task.php, Agent.php, Expense.php)
├── views/                # All UI pages
│   ├── dashboard/
│   ├── tasks/
│   ├── expenses/
│   ├── login/
│   └── analytics/
├── firebase/             # Firebase Admin SDK configuration
├── index.php             # Entry point
└── README.md

🔧 Installation & Setup
1. Clone the repository
git clone https://github.com/yourusername/easyfield-admin-panel.git
cd easyfield-admin-panel

2. Install dependencies

Required:

PHP 7.4+

Composer

MySQL Server

Firebase Admin PHP SDK

Run:

composer install

3. Configure MySQL

Import the database schema:

mysql -u root -p easyfield < database/schema.sql


Update config/db.php:

'dsn' => 'mysql:host=localhost;dbname=easyfield',
'username' => 'root',
'password' => '',

4. Configure Firebase Admin SDK

Place the Firebase Admin SDK key file:

/firebase/serviceAccountKey.json


Update Firebase config in:

firebase/firebase_init.php

5. Run the server
php yii serve


Access the dashboard:

http://localhost:8080

🌐 Admin Roles
Admin

Assign tasks

Track real-time locations

Monitor all agents

Approve expenses

Generate reports

Supervisor

Review logs & analytics

Validate task completion

Oversee teams

View summaries

🔐 Security

Role-based access control (RBAC)

Firebase Authentication login

Server-side validation for every action

MySQL protected with prepared statements

Secure API key handling

📑 API Integrations

Firebase Authentication for login

Firestore for real-time task/visit sync

Cloud Storage for expense image uploads

Google Maps API for visualization

🧪 Testing

Manual and automated testing for UI

PHP Unit tests for Models and Controllers

Firebase emulator used for safe testing

MySQL test database for integration testing

📈 Future Enhancements

AI-based performance analytics

Predictive task assignment

Chat system between admin & field agents

Multi-language dashboard

Bulk task upload (Excel/CSV)

👨‍💻 Developer

Lodhiya Sagar Mahendrabhai (M25CSE017)
Indian Institute of Technology, Jodhpur
Guided by Dr. Sumit Karla

📜 License

This Admin Panel is part of the EasyField Major Project under
Software Data Engineering (CSL7090).
