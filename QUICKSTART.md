# Quick Start Guide - Zivana Montessori School Website

## 🚀 Local Development Setup

### Step 1: Database Setup

1. Create a new MySQL database named `zivana_montessori`:
```sql
CREATE DATABASE zivana_montessori CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Import the database schema:
```bash
mysql -u root -p zivana_montessori < database/schema.sql
```

Or use phpMyAdmin to import `database/schema.sql`.

### Step 2: Configure Environment

The `.env` file is already configured for local development with:
- Database: `zivana_montessori`
- User: `root`
- Password: (empty)

If your setup is different, edit `.env` file.

### Step 3: Start Your Web Server

**Option A: Using PHP Built-in Server**
```bash
cd public
php -S localhost:8000
```
Access: `http://localhost:8000`

**Option B: Using XAMPP/WAMP/MAMP**
1. Copy the project to your htdocs/www folder
2. Access: `http://localhost/zivanamontdev-php-repo/public`

### Step 4: Login to Admin Panel

URL: `http://localhost:8000/admin` (or your configured URL)

**Default Credentials:**
- Username: `admin`
- Password: `admin123`

**Important:** Change this password immediately!

---

## 🌐 Production Deployment (Hostinger)

### Step 1: Upload Files

1. Connect to your hosting via FTP or File Manager
2. Upload ALL project files to your `public_html` directory
3. Your structure should look like:
```
public_html/
├── app/
├── config/
├── database/
├── public/
├── routes/
├── .env
├── .htaccess
└── README.md
```

### Step 2: Configure Database

1. In Hostinger cPanel, go to MySQL Databases
2. Create database or use existing one
3. Note your database credentials

4. Edit `.env` file with production credentials:
```env
DB_HOST=localhost
DB_NAME=u189792424_zivana_dev
DB_USER=u189792424_zivana
DB_PASS=Zivana04112025$

APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### Step 3: Import Database

1. Go to phpMyAdmin in cPanel
2. Select your database
3. Click "Import"
4. Upload `database/schema.sql`
5. Click "Go"

### Step 4: Set Permissions

Using File Manager or FTP:
1. Set `public/uploads/` to 755 permissions
2. Ensure `.env` is readable (644)

### Step 5: Update .htaccess (if needed)

If your site is not in root directory, update `.htaccess` RewriteBase.

### Step 6: Test Your Site

1. Visit your domain
2. Login to admin: `yourdomain.com/admin`
3. Change admin password immediately

---

## 📝 First Steps After Installation

### 1. Change Admin Password

**Option A: Via Database**
```sql
UPDATE users 
SET password = '$2y$10$YOUR_HASHED_PASSWORD' 
WHERE username = 'admin';
```

To generate hash, use this PHP:
```php
echo password_hash('your_new_password', PASSWORD_DEFAULT);
```

**Option B: Via Admin Panel** (Create this feature if needed)

### 2. Configure Basic Settings

Go to **Admin Panel > Management > Settings**

Update:
- ✅ School Name
- ✅ School Address
- ✅ School Phone
- ✅ School Email
- ✅ School Description
- ✅ WhatsApp Number (format: 628123456789)
- ✅ Meta Title & Description (for SEO)

### 3. Add Social Media Links

Go to **Admin Panel > Management > Social Media**

Add your accounts:
- Facebook
- Instagram
- YouTube
- Twitter/X
- TikTok

### 4. Create Content

#### Add Programs
1. Go to **Programs**
2. Click "Add Program"
3. Fill in program details
4. Upload program images

#### Add Staff Members
1. Go to **Employees**
2. Click "Add Employee"
3. Select appropriate level:
   - School Leadership
   - Educational Support Staff
   - Teaching Staff
   - Operational Staff

#### Create Articles
1. Go to **Articles**
2. Click "Create Article"
3. Write content (HTML supported)
4. Upload featured image
5. Set as Published

#### Setup Daily Schedule
1. Go to **Management > Schedules**
2. Add time slots and activities

#### Add School Awards
1. Go to **Management > Awards**
2. Upload award images
3. Add descriptions

---

## 🎨 Customization

### Change Colors

Edit the Tailwind CSS classes in view files:
- Purple theme: `bg-purple-600`, `text-purple-600`
- Change to blue: `bg-blue-600`, `text-blue-600`
- Or any other Tailwind color

### Add Custom CSS

Create: `public/assets/css/custom.css`

Include in layout:
```php
<link rel="stylesheet" href="<?= asset('css/custom.css') ?>">
```

### Modify Homepage Hero

Edit: `app/views/home/index.php`
- Line 7-15: Hero section content

---

## 🔒 Security Checklist

- [ ] Change default admin password
- [ ] Set `APP_DEBUG=false` in production
- [ ] Set proper file permissions (755 for directories, 644 for files)
- [ ] Enable HTTPS/SSL certificate
- [ ] Keep `.env` file secure (never commit to git)
- [ ] Regular database backups
- [ ] Update PHP to latest stable version
- [ ] Monitor uploaded files directory

---

## 🐛 Troubleshooting

### "500 Internal Server Error"
- Check `.htaccess` files exist
- Verify mod_rewrite is enabled
- Check PHP error log
- Set `APP_DEBUG=true` to see errors

### "Database Connection Error"
- Verify credentials in `.env`
- Check database exists
- Confirm MySQL service running
- Test database connection separately

### "404 on All Pages"
- Ensure `.htaccess` in root and `public/` directories
- Check mod_rewrite module enabled
- Verify AllowOverride is set to All

### "Cannot Upload Images"
- Check `public/uploads/` permissions (755)
- Verify `upload_max_filesize` in php.ini
- Check `post_max_size` in php.ini
- Ensure directory ownership is correct

### "Admin Panel Login Not Working"
- Re-import database schema
- Clear browser cookies/cache
- Check session.save_path in php.ini
- Verify session_start() is working

---

## 📊 Analytics & Monitoring

The system automatically tracks:
- Page visits
- Device types
- Popular pages
- Registration submissions

View in: **Admin Panel > Dashboard**

To clear old analytics (90+ days):
```sql
DELETE FROM analytics 
WHERE visited_at < DATE_SUB(NOW(), INTERVAL 90 DAY);
```

---

## 🔄 Regular Maintenance

### Weekly
- Check registration submissions
- Review analytics
- Moderate comments (if implemented)

### Monthly
- Backup database
- Review and update content
- Check for broken images
- Monitor site performance

### Quarterly
- Update PHP version
- Security audit
- Performance optimization
- Clean old analytics data

---

## 📞 Support Contacts

For technical issues:
- Check README.md for detailed documentation
- Review PRD.md for feature specifications
- Check error logs in hosting panel

---

## 🎉 You're All Set!

Your Zivana Montessori School website is now ready to use!

**Next Steps:**
1. ✅ Complete all settings configuration
2. ✅ Add your school's programs and activities
3. ✅ Upload staff photos and bios
4. ✅ Create engaging articles
5. ✅ Test registration form with WhatsApp
6. ✅ Share your website with parents!

**Remember:** Keep your content fresh and engaging to attract more enrollments!

---

Good luck with your website! 🚀
