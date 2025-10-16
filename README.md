# Parliament Intern Logbook System

A comprehensive web application for managing intern daily logs, evaluations, and feedback for the Parliament of Sri Lanka.

## 🚀 Features

### 🔐 Authentication & Authorization

- Role-based access control (Admin, Supervisor, Intern)
- Secure login/logout system
- Password hashing and CSRF protection
- Session management

### 👩‍💻 Intern Module

- **Daily Logs**: Add, edit, delete daily activity logs
- **Time Restrictions**: Edit/delete logs only within 24 hours
- **Weekly Reports**: Generate PDF summaries
- **Performance Tracking**: View supervisor evaluations
- **Skills Documentation**: Track learning progress

### 🧑‍🏫 Supervisor Module

- **Intern Management**: View assigned interns
- **Log Review**: Approve/reject daily logs with comments
- **Weekly Evaluations**: Rate technical and soft skills (1-5 scale)
- **Performance Analytics**: Track intern progress
- **Feedback System**: Provide constructive feedback

### 🧑‍💼 Admin Module

- **User Management**: Create, edit, delete users
- **Department Management**: Organize interns by departments
- **Data Export**: Export logs, users, and evaluations (CSV)
- **Announcements**: System-wide notifications
- **Analytics Dashboard**: Charts and statistics
- **Activity Logging**: Audit trail for all actions

## 🛠️ Technology Stack

- **Backend**: Pure PHP 8 (No frameworks)
- **Database**: MySQL 8.0+
- **Frontend**: Bootstrap 5 + jQuery
- **Charts**: Chart.js
- **Tables**: DataTables.js
- **Security**: PDO prepared statements, CSRF tokens, password hashing

## 📋 Requirements

- PHP 8.0 or higher
- MySQL 8.0 or higher
- Web server (Apache/Nginx)
- XAMPP/Laragon (for local development)

## 🚀 Installation

### 1. Clone/Download the Project

```bash
# If using Git
git clone <repository-url>
cd parliament-intern-logbook

# Or download and extract the ZIP file
```

### 2. Database Setup

#### Option A: Using phpMyAdmin (XAMPP/Laragon)

1. Open phpMyAdmin in your browser
2. Create a new database named `parliament_intern_logbook`
3. Import the `database.sql` file:
   - Click "Import" tab
   - Choose the `database.sql` file
   - Click "Go"

#### Option B: Using Command Line

```bash
# Connect to MySQL
mysql -u root -p

# Create database
CREATE DATABASE parliament_intern_logbook;
USE parliament_intern_logbook;

# Import schema
SOURCE database.sql;
```

### 3. Configure Database Connection

Edit `config/database.php` and update the connection details:

```php
private $host = 'localhost';           // Database host
private $db_name = 'parliament_intern_logbook';  // Database name
private $username = 'root';           // Database username
private $password = '';               // Database password
```

### 4. Set Up Web Server

#### Using XAMPP:

1. Copy the project folder to `C:\xampp\htdocs\parliament1`
2. Start Apache and MySQL services
3. Access: `http://localhost/parliament1`

#### Using Laragon:

1. Copy the project folder to `C:\laragon\www\parliament1`
2. Start Laragon services
3. Access: `http://parliament1.test`

#### Using Other Web Servers:

1. Point document root to the project folder
2. Ensure PHP and MySQL are running
3. Access via your configured domain/IP

### 5. Set File Permissions (Linux/Mac)

```bash
chmod 755 -R /path/to/project
chmod 644 /path/to/project/*.php
```

## 🔑 Default Login Credentials

After importing the database, you can log in with these default accounts:

### Administrator

- **Email**: admin@parliament.lk
- **Password**: password
- **Access**: Full system administration

### Supervisor

- **Email**: sarah.perera@parliament.lk
- **Password**: password
- **Access**: Manage assigned interns

### Intern

- **Email**: kavindu.silva@parliament.lk
- **Password**: password
- **Access**: Submit daily logs and view evaluations

## 📁 Project Structure

