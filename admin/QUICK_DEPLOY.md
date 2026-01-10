# 🚀 Quick Deploy - Admin Subdomain

## ⚠️ BERDASARKAN DIAGNOSTIC HASIL

Struktur server production:
```
/home/u189792424/domains/sekolahzivanamontessori.sch.id/
├── public_html/                      # Document root cPanel
│   ├── .htaccess                    # ✓ ADA - perlu edit
│   ├── public/
│   │   └── index.php                # ✓ Entry point aplikasi
│   └── subdomain/
│       └── admin/                   # ❌ Folder ini ada tapi kosong
│           ├── .htaccess            # ❌ BELUM ADA - perlu upload
│           └── index.php            # ❌ BELUM ADA - perlu upload
```

---

## 📝 LANGKAH 1: Upload File Admin

Upload 2 file ini ke server:

### 1.1. Upload `.htaccess`
**Dari:** `admin/.htaccess` (lokal)  
**Ke:** `/public_html/subdomain/admin/.htaccess` (server)

**Isi file:**
```apache
# Enable Rewrite Engine
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Set base directory
    RewriteBase /
    
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

### 1.2. Upload `index.php`
**Dari:** `admin/index.php` (lokal)  
**Ke:** `/public_html/subdomain/admin/index.php` (server)

**Isi file:**
```php
<?php
/**
 * Admin Subdomain Entry Point
 */

// Define that this is admin subdomain
define('IS_ADMIN_SUBDOMAIN', true);

// Load the main application
// Path: dari /subdomain/admin/ ke /public/index.php
require_once __DIR__ . '/../../public/index.php';
```

---

## 📝 LANGKAH 2: Edit .htaccess Root

Edit file yang sudah ada di server:  
**File:** `/public_html/.htaccess`

### 2.1. Buka file untuk edit
Via cPanel File Manager atau FTP

### 2.2. Tambahkan rule redirect SEBELUM rule yang ada

**SEBELUM (baris 1-7):**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    
    # Redirect to public folder
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ public/$1 [L]
```

**SESUDAH (baris 1-12):**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    
    # Redirect /admin ke admin subdomain
    RewriteCond %{HTTP_HOST} ^sekolahzivanamontessori\.sch\.id$ [OR]
    RewriteCond %{HTTP_HOST} ^www\.sekolahzivanamontessori\.sch\.id$
    RewriteRule ^admin(/.*)?$ https://admin.sekolahzivanamontessori.sch.id$1 [R=301,L]
    
    # Redirect to public folder
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ public/$1 [L]
```

**⚠️ PENTING:** Cukup tambahkan 4 baris redirect admin, JANGAN hapus/ubah rule lainnya!

---

## 📝 LANGKAH 3: Verifikasi cPanel Subdomain

1. Login cPanel
2. Buka **Subdomains**
3. Cari subdomain: **admin.sekolahzivanamontessori.sch.id**
4. Pastikan **Document Root** = `/home/u189792424/domains/sekolahzivanamontessori.sch.id/public_html/subdomain/admin`

Kalau belum ada, buat subdomain baru:
- Subdomain: `admin`
- Domain: `sekolahzivanamontessori.sch.id`
- Document Root: `public_html/subdomain/admin`

---

## ✅ LANGKAH 4: Testing

### Test 1: Redirect dari main domain
**URL:** https://sekolahzivanamontessori.sch.id/admin  
**Expected:** Redirect ke https://admin.sekolahzivanamontessori.sch.id/admin/login  
**Status:** ___

### Test 2: Admin subdomain root
**URL:** https://admin.sekolahzivanamontessori.sch.id/  
**Expected:** Redirect ke https://admin.sekolahzivanamontessori.sch.id/admin/login  
**Status:** ___

### Test 3: Admin login langsung
**URL:** https://admin.sekolahzivanamontessori.sch.id/admin/login  
**Expected:** Tampil halaman login  
**Status:** ___

### Test 4: Login functionality
**Action:** Isi email & password admin, klik Masuk  
**Expected:** Redirect ke dashboard  
**Status:** ___

---

## 🔧 Troubleshooting

### Error 500 - Internal Server Error
**Kemungkinan:**
1. File `index.php` atau `.htaccess` tidak terupload dengan benar
2. Path `require_once` salah
3. PHP error - cek error log

**Solusi:**
- Cek error log di cPanel → Errors
- Cek file permission (644 untuk .php, 644 untuk .htaccess)

### Error 404 - Not Found
**Kemungkinan:**
1. Subdomain belum dibuat di cPanel
2. Document root salah
3. File tidak terupload

**Solusi:**
- Verifikasi subdomain di cPanel
- Cek file ada di path yang benar

### Redirect Loop (too many redirects)
**Kemungkinan:**
1. RewriteBase salah
2. Kondisi redirect konflik

**Solusi:**
- Pastikan RewriteBase semua `/`
- Cek tidak ada duplicate rule redirect

### Halaman putih (blank page)
**Kemungkinan:**
1. PHP error tidak ditampilkan
2. Path file salah

**Solusi:**
- Cek error log
- Test dengan `error_reporting(E_ALL); ini_set('display_errors', 1);` di awal index.php

---

## 📞 Checklist Final

- [ ] File `.htaccess` sudah diupload ke `/subdomain/admin/`
- [ ] File `index.php` sudah diupload ke `/subdomain/admin/`
- [ ] File `.htaccess` root sudah ditambahkan redirect rule
- [ ] Subdomain admin sudah dibuat di cPanel
- [ ] Document root subdomain sudah benar
- [ ] Test 1: Main domain redirect ✓
- [ ] Test 2: Subdomain root redirect ✓
- [ ] Test 3: Login page tampil ✓
- [ ] Test 4: Login berfungsi ✓
- [ ] File diagnostic sudah dihapus

---

**Deploy Date:** _________  
**Deploy Status:** _________  
**Notes:** _________
