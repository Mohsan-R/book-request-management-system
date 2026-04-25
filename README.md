# Book Request Management System (BookHive)

A multi-role web application built with PHP and MySQL for discovering, requesting, and managing books using Google Books API integration.



# Features

## User Module

 User Registration
 Secure Login
 Dashboard with request statistics
 Request books by category
 Google Books API integration
 Cancel pending requests
 Logout

## Admin Module

* Admin Login
* Dashboard statistics
* Total Users
* Total Requests
* In Progress Requests
* Completed Requests

## Super Admin Module

* Super Admin Login
* Manage all requests
* Update request status
* Delete requests
* Manage users
* Reset user passwords
* Delete users
* Add/Delete admins

---

# Technologies Used

* PHP
* MySQL
* HTML
* CSS
* JavaScript
* Google Books API
* XAMPP

---

# Project Folder Structure

```text
book-request-system/
│
├── admin/
├── api/
├── config/
├── includes/
├── superadmin/
├── user/
│
├── index.php
├── logout.php
├── database.sql
└── README.md
```

---

# Setup Instructions

## Step 1: Install XAMPP

Install XAMPP and start:

* Apache
* MySQL

## Step 2: Copy Project Folder

Place project inside:

```text
C:\xampp\htdocs\
```

Example:

```text
C:\xampp\htdocs\book-request-system
```

## Step 3: Create Database

Open phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Create database:

```text
book_request_system
```

## Step 4: Import SQL File

Import:

```text
database.sql
```

## Step 5: Run Project

Open:

```text
http://localhost/book-request-system/
```

---

# Default Login Credentials

## Admin

* Username: admin
* Password: admin123

## Super Admin

* Username: superadmin
* Password: super123

---

# Notes

* Internet connection is required for Google Books API.
* Passwords are stored securely using `password_hash()`.
* SQL queries use prepared statements for security.

---

# Author

Mohsan Raza
BS Computer Science