```
parliament1/
├── config/
│   └── database.php          # Database configuration
├── controllers/
│   ├── AdminController.php   # Admin functionality
│   ├── AuthController.php     # Authentication
│   ├── DashboardController.php # Dashboard logic
│   ├── InternController.php   # Intern features
│   └── SupervisorController.php # Supervisor features
├── includes/
│   └── functions.php         # Common functions
├── views/
│   ├── layouts/
│   │   ├── header.php        # Common header
│   │   └── footer.php        # Common footer
│   ├── auth/
│   │   └── login.php         # Login page
│   ├── dashboard/
│   │   ├── admin_dashboard.php
│   │   ├── supervisor_dashboard.php
│   │   └── intern_dashboard.php
│   ├── intern/
│   │   ├── logs.php          # Daily logs list
│   │   ├── add_log.php       # Add log form
│   │   ├── edit_log.php      # Edit log form
│   │   ├── view_log.php      # View single log
│   │   └── evaluations.php   # Performance evaluations
│   ├── supervisor/
│   │   ├── interns.php       # Assigned interns
│   │   ├── logs.php          # Review logs
│   │   ├── review_log.php    # Review single log
│   │   ├── add_evaluation.php # Add evaluation
│   │   └── evaluations.php   # All evaluations
│   └── admin/
│       ├── users.php         # User management
│       ├── add_user.php      # Add user form
│       └── announcements.php # System announcements
├── assets/                    # CSS, JS, images (if any)
├── database.sql              # Database schema and sample data
├── index.php                 # Main entry point
└── README.md                 # This file
```

## 🎯 Usage Guide

### For Interns:

1. **Login** with your credentials
2. **Add Daily Logs** documenting your activities
3. **View Evaluations** from your supervisor
4. **Generate Reports** for weekly summaries
5. **Track Progress** through the dashboard

### For Supervisors:

1. **Review Logs** submitted by your interns
2. **Provide Feedback** through comments
3. **Create Evaluations** with ratings and feedback
4. **Monitor Progress** of assigned interns
5. **Generate Reports** for performance analysis

### For Administrators:

1. **Manage Users** (create, edit, delete accounts)
2. **Assign Interns** to supervisors
3. **Create Announcements** for system-wide communication
4. **Export Data** for reporting purposes
5. **Monitor System** through analytics dashboard

## 🔧 Configuration Options

### Database Settings

Edit `config/database.php` to match your database configuration.

### Security Settings

- CSRF tokens are automatically generated
- Passwords are hashed using PHP's `password_hash()`
- Sessions are managed securely

### Email Configuration (Optional)

To enable email notifications, configure PHPMailer in the relevant controllers.

## 🐛 Troubleshooting

### Common Issues:

1. **Database Connection Error**

   - Check database credentials in `config/database.php`
   - Ensure MySQL service is running
   - Verify database exists

2. **Permission Denied**

   - Check file permissions (755 for directories, 644 for files)
   - Ensure web server has read access

3. **Page Not Found (404)**

   - Check URL rewriting (if using .htaccess)
   - Verify document root is set correctly

4. **Session Issues**
   - Check PHP session configuration
   - Ensure session directory is writable

### Debug Mode:

Add this to the top of `index.php` for debugging:

```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 🔒 Security Features

- **SQL Injection Protection**: All queries use PDO prepared statements
- **XSS Prevention**: All output is escaped using `htmlspecialchars()`
- **CSRF Protection**: Forms include CSRF tokens
- **Password Security**: Passwords are hashed using `password_hash()`
- **Session Security**: Secure session configuration
- **Input Validation**: All inputs are sanitized and validated

## 📊 Sample Data

The database includes sample data for testing:

- 3 sample users (admin, supervisor, intern)
- Sample daily logs with different statuses
- Sample evaluations with ratings
- Sample announcements

## 🚀 Deployment

### Production Deployment:

1. **Update Database Configuration**

   ```php
   // config/database.php
   private $host = 'your-production-host';
   private $username = 'your-production-username';
   private $password = 'your-production-password';
   ```

2. **Set Production Environment**

   ```php
   // Disable error reporting in production
   error_reporting(0);
   ini_set('display_errors', 0);
   ```

3. **Configure Web Server**

   - Set up SSL certificate
   - Configure proper file permissions
   - Set up database backups

4. **Security Checklist**
   - Change default passwords
   - Update database credentials
   - Configure firewall rules
   - Set up regular backups

## 📈 Performance Optimization

- **Database Indexing**: Indexes are included in the schema
- **Query Optimization**: Efficient queries with proper joins
- **Caching**: Consider implementing Redis/Memcached for sessions
- **CDN**: Use CDN for Bootstrap/jQuery libraries

## 🤝 Support

For technical support or questions:

- Check the troubleshooting section above
- Review the code comments for implementation details
- Ensure all requirements are met

## 📝 License

This project is developed for the Parliament of Sri Lanka. All rights reserved.

## 🔄 Updates

To update the system:

1. Backup your database
2. Replace files with new versions
3. Run any new database migrations
4. Test functionality

---

**Parliament Intern Logbook System** - Digital transformation for intern management at the Parliament of Sri Lanka.


