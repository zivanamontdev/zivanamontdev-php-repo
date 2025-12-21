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

## Deployment Steps

### 1. Push ke Git Repository
```bash
git add .
git commit -m "Update configuration for development deployment"
git push origin development
```

### 2. Setup di Hostinger

#### A. Pull Repository di Hostinger
1. Login ke SSH atau File Manager Hostinger
2. Navigate ke folder deployment:
   ```bash
   cd /home/u189792424/domains/sekolahzivanamontessori.sch.id/public_html/dev
   ```
3. Pull latest changes:
   ```bash
   git pull origin development
   ```
   
**Catatan**: Folder `vendor/` sekarang sudah included di repository, jadi tidak perlu install dependencies lagi.

#### B. Setup Environment Configuration
1. Copy `.env.production` menjadi `.env` di server:
   ```bash
   cp .env.production .env
   ```
   
   Atau manual edit `.env` dengan konfigurasi:
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

#### C. Setup Permissions
```bash
# Set permissions untuk folder uploads dan cache
chmod -R 755 public/uploads
chmod -R 755 storage/cache
chmod -R 755 storage/logs

# Pastikan folder ini ada
mkdir -p public/uploads
mkdir -p storage/cache
mkdir -p storage/logs
```

#### D. Database Migration
Jika perlu run migration:
```bash
php scripts/migrate.php
```

### 3. Verifikasi Deployment
1. Buka browser: https://dev.sekolahzivanamontessori.sch.id/
2. Cek apakah website load dengan benar
3. Test login admin
4. Test form registrasi
5. Cek error logs jika ada masalah:
   ```bash
   tail -f storage/logs/error.log
   ```

## Troubleshooting

### Error: Database Connection Failed
**Solusi**: 
1. Pastikan kredensial database benar di `.env`
2. Cek apakah database sudah dibuat
3. Test koneksi database manual:
   ```bash
   php scripts/check-db.php
   ```

### Error: Permission Denied
**Solusi**:
```bash
chmod -R 755 public/uploads
chmod -R 755 storage
```

### Website Blank/White Screen
**Solusi**:
1. Enable error reporting sementara di `.env`:
   ```env
   APP_DEBUG=true
   ```
2. Cek error log:
   ```bash
   tail -f storage/logs/error.log
   ```
3. Cek PHP error log di cPanel

## Important Notes

1. **Vendor Folder**: Folder `vendor/` sekarang sudah included di repository untuk memudahkan deployment.

2. **Environment File**: File `.env` tidak di-push ke git untuk keamanan. Gunakan `.env.production` sebagai template.

3. **Public Path**: Pastikan web server point ke folder `/public` sebagai document root, atau setup `.htaccess` dengan benar.

4. **File Uploads**: Folder `public/uploads/` di-ignore di git. Pastikan folder ini ada dan writable di server.

5. **Database**: Database development (`u189792424_zivana_dev`) terpisah dari production database.

## Automatic Deployment (Optional)

Untuk setup auto-deploy via Git hooks, hubungi support Hostinger atau setup webhook dari GitHub/GitLab ke server.

## Contact
Jika ada masalah, cek dokumentasi atau hubungi developer.
