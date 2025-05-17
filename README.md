# TaskTrek - Task Scheduling System

### Team Members
- **SAMANTHA W** (23PW25)  
- **DHARSHANA SK** (23PW06)
  

## Overview
This is a web application built to help users discover and track hangout spots in and around Coimbatore. The application provides categorized places for dining, games, parks, and more. Users can mark places as "Visited", which are then stored separately to help track where they've been.


## 🌐 Features

- 📍 Categorized hangout suggestions (Dining, Games, Parks, etc.)
- ✅ Checkbox functionality to mark places as "Visited"
- 🖼️ Image gallery for each location
- 🔒 User-friendly front-end with JavaScript
- 🛠️ Backend with PHP for dynamic data handling
- 📦 Configurable via `.env` and `config.php`


## 📁 Folder Structure

```bash
hangoutsWebdev/
├── assets/ # Contains images and JavaScript (app.js)
├── includes/ # Likely includes shared PHP files like headers or nav
├── pages/ # Individual PHP pages for categorized hangouts
├── .env # Environment-specific configuration
├── config.php # Database or site-wide config
├── test.php # Script for testing components
├── test_connection.php # Script to test database connection
└── README.md # This file

```


## ⚙️ Setup Instructions

1. **Clone or Extract** the project into your web server directory (e.g., `htdocs` for XAMPP).
2. **Update Configuration:**
   - Add your database and environment details to the `.env` and `config.php` files.
3. **Run a local server** (e.g., XAMPP) and access via `http://localhost/hangoutsWebdev/`.
4. **Check database connection:**
   - Use `test_connection.php` to verify if your DB is correctly connected.


## 🧪 Testing

- Navigate to `test.php` or `test_connection.php` in your browser to run basic setup tests.


## 🧑‍💻 Technologies Used

- Frontend: HTML, CSS, JavaScript
- Backend: PHP
- Database: MySQL (assumed from config.php)
- Image assets for UI illustration
