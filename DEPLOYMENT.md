# 🚀 Deployment Guide - Zivana Montessori School Website

## 📋 Pre-Deployment Checklist

- [ ] All files committed to repository
- [ ] Database credentials confirmed
- [ ] Domain DNS configured
- [ ] Hosting access ready (FTP/File Manager & phpMyAdmin)

## 📤 Step 1: Upload Files to Hosting

### Via FTP Client (FileZilla, WinSCP, etc.)
1. Connect to your hosting FTP:
   - Host: `ftp.sekolahzivanamontessori.sch.id` (or provided by Hostinger)
   - Username: Your hosting username
   - Password: Your hosting password

2. Upload ALL project files to `/public_html/`:
   ```
   /public_html/
   ├── app/
   ├── config/
   ├── database/
   ├── public/
   ├── routes/
   ├── .env (IMPORTANT: Copy from .env.production and rename)
   ├── .htaccess
   └── .gitignore
   ```

### Via Hostinger File Manager
1. Login to Hostinger control panel
2. Go to "File Manager"
3. Navigate to `/public_html/`
4. Upload all files/folders
5. Extract if uploaded as ZIP

## 🗄️ Step 2: Setup Database

### Import Database Schema
1. Login to **phpMyAdmin** via Hostinger control panel
2. Select database: `u189792424_zivana_dev`
3. Click **"Import"** tab
4. Choose file: `database/schema.sql`
5. Click **"Go"** button
6. Wait for success message

### Verify Tables Created
Check that these 12 tables exist:
- ✅ users
- ✅ programs
- ✅ images
- ✅ articles
- ✅ employees
- ✅ schedules
- ✅ awards
- ✅ social_media
- ✅ registrations
- ✅ analytics
- ✅ settings
- ✅ form_fields

## ⚙️ Step 3: Configure Environment

### Create Production .env File
1. Copy `.env.production` to `.env` via File Manager
2. Or create new `.env` file with production settings:

```ini
# Production Database Configuration
DB_HOST=localhost
DB_PORT=3306
DB_NAME=u189792424_zivana_dev
DB_USER=u189792424_zivana
DB_PASS=Zivana04112025$

# Production Application
APP_NAME="Zivana Montessori School"
APP_URL=https://sekolahzivanamontessori.sch.id
APP_ENV=production
APP_DEBUG=false

# Session
SESSION_LIFETIME=7200

# Upload Settings
MAX_UPLOAD_SIZE=5242880
ALLOWED_IMAGE_TYPES=jpg,jpeg,png,gif,webp

# Security
CSRF_TOKEN_NAME=csrf_token
```

## 🔐 Step 4: Set File Permissions

Via File Manager or FTP, set these permissions:

```
public/uploads/          → 755 or 775 (writable)
public/uploads/programs/ → 755 or 775 (writable)
public/uploads/articles/ → 755 or 775 (writable)
public/uploads/employees/→ 755 or 775 (writable)
public/uploads/awards/   → 755 or 775 (writable)
.env                     → 644 (readable only by you)
```

## 🔧 Step 5: Configure Web Root (Document Root)

### Option A: If hosting allows custom document root
1. Go to Hostinger control panel
2. Find "Website Settings" or "Domain Settings"
3. Set document root to: `public_html/public`
4. Save changes

### Option B: If document root cannot be changed
Your `.htaccess` in root will handle routing automatically.

## ✅ Step 6: Test Website

### Test Public Pages
Visit these URLs and verify they work:
- `https://sekolahzivanamontessori.sch.id/` (Homepage)
- `https://sekolahzivanamontessori.sch.id/activities`
- `https://sekolahzivanamontessori.sch.id/profile`
- `https://sekolahzivanamontessori.sch.id/articles`
- `https://sekolahzivanamontessori.sch.id/registration`

### Test Admin Panel
1. Go to: `https://sekolahzivanamontessori.sch.id/admin`
2. Login with default credentials:
   - Username: `admin`
   - Password: `admin123`
3. **IMMEDIATELY change password** after first login!

### Test Features
- [ ] Navigation works on all pages
- [ ] Images load correctly
- [ ] Registration form works
- [ ] WhatsApp redirect works
- [ ] Admin login works
- [ ] Admin can create/edit/delete content
- [ ] Image upload works
- [ ] No PHP errors visible (check error_log if needed)

## 🔒 Step 7: Post-Deployment Security

### Immediate Actions
1. **Change admin password** via admin panel
2. Verify `.env` file is NOT accessible via browser:
   - Try: `https://sekolahzivanamontessori.sch.id/.env`
   - Should show 403 Forbidden or 404 Not Found
3. Check file permissions are correct
4. Enable HTTPS/SSL certificate (usually auto in Hostinger)

### Optional Security Enhancements
```apache
# Add to .htaccess for extra security
<Files ".env">
    Order allow,deny
    Deny from all
</Files>

<Files "config.php">
    Order allow,deny
    Deny from all
</Files>
```

## 🐛 Troubleshooting

### White Screen / Blank Page
1. Check PHP version (must be 7.4+ or 8.0+)
2. Check `.env` file exists and has correct credentials
3. Enable errors temporarily in `config/config.php`:
   ```php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```
4. Check error_log file in hosting

### 404 Error on All Pages
1. Verify `.htaccess` file exists in root
2. Check Apache `mod_rewrite` is enabled (usually is on Hostinger)
3. Verify document root points to correct folder

### Database Connection Error
1. Verify database name, user, and password in `.env`
2. Check database exists in phpMyAdmin
3. Verify user has permissions on database

### Images Not Uploading
1. Check `public/uploads/` folder exists
2. Verify folder permissions (755 or 775)
3. Check `MAX_UPLOAD_SIZE` in `.env`
4. Verify PHP `upload_max_filesize` in hosting settings

### CSS Not Loading
1. Check `APP_URL` in `.env` matches your domain exactly
2. Clear browser cache
3. Check `.htaccess` rules

## 📞 Support Contacts

- **Hosting Support:** Hostinger live chat
- **Database:** phpMyAdmin in control panel
- **DNS/Domain:** Your domain registrar

## 🎉 Success!

Your website is now live at:
**https://sekolahzivanamontessori.sch.id**

### Next Steps
1. Add actual content via admin panel
2. Upload school programs
3. Add articles/news
4. Add employee profiles
5. Configure WhatsApp number in settings
6. Test registration form thoroughly
7. Monitor analytics in admin dashboard

---

**Deployment completed successfully!** 🚀

For any issues, check the error logs in your hosting control panel or contact Hostinger support.
