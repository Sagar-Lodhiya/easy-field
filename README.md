# 📊 EasyField – Admin Panel (Web Dashboard)

The **EasyField Admin Panel** is a web-based dashboard built to manage field operations, assign tasks, monitor real-time agent activity, review expenses, and analyze overall workforce performance.

It syncs seamlessly with the **EasyField Android Application** using **Firebase** backend services for real-time updates and data integrity.

---

## 🚀 Features

### 🧑‍💼 Admin Features
* **Assign, update, and manage tasks** for field agents.
* **Monitor real-time agent tracking** and activity status.
* **Approve or reject expenses** submitted by agents.
* **Review geo-tagged visit logs** for validation.
* **Generate detailed performance reports** and analytics.

### 👨‍🏫 Supervisor Features
* **View team activity and analytics** for their assigned group.
* **Validate completed tasks and visits** submitted by agents.
* **Oversee expense submissions** before final admin approval.

### 🔐 Authentication
* **Firebase Authentication** (Admin & Supervisor login) is used for secure access.
* **Role-based access control** ensures users only see relevant data and features.

---

## 🛠️ Technology Stack

| Component | Technology |
| :--- | :--- |
| **Framework** | PHP (Yii Framework) |
| **Frontend** | HTML5, CSS3, **Bootstrap**, JavaScript |
| **Backend** | PHP (**MVC Architecture**) |
| **Database (Web)** | **MySQL** |
| **Realtime Sync** | Firebase Firestore |
| **Authentication** | Firebase Authentication |
| **File Storage** | Firebase Cloud Storage |
| **Maps** | **Google Maps API** |
| **CMS Integration** | WordPress (optional) |

---

## 🧩 System Architecture (Admin Panel)

The Admin Panel follows the **Model-View-Controller (MVC)** Architecture, primarily driven by the **Yii Framework** integration.

### Model
* Handles **MySQL CRUD operations** for relational data.
* Integrates **Firebase Admin SDK** for real-time synchronization.
* Manages core data entities: tasks, agents, visits, and expenses.

### View
* Dashboard UI built with **Bootstrap** for responsiveness.
* Contains responsive pages for tasks, visits, expenses, and analytics.

### Controller
* Processes **admin & supervisor actions** and requests.
* Handles routing, session management, and data validation.
* Connects views with backend logic and models.

### Project Structure Example

Markdown

# 📊 EasyField – Admin Panel (Web Dashboard)

The **EasyField Admin Panel** is a web-based dashboard built to manage field operations, assign tasks, monitor real-time agent activity, review expenses, and analyze overall workforce performance.

It syncs seamlessly with the **EasyField Android Application** using **Firebase** backend services for real-time updates and data integrity.

---

## 🚀 Features

### 🧑‍💼 Admin Features
* **Assign, update, and manage tasks** for field agents.
* **Monitor real-time agent tracking** and activity status.
* **Approve or reject expenses** submitted by agents.
* **Review geo-tagged visit logs** for validation.
* **Generate detailed performance reports** and analytics.

### 👨‍🏫 Supervisor Features
* **View team activity and analytics** for their assigned group.
* **Validate completed tasks and visits** submitted by agents.
* **Oversee expense submissions** before final admin approval.

### 🔐 Authentication
* **Firebase Authentication** (Admin & Supervisor login) is used for secure access.
* **Role-based access control** ensures users only see relevant data and features.

---

## 🛠️ Technology Stack

| Component | Technology |
| :--- | :--- |
| **Framework** | PHP (Yii Framework) |
| **Frontend** | HTML5, CSS3, **Bootstrap**, JavaScript |
| **Backend** | PHP (**MVC Architecture**) |
| **Database (Web)** | **MySQL** |
| **Realtime Sync** | Firebase Firestore |
| **Authentication** | Firebase Authentication |
| **File Storage** | Firebase Cloud Storage |
| **Maps** | **Google Maps API** |
| **CMS Integration** | WordPress (optional) |

---

## 🧩 System Architecture (Admin Panel)

The Admin Panel follows the **Model-View-Controller (MVC)** Architecture, primarily driven by the **Yii Framework** integration.

### Model
* Handles **MySQL CRUD operations** for relational data.
* Integrates **Firebase Admin SDK** for real-time synchronization.
* Manages core data entities: tasks, agents, visits, and expenses.

### View
* Dashboard UI built with **Bootstrap** for responsiveness.
* Contains responsive pages for tasks, visits, expenses, and analytics.

### Controller
* Processes **admin & supervisor actions** and requests.
* Handles routing, session management, and data validation.
* Connects views with backend logic and models.

### Project Structure Example

AdminPanel/ ├── assets/ # CSS, JS, media files ├── config/ # Yii framework configuration ├── controllers/ # Controllers (Admin, Task, Expense, Visit) ├── models/ # Web app + Firebase models ├── views/ # UI templates (dashboard, tasks, expenses, etc.) ├── firebase/ # Firebase Admin SDK setup ├── index.php # Application entry point └── README.md

---

### 2️⃣ Install dependencies

**Requirements:**
* PHP 7.4+
* Composer
* MySQL Server
* Firebase Admin SDK

**Install dependencies:**
```bash
composer install

3️⃣ Setup MySQL Database
Create database and import schema:

mysql -u root -p easyfield < database/schema.sql

Configure config/db.php (Update credentials as necessary):

'dsn' => 'mysql:host=localhost;dbname=easyfield',
'username' => 'root',
'password' => '',

4️⃣ Configure Firebase
Place Firebase Service Account Key: Ensure your Firebase Admin SDK service account key is placed here:

firebase/serviceAccountKey.json

Verify Initialization: Check the initialization path in:

firebase/firebase_init.php

5️⃣ Run the Application
Use the Yii built-in server:

php yii serve

Visit the application: Open your browser to:

http://localhost:8080

🔐 Security
Firebase-secured login with robust authentication.

Role-Based Access Control (RBAC) enforcement.

MySQL prepared statements to prevent SQL Injection.

Protected API keys and configuration settings.

Firebase Security Rules for data integrity.

🧪 Testing
Unit testing for models and controllers (using PHPUnit).

Firebase Emulator used for safe testing of real-time functions.

Browser testing (Chrome & Firefox) for UI/UX validation.

Dedicated MySQL test database for integration tests.

🚧 Future Enhancements
AI-based workload prediction and resource allocation.

Auto-task assignment based on agent location and availability.

In-app chat functionality (admin ↔ agent).

Advanced PDF/Excel analytics exports.

Multi-language support for international deployment.

👨‍💻 Developer
Lodhiya Sagar Mahendrabhai (M25CSE017) Indian Institute of Technology, Jodhpur Guided by Dr. Sumit Karla

