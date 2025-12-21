# Deployment Guide - Development Environment

## URL Production
- **URL**: https://dev.sekolahzivanamontessori.sch.id/
- **Path di Hosting**: `/home/u189792424/domains/sekolahzivanamontessori.sch.id/public_html/dev`

## Database Configuration
- **Host**: localhost
- **Port**: 3306
- **Database**: u189792424_zivana_dev
- **Username**: u189792424_zivana
- **Password**: Zivana04112025$

## ✨ Auto-Configuration Feature

Aplikasi sekarang **automatically detects production environment** berdasarkan domain!

Ketika aplikasi diakses melalui domain `sekolahzivanamontessori.sch.id`, sistem akan otomatis:
- ✅ Menggunakan database production credentials
- ✅ Set environment ke production
- ✅ Disable debug mode
- ✅ Load production settings

**Tidak perlu setup .env manual!** Tapi tetap bisa menggunakan `.env` file jika ingin override settings.

## Deployment Steps

### 1. Push ke Git Repository
```bash
git add .
git commit -m "Update configuration for development deployment"
git push origin development
```

### 2. Setup di Hostinger (via cPanel)

#### A. Pull Repository
**Via Git Version Control (Recommended):**
1. Login ke cPanel Hostinger
2. Cari menu **Git Version Control**
3. Locate repository di `public_html/dev`
4. Klik **Manage**
5. Klik **Pull or Deploy**
6. Klik **Update from Remote**
7. Done! ✅

**Via File Manager (Alternative):**
1. Login ke cPanel
2. Buka **File Manager**
3. Navigate ke `/home/u189792424/domains/sekolahzivanamontessori.sch.id/public_html/dev`
4. Upload files yang diupdate dari local
5. Replace files yang sudah ada

#### B. Setup Environment Configuration (Optional)

File `.env` bersifat **OPSIONAL** karena aplikasi sudah auto-detect production environment.

Namun, jika Anda ingin menggunakan file `.env`:

**Via File Manager:**
1. Navigate ke folder `dev`
2. Klik kanan file `.env.production`
3. Pilih **Copy**
4. Rename hasil copy menjadi `.env`

**Isi file `.env` (jika dibuat manual):**
   ```env
   DB_HOST=localhost
   DB_PORT=3306
   DB_NAME=u189792424_zivana_dev
   DB_USER=u189792424_zivana
   DB_PASS=Zivana04112025$
   
   APP_NAME="Zivana Montessori School"
   APP_URL=https://dev.sekolahzivanamontessori.sch.id
   APP_ENV=production
   APP_DEBUG=false
   ```

#### C. Setup Permissions (via File Manager)

**Set folder permissions ke 755:**
1. Klik kanan folder `public/uploads` → **Change Permissions** → **755**
2. Klik kanan folder `storage` → **Change Permissions** → **755**

**Create folders jika belum ada:**
- `public/uploads`
- `storage/cache`
- `storage/logs`

#### D. Database Migration (if needed)
Jika perlu run migration via cPanel Terminal atau PHP Cron:
```bash
php scripts/migrate.php
```

### 3. Verifikasi Deployment
1. Buka browser: https://dev.sekolahzivanamontessori.sch.id/
2. Website should load correctly with auto-configured production settings
3. Test login admin
4. Test form registrasi
5. Check error logs via File Manager jika ada masalah: `storage/logs/error.log`

## Troubleshooting

### Error: Database Connection Failed
**Solusi via cPanel:** 
1. Pastikan database `u189792424_zivana_dev` sudah dibuat di **MySQL Databases**
2. Pastikan user `u189792424_zivana` punya akses penuh ke database
3. Import database structure via **phpMyAdmin**
4. Aplikasi akan auto-detect credentials jika diakses via domain production

### Error: Permission Denied
**Solusi via File Manager:**
1. Klik kanan folder `public/uploads` → Change Permissions → **755**
2. Klik kanan folder `storage` → Change Permissions → **755**

### Website Blank/White Screen
**Solusi:**
1. Check apakah ada file `.env` dengan config yang salah - **hapus saja** biar pakai auto-detection
2. Check error log via File Manager: `storage/logs/error.log`
3. Enable debug sementara: edit `config/config.php`, set `APP_DEBUG` ke `true`

### Still Using Wrong Database (root@localhost)
**Solusi:**
1. **Hapus file `.env`** jika ada - biar aplikasi pakai auto-detection
2. Atau pastikan file `.env` punya config yang benar (copy dari `.env.production`)
3. Clear browser cache
4. Refresh website

## Important Notes

1. **Auto-Configuration**: Aplikasi otomatis detect production environment berdasarkan domain. File `.env` opsional.

2. **Vendor Folder**: Folder `vendor/` sudah included di repository untuk memudahkan deployment.

3. **Environment File**: File `.env` tidak di-push ke git untuk keamanan. Gunakan `.env.production` sebagai template jika ingin membuat `.env` manual.

3. **Environment File**: File `.env` tidak di-push ke git untuk keamanan. Gunakan `.env.production` sebagai template jika ingin membuat `.env` manual.

4. **Public Path**: Pastikan web server point ke folder `/public` sebagai document root, atau setup `.htaccess` dengan benar.

5. **File Uploads**: Folder `public/uploads/` di-ignore di git. Pastikan folder ini ada dan writable di server.

6. **Database**: Database development (`u189792424_zivana_dev`) terpisah dari production database.

## Contact
Jika ada masalah, cek dokumentasi atau hubungi developer.
Jika ada masalah, cek dokumentasi atau hubungi developer.
