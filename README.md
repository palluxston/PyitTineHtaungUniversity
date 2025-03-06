# PyitTineHtaung University Management System

A comprehensive university management system designed to streamline academic and administrative operations.

## Features

### User Management
- Multi-role authentication system (Admin, Faculty, Student)
- Secure login and session management
- Profile management for all users
- Centralized user administration

### Academic Management
- Course management and enrollment
- Assignment creation and submission
- Grading system
- Academic performance tracking
- Transcript generation (PDF format)

### Communication
- Contact form for public inquiries
- Internal messaging system
- Activity tracking
- Announcement system

## Technical Requirements

- PHP 8.2 or higher
- MySQL/MariaDB 10.4 or higher
- Apache Web Server
- XAMPP (recommended for local development)

### Coding Distribution

Approximate Distribution:

1. PHP (≈65%)
   
   - Main backend logic files in /admin/ , /faculty/ , /student/
   - Core functionality files like connect.php
   - Database interactions and user management
   - Most files contain mixed PHP and HTML
2. HTML (≈15%)
   
   - Embedded within PHP files
   - Template structures
   - Content layouts in /public/
3. CSS (≈12%)
   
   - Style files like admin_style.css
   - Embedded styles in PHP files
   - UI component styling
   - Responsive design rules
4. JavaScript (≈8%)
   
   - Client-side validations
   - Interactive features
   - AJAX calls for dynamic content
   - Files in /javascript/ directory
Key Observations:

- Most files are PHP with embedded HTML
- CSS is both in separate files and inline styles
- JavaScript is used primarily for frontend interactivity
- The project follows a typical LAMP stack structure

## Installation

1. Clone the repository:
```bash
git clone https://github.com/palluxston/PyitTineHtaungUniversity.git