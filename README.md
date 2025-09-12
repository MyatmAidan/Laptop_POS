# Laptop E-commerce Store

A complete e-commerce solution for laptop sales with unified authentication system.

## Features

- **Unified Authentication System**: Single login/register system for both admin and customer users
- **Role-based Access Control**: Admin and customer roles with appropriate permissions
- **Modern UI**: Bootstrap 5 with custom styling for login/register pages
- **Secure Password Hashing**: Uses PHP's password_hash() function
- **Session Management**: Proper session handling and security

## Authentication System

### Files Structure
```
├── auth.php                    # Main authentication functions
├── login.php                   # Unified login page
├── register.php                # Unified registration page
├── logout.php                  # Unified logout
├── index.php                   # Main redirector
├── admin/
│   ├── index.php              # Admin dashboard
│   ├── layouts/
│   │   ├── header.php         # Admin header with auth
│   │   └── footer.php         # Admin footer
│   └── logout.php             # Admin logout redirect
└── user/
    ├── index.php              # User dashboard
    └── includes/
        ├── header.php         # User header
        └── nav.php            # User navigation with auth
```

### Authentication Functions

The `auth.php` file provides these functions:

- `isLoggedIn()` - Check if user is logged in
- `isAdmin()` - Check if user is admin
- `getCurrentUserId()` - Get current user ID
- `getCurrentUser()` - Get current user data
- `requireAuth()` - Require authentication for page access
- `requireAdmin()` - Require admin authentication
- `redirectIfLoggedIn()` - Redirect if already logged in
- `logout()` - Logout function

### Usage Examples

```php
// Require authentication for any page
require_once 'auth.php';
requireAuth();

// Require admin access
require_once 'auth.php';
requireAdmin();

// Check user role
if (isAdmin()) {
    // Admin specific code
} else {
    // Customer specific code
}
```

## Installation

1. **Database Setup**
   ```bash
   # The database will be created automatically when you first access the site
   # Or run the seeder manually:
   php database/seeder.php
   ```

2. **Default Users**
   - **Admin**: admin@laptopstore.com / admin123
   - **Customer**: customer@laptopstore.com / customer123

3. **Access the Application**
   - Main site: `http://localhost/Laptop_Ecommerce/`
   - Login: `http://localhost/Laptop_Ecommerce/login.php`
   - Admin: `http://localhost/Laptop_Ecommerce/admin/`
   - User: `http://localhost/Laptop_Ecommerce/user/`

## Security Features

- **Password Hashing**: All passwords are hashed using `password_hash()`
- **SQL Injection Protection**: Prepared statements used throughout
- **Session Security**: Proper session management
- **Input Validation**: Comprehensive form validation
- **XSS Protection**: Output escaping with `htmlspecialchars()`

## Database Schema

The system uses a single `user` table with the following structure:

```sql
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    confirm_password VARCHAR(255) NOT NULL,
    role ENUM('admin','customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## Removed Files

The following redundant files have been removed:
- `admin/login.php` - Replaced by unified login
- `admin/register.php` - Replaced by unified register
- `check_auth.php` - Replaced by auth.php functions
- `user/includes/register-exec.php` - Replaced by unified register

## Notes

- All authentication is now centralized in the root directory
- Admin and user areas are properly separated with role-based access
- Session variables are consistent across the application
- The system automatically redirects users to appropriate areas based on their role
# Laptop_POS
