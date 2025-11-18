# Loader Panel

Loader Panel is a lightweight PHP web application for requesting, tracking, and managing invoices. It provides a minimal admin UI, invoice request flow, invoice status tracking (requested, unpaid, pending, paid, rejected), and a simple email sender built on PHPMailer.

**Table of contents**

- [Project Overview](#project-overview)
- [Features](#features)
- [Repository Structure](#repository-structure)
- [Requirements](#requirements)
- [Installation & Quick Start](#installation--quick-start)
- [Configuration](#configuration)
- [Database schema](#database-schema)
- [Usage](#usage)
- [Email (SMTP) setup](#email-smtp-setup)
- [Security Notes & Recommendations](#security-notes--recommendations)
- [Troubleshooting](#troubleshooting)
- [Roadmap & Improvements](#roadmap--improvements)
- [Contributing](#contributing)
- [License](#license)

## Project Overview

Loader Panel provides a small administrative interface to create invoice requests for customers, review unpaid/pending/paid/rejected invoices, and send product-related emails through SMTP using PHPMailer. It is suitable as an internal tool or a starting point for a more feature-rich billing dashboard.

## Features

- Create invoice requests (customer email, product, amount)
- Persist invoices to a MySQL `invoices` table
- View invoices filtered by status (unpaid, pending, paid, rejected)
- Send emails using PHPMailer and save them to an `emails` table
- Basic session-based login with `loader_log` table
- PHPMailer included under `php/PHPMailer`

## Repository Structure

- `index.php` — Main admin UI (requires login)
- `login.php` — Login form
- `style.css` — UI styles
- `table.txt` — SQL snippets and table definitions used in the project
- `php/` — Server-side PHP code
  - `db_connect.php` — MySQL connection settings and `mysqli` connection (`$conn`)
  - `config.php` — SMTP credential array used by PHPMailer
  - `login_cre.php` — Authentication handler; reads `loader_log`
  - `request_invoice.php` — Inserts invoice rows into `invoices`
  - `send_email.php` — Sends emails via PHPMailer and logs to `emails`
  - `all_status_invoices.php` — Loads invoices and filters them by status
  - `PHPMailer/` — PHPMailer library files bundled with the project

## Requirements

- PHP 7.4+ (PHP 8 recommended) with `mysqli` extension
- MySQL or MariaDB server
- Web server (Apache, Nginx) or use the PHP built-in server for development

## Installation & Quick Start

1. Place the project in your webserver document root or a development folder.
2. Create a MySQL database and a user with privileges for that database.
3. Edit `php/db_connect.php` to set your database host, username, password and database name.
4. Edit `php/config.php` to set your SMTP host, username, password and port.
5. Create the database tables (see Database schema). The application attempts to create missing tables at runtime, but creating them manually is recommended for production.

To run locally using PHP built-in server (for dev/testing):

```powershell
cd 'c:\Users\Soft-Tech Technology\Desktop\SoftTech All Projects\loader-panel'
php -S localhost:8000
```

Open `http://localhost:8000/login.php` to access the app.

## Configuration

- `php/db_connect.php`: change the following variables to match your database credentials:

```php
$servername = "localhost";
$username = "your_db_user";
$password = "your_db_password";
$dbname = "your_database_name";
```

- `php/config.php`: configure SMTP credentials used by PHPMailer. Example structure already in the project (move to environment variables in production):

```php
$credential = [
    "host" => "smtp.example.com",
    "username" => "user@example.com",
    "password" => "secret",
    "port" => 465
];
```

## Database schema

The application uses three primary tables. The app code includes CREATE statements which run at runtime if the tables do not exist, but you can also create them manually using these SQL statements.

- `invoices` table

```sql
CREATE TABLE IF NOT EXISTS invoices(
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_email VARCHAR(100) DEFAULT NULL,
  customer_name VARCHAR(100) DEFAULT NULL,
  desired_product VARCHAR(255) DEFAULT NULL,
  invoice_amount DECIMAL(10, 2) DEFAULT 0,
  invoice_number VARCHAR(50) DEFAULT NULL,
  invoice_link TEXT DEFAULT NULL,
  invoice_purpose VARCHAR(255) DEFAULT NULL,
  cost DECIMAL(10, 2) DEFAULT 0,
  payable_amount DECIMAL(10, 2) DEFAULT 0,
  due_date VARCHAR(255) DEFAULT NULL,
  remark VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  status ENUM('requested','unpaid','pending','paid','rejected') DEFAULT 'requested'
);
```

- `loader_log` (login table)

```sql
CREATE TABLE IF NOT EXISTS loader_log(
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id VARCHAR(100) NOT NULL,
  password VARCHAR(50) NOT NULL
);
```

To create an initial admin user (example):

```sql
INSERT INTO loader_log (user_id, password) VALUES ('admin', 'yourpassword');
```

> IMPORTANT: passwords are stored as plaintext by default in this project. See Security Notes to change this behavior.

- `emails` table

```sql
CREATE TABLE IF NOT EXISTS emails(
  id INT AUTO_INCREMENT PRIMARY KEY,
  product VARCHAR(255) NOT NULL,
  recipent VARCHAR(100) NOT NULL,
  subject TEXT NOT NULL,
  body TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Usage

- Login: Open `login.php` and sign in with credentials stored in `loader_log`.
- Request invoice: Open the "Request invoice" form in the sidebar. Enter customer email, name, select product and submit. This creates a row in `invoices` with status `requested`.
- Invoice status pages: Use the sidebar to view Unpaid, Pending, Paid, and Rejected invoices. The server-side code loads all invoices and filters them by `status`.
- Payment flow: The UI places an `invoice_link` in unpaid invoices; selecting "Pay now" navigates to that link if present.
- Send email: Open "Send Email" in the sidebar to send an email. Sent emails are logged into `emails`.

## Email (SMTP) setup

- `php/config.php` contains the `$credential` array used by `send_email.php` to configure PHPMailer. Update `host`, `username`, `password`, and `port` to match your SMTP provider.
- Example providers:
  - For SMTPS use port `465` and `PHPMailer::ENCRYPTION_SMTPS` (current code uses SMTPS by default).
  - For STARTTLS use port `587` and change PHPMailer encryption accordingly.
- If using Gmail, create an App Password (if using 2FA) or enable "less secure apps" (not recommended).

## Security Notes & Recommendations

The current implementation is minimal and includes several risky defaults for quick testing. Before using in production, address the following:

- Secrets in git: Remove credentials from `php/config.php` and `php/db_connect.php`. Use environment variables or a `.env` file outside the web root.
- Hash passwords: Replace plaintext storage with `password_hash()` on signup and `password_verify()` on login.
- CSRF protection: Add CSRF tokens on all POST forms.
- Input validation & escaping: Validate and sanitize all inputs server-side and escape output to avoid XSS.
- Session security: Use `session_regenerate_id()` on login and set cookie flags (`secure`, `httponly`, `samesite`).
- Use HTTPS in production to protect credentials and session cookies.

## Troubleshooting

- Database connection errors: verify the values in `php/db_connect.php`, ensure MySQL is running and accessible from the web server.
- SMTP errors: PHPMailer prints error info on failure; check `php/send_email.php` output, verify SMTP host/port/credentials and network connectivity.
- Missing tables: either run the SQL in this README or let the app create tables automatically (it runs CREATE TABLE IF NOT EXISTS at runtime for key tables).

## Roadmap & Improvements

- Hash and salt user passwords and add role-based access control.
- Move configuration to environment variables and add a setup/installer script.
- Centralize DB code and error handling; add a small data access layer.
- Add server-side validation, CSRF protection, and stronger sanitization.
- Add automated tests and a CI pipeline for continuous integration.

## Contributing

Contributions are welcome. Please open an issue to propose changes before submitting a PR. Keep changes small and focused.

## License

This repository currently does not include an explicit license. Add a `LICENSE` file (for example the MIT license) if you wish to share or publish the code with clear terms.
