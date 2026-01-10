# Panduan Deploy Admin Subdomain

## 🎯 Tujuan
Redirect otomatis dari `sekolahzivanamontessori.sch.id/admin` ke `admin.sekolahzivanamontessori.sch.id/login`

## 📁 Struktur File Production

```
public_html/
├── app/
├── config/
├── public/                      # Document root (cPanel mengarah kesini)
│   ├── .htaccess               # SUDAH ADA - tambahkan redirect rule disini
│   └── index.php               # Entry point aplikasi (JANGAN GANGGU)
├── routes/
└── subdomain/
    ├── dev/                     # Development branch
    └── admin/                   # Admin subdomain
        ├── .htaccess           # Upload file ini
        └── index.php           # Upload file ini
```

## 🚀 Langkah Deployment

### 1. Konfigurasi cPanel/DNS
1. Login ke cPanel
2. Buka **Subdomain**
3. Buat subdomain baru:
   - Subdomain: `admin`
   - Domain: `sekolahzivanamontessori.sch.id`
   - Document Root: `public_html/subdomain/admin`
4. Klik **Create**

### 2. Upload File ke Admin Subdomain
Upload kedua file dari folder `admin/` ke `/public_html/subdomain/admin/`:

**File yang diupload:**
- `admin/.htaccess` → `/public_html/subdomain/admin/.htaccess`
- `admin/index.php` → `/public_html/subdomain/admin/index.php`

#### File: `.htaccess`
```apache
# Enable Rewrite Engine
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Set base directory
    RewriteBase /
    
    # Redirect root to /login
    RewriteCond %{REQUEST_URI} ^/$
    RewriteRule ^$ /login [R=301,L]
    
    # Redirect to index.php if not a real file or directory
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php?/$1 [L,QSA]
    
    # Remove trailing slashes
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)/$ /$1 [L,R=301]
</IfModule>

# Prevent directory browsing
Options -Indexes

# Set default character set
AddDefaultCharset UTF-8

# Enable error pages
ErrorDocument 404 /index.php
ErrorDocument 403 /index.php
ErrorDocument 500 /index.php
```

#### File: `index.php`
```php
<?php
/**
 * Admin Subdomain Entry Point
 * This file serves as the entry point for admin.sekolahzivanamontessori.sch.id
 * It loads the main application from the parent directory
 */

// Define that this is admin subdomain
define('IS_ADMIN_SUBDOMAIN', true);

// Redirect to /login if accessing root
if ($_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '') {
    header('Location: /login', true, 301);
    exit();
}

// Load the main application
// Path: dari /public_html/subdomain/admin/ ke /public_html/public/index.php
require_once __DIR__ . '/../../public/index.php';
```

### 3. Update .htaccess di Document Root
Tambahkan rule berikut ke `/public_html/public/.htaccess` (di bagian atas, sebelum rule lainnya):

**PENTING:** File `.htaccess` ini SUDAH ADA di production, jadi JANGAN ganti/hapus, cukup TAMBAHKAN rule ini di bagian atas.

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Redirect /admin ke admin subdomain
    RewriteCond %{HTTP_HOST} ^sekolahzivanamontessori\.sch\.id$ [OR]
    RewriteCond %{HTTP_HOST} ^www\.sekolahzivanamontessori\.sch\.id$
    RewriteRule ^admin(/.*)?$ https://admin.sekolahzivanamontessori.sch.id$1 [R=301,L]
</IfModule>
```

**CATATAN**: File `root_htaccess_config.txt` berisi template lengkap untuk ditambahkan ke root .htaccess

## ✅ Testing

### Test Case 1: Redirect dari Main Domain
- URL: `https://sekolahzivanamontessori.sch.id/admin`
- Expected: Redirect ke `https://admin.sekolahzivanamontessori.sch.id/login`

### Test Case 2: Akses Admin Subdomain Root
- URL: `https://admin.sekolahzivanamontessori.sch.id/`
- Expected: Redirect ke `https://admin.sekolahzivanamontessori.sch.id/login`

### Test Case 3: Akses Admin Login Langsung
- URL: `https://admin.sekolahzivanamontessori.sch.id/login`
- Expected: Tampil halaman login admin

### Test Case 4: Akses Admin Dashboard
- URL: `https://admin.sekolahzivanamontessori.sch.id/admin/dashboard`
- Expected: Tampil dashboard admin (setelah login)

### Test Case 5: Preserve Path dari Main Domain
- URL: `https://sekolahzivanamontessori.sch.id/admin/dashboard`
- Expected: Redirect ke `https://admin.sekolahzivanamontessori.sch.id/admin/dashboard`

## 🔧 Troubleshooting

### Problem: Infinite Redirect Loop
**Solution**: Pastikan RewriteBase di kedua .htaccess adalah `/`

### Problem: 404 Not Found
**Solution**: 
1. Cek file `index.php` ada di `/public_html/subdomain/admin/`
2. Cek path `require_once __DIR__ . '/../../public/index.php'` benar
3. Cek mod_rewrite enabled di server
4. Pastikan document root cPanel mengarah ke `/public_html/public/`
5. Pastikan `/public_html/public/index.php` bisa diakses

### Problem: Redirect ke HTTPS gagal
**Solution**: Tambahkan rule HTTPS di .htaccess:
```apache
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}/$1 [R=301,L]
```

### Problem: CSS/JS tidak load di subdomain
**Solution**: Pastikan asset path menggunakan absolute URL atau base tag di layout

## 📝 Checklist Deployment

- [ ] Subdomain `admin` sudah dibuat di cPanel
- [ ] Document root subdomain: `/public_html/subdomain/admin/`
- [ ] File `.htaccess` sudah diupload ke admin subdomain
- [ ] File `index.php` sudah diupload ke admin subdomain
- [ ] Root domain `.htaccess` sudah ditambahkan redirect rule
- [ ] Test Case 1-5 sudah berhasil
- [ ] Login admin berfungsi normal
- [ ] Session & cookies berfungsi normal

## 🎓 Penjelasan Teknis

### Cara Kerja Redirect

1. **User akses**: `sekolahzivanamontessori.sch.id/admin`
2. **Root .htaccess**: Detect pattern `^admin(/.*)?$`
3. **Redirect 301**: Ke `admin.sekolahzivanamontessori.sch.id`
4. **Admin .htaccess**: Detect root access (`^/$`)
5. **Redirect 301**: Ke `/login`
6. **Final URL**: `admin.sekolahzivanamontessori.sch.id/login`

### Path Resolution

```
admin.sekolahzivanamontessori.sch.id/login
  ↓
/public_html/subdomain/admin/index.php
  ↓
require_once __DIR__ . '/../../public/index.php'
  ↓
/public_html/public/index.php (Front Controller)
  ↓
Router matches /login → AuthController::login()
```

## 📞 Support

Jika ada masalah saat deployment, cek:
1. Error log server: cPanel → Errors
2. PHP error log: `/public_html/storage/logs/`
3. Apache error log: `/var/log/apache2/error.log`

---

**Last Updated**: January 10, 2026  
**Version**: 1.0  
**Author**: Zivana Montessori Dev Team
