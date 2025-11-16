# Zivana Montessori School Website

A professional company profile website built with custom PHP MVC framework, designed for shared hosting compatibility.

## Features

- **Custom PHP MVC Framework** - Lightweight and efficient
- **Responsive Design** - Mobile-first approach with Tailwind CSS
- **Admin Panel** - Full CRUD operations for content management
- **SEO Optimized** - Clean URLs and meta tags
- **Analytics** - Built-in visitor tracking
- **Secure** - CSRF protection, XSS prevention, input sanitization
- **WhatsApp Integration** - Registration form with WhatsApp redirect

## Technology Stack

- **Backend:** PHP 7.4+ (Custom MVC Framework)
- **Frontend:** HTML5, CSS3, JavaScript, Tailwind CSS
- **Database:** MySQL 5.7+ / MariaDB 10.3+
- **Hosting:** Shared hosting compatible (Hostinger ready)

## Installation

### 1. Requirements

- PHP 7.4 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Apache web server with mod_rewrite enabled
- At least 5GB storage for uploads

### 2. Setup Database

Create a MySQL database and import the schema:

```bash
mysql -u your_username -p your_database_name < database/schema.sql
```

Or manually run the SQL file `database/schema.sql` in phpMyAdmin.

### 3. Configure Environment

Copy `.env.example` to `.env` and update the configuration:

```bash
cp .env.example .env
```

Edit `.env` file with your database credentials:

```env
DB_HOST=localhost
DB_NAME=your_database_name
DB_USER=your_database_user
DB_PASS=your_database_password
```

### 4. Set Permissions

Ensure the uploads directory is writable:

```bash
chmod -R 755 public/uploads
```

### 5. Web Server Configuration

#### Apache (.htaccess is included)

Make sure `mod_rewrite` is enabled. The `.htaccess` files are already configured.

#### For shared hosting (Hostinger, etc.)

1. Upload all files to your public_html directory
2. Update `.env` with your database credentials
3. Import `database/schema.sql` via phpMyAdmin
4. Access your website

### 6. Production Database Configuration

For production on Hostinger, update `.env` with:

```env
DB_HOST=localhost
DB_NAME=u189792424_zivana_dev
DB_USER=u189792424_zivana
DB_PASS=Zivana04112025$
```

## Default Admin Credentials

```
Username: admin
Password: admin123
```

**Important:** Change the default password immediately after first login!

## Directory Structure

```
zivanamontdev-php-repo/
├── app/
│   ├── controllers/      # Application controllers
│   ├── core/            # Core framework classes
│   ├── helpers/         # Helper functions
│   ├── middleware/      # Middleware classes
│   ├── models/          # Database models
│   └── views/           # View templates
│       ├── admin/       # Admin panel views
│       ├── home/        # Public pages
│       ├── layouts/     # Layout templates
│       └── errors/      # Error pages
├── config/              # Configuration files
├── database/            # Database schema and migrations
├── public/              # Public accessible files
│   ├── assets/         # CSS, JS, images
│   ├── uploads/        # Uploaded files
│   ├── .htaccess       # Apache configuration
│   └── index.php       # Front controller
├── routes/              # Route definitions
├── .env                 # Environment configuration
├── .env.example         # Environment template
├── .htaccess           # Root htaccess
└── README.md           # This file
```

## Usage

### Access Website

Public website: `http://your-domain.com`

### Access Admin Panel

Admin panel: `http://your-domain.com/admin`

### Admin Features

1. **Dashboard** - View analytics and statistics
2. **Programs Management** - Manage school programs and images
3. **Articles Management** - Create and manage news/articles
4. **Employees Management** - Manage staff information
5. **Schedules Management** - Manage daily school schedule
6. **Awards Management** - Showcase school achievements
7. **Social Media Management** - Manage social media links
8. **Settings** - Configure site settings

## Database Schema

The system includes 12 main tables:

- `users` - Admin users
- `programs` - School programs
- `images` - Program images
- `articles` - News/articles
- `employees` - Staff members
- `schedules` - Daily schedule
- `awards` - School awards
- `social_media` - Social media accounts
- `registrations` - Enrollment submissions
- `analytics` - Visitor tracking
- `settings` - Site configuration
- `form_fields` - Dynamic form fields

## Security Features

- CSRF token protection on all forms
- XSS prevention with input sanitization
- SQL injection prevention (PDO prepared statements)
- Password hashing (bcrypt)
- Session-based authentication
- File upload validation
- HTTPS enforcement (recommended)

## Performance Optimization

- Lazy loading images
- Efficient database queries
- Pagination for large datasets
- Browser caching headers
- Gzip compression support

## Customization

### Update School Information

1. Login to admin panel
2. Go to Management > Settings
3. Update school name, address, contact info, etc.

### Add WhatsApp Number for Registration

1. Go to Management > Settings
2. Find "whatsapp_number" setting
3. Enter phone number in international format (e.g., 628123456789)

### Customize Theme Colors

Edit the Tailwind CSS classes in view files or add custom CSS in `public/assets/css/`.

## Maintenance

### Backup Database

Regular database backups are recommended:

```bash
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql
```

### Clear Analytics Data

To clear old analytics data:

```sql
DELETE FROM analytics WHERE visited_at < DATE_SUB(NOW(), INTERVAL 90 DAY);
```

## Troubleshooting

### 404 Error on All Pages

- Check if mod_rewrite is enabled
- Verify .htaccess files exist and are readable
- Ensure AllowOverride is set to All in Apache config

### Database Connection Error

- Verify database credentials in .env
- Check if MySQL service is running
- Ensure database user has proper permissions

### Upload Errors

- Check directory permissions (755 for directories, 644 for files)
- Verify max_upload_size in php.ini
- Ensure uploads directory exists

### Cannot Login to Admin

- Verify database has default admin user
- Re-import database/schema.sql
- Check session configuration in PHP

## Support

For issues or questions, please refer to the PRD.md document or contact the development team.

## License

Proprietary - Zivana Montessori School

## Version

1.0.0 - Initial Release

---

**Built with ❤️ for Zivana Montessori School**
