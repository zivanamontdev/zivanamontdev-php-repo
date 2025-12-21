# 🚀 Quick Deployment Guide - Development

## URL Target
**https://dev.sekolahzivanamontessori.sch.id/**

## Auto-Configuration ✨

Aplikasi sekarang **otomatis detect production environment** berdasarkan domain!

Ketika diakses di `sekolahzivanamontessori.sch.id`, aplikasi akan otomatis menggunakan:
- Database: `u189792424_zivana_dev`
- User: `u189792424_zivana`
- Password: `Zivana04112025$`

## Deployment Steps

### 1️⃣ Pull Latest Code via cPanel Git
1. Login ke cPanel Hostinger
2. Buka **Git Version Control**
3. Cari repository: `public_html/dev`
4. Klik **Manage** → **Pull or Deploy** → **Update from Remote**

Atau via File Manager:
1. Login ke cPanel
2. Buka File Manager
3. Navigate ke: `/home/u189792424/domains/sekolahzivanamontessori.sch.id/public_html/dev`
4. Upload/replace files yang berubah

### 2️⃣ Setup Environment (Optional)

File `.env` **OPSIONAL** karena aplikasi sudah auto-detect production.

Tapi jika ingin setup manual via File Manager:
1. Copy file `.env.production` 
2. Rename menjadi `.env`

### 3️⃣ Set Permissions via File Manager

Klik kanan folder → **Change Permissions** → Set ke **755**:
- `public/uploads`
- `storage/cache`  
- `storage/logs`

### 4️⃣ Test Website
Buka: **https://dev.sekolahzivanamontessori.sch.id/**

## ✅ Done!

Aplikasi akan otomatis:
- ✅ Detect production environment
- ✅ Pakai database production
- ✅ Disable debug mode
- ✅ Load semua dependencies (vendor sudah included)

## ⚠️ TROUBLESHOOTING

### Error: "Database connection failed"

**Cek via cPanel:**
1. **MySQL Databases** → Pastikan database `u189792424_zivana_dev` ada
2. **MySQL Database Wizard** → Pastikan user `u189792424_zivana` punya akses ke database
3. Import SQL jika database kosong

### Error: "Permission denied" untuk upload

**Fix via File Manager:**
1. Klik kanan `public/uploads` → Change Permissions → 755
2. Klik kanan `storage` → Change Permissions → 755

### Website masih error

1. Check file `.env` - pastikan ada dan isinya benar
2. Atau hapus file `.env` - biar pakai auto-detection
3. Clear cache: Hapus semua file di `storage/cache/`

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
