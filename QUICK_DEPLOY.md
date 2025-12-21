# 🚀 Quick Deployment Guide - Development

## URL Target
**https://dev.sekolahzivanamontessori.sch.id/**

## Step-by-Step di Hostinger

### 1️⃣ Pull Latest Code
```bash
cd /home/u189792424/domains/sekolahzivanamontessori.sch.id/public_html/dev
git pull origin development
```

### 2️⃣ Setup Environment
```bash
cp .env.production .env
```

Atau manual edit `.env`:
```env
DB_HOST=localhost
DB_NAME=u189792424_zivana_dev
DB_USER=u189792424_zivana
DB_PASS=Zivana04112025$
APP_URL=https://dev.sekolahzivanamontessori.sch.id
APP_ENV=production
APP_DEBUG=false
```

### 3️⃣ Set Permissions
```bash
chmod -R 755 public/uploads
chmod -R 755 storage
mkdir -p public/uploads storage/cache storage/logs
```

### 4️⃣ Test Website
Buka: https://dev.sekolahzivanamontessori.sch.id/

## ✅ Done!

Semua file termasuk vendor sudah ada di repo, jadi tidak perlu install dependencies lagi.

## 🔧 Jika Ada Masalah

### Cek Error Log
```bash
tail -f storage/logs/error.log
```

### Test Database Connection
```bash
php scripts/check-db.php
```

### Reset Permissions
```bash
find public/uploads -type d -exec chmod 755 {} \;
find public/uploads -type f -exec chmod 644 {} \;
find storage -type d -exec chmod 755 {} \;
find storage -type f -exec chmod 644 {} \;
```

## 📚 Dokumentasi Lengkap
Lihat [DEPLOYMENT_DEV.md](DEPLOYMENT_DEV.md) untuk detail lengkap.
