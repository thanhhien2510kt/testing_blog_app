# QA Insider Blog Project

A simple MVC generic PHP Blog application.

## Prerequisites
- XAMPP (PHP >= 7.4, MySQL)

## Setup Instructions

1.  **Database Setup**:
    -   Open PHPMyAdmin (`http://localhost/phpmyadmin`).
    -   Create a new database named `blog_db` (or it will be created by the script if you have permissions).
    -   Import the file `sql/schema.sql`.

2.  **Configuration**:
    -   Open `app/config/config.php`.
    -   Check the database credentials. Default is:
        -   User: `root`
        -   Pass: `` (empty)
        -   DB: `blog_db`
    -   Update `URLROOT` if your path is different from dynamic detection.

3.  **Run**:
    -   Start XAMPP Apache and MySQL.
    -   Open your browser and navigate to:
        `http://localhost/testing-blog-app/public`

## Features
-   **Structure**: Custom MVC (Model-View-Controller).
-   **Database**: PDO Connection in `app/core/Database.php`.
-   **Frontend**: Bootstrap 5 + Custom CSS.

## Default Admin User
-   **Email**: admin@example.com
-   **Password**: admin123
