# Subdomain Setup - Admin Panel

## Struktur di Hostinger

Setelah membuat subdomain `admin.sekolahzivanamontessori.sch.id` di Hostinger, struktur folder yang dibuat:

```
/home/u189792424/domains/sekolahzivanamontessori.sch.id/
├── public_html/              # Main domain (dev.sekolahzivanamontessori.sch.id)
│   ├── index.php
│   ├── .htaccess
│   ├── assets/
│   └── uploads/
│
└── public_html/admin/        # Admin subdomain (admin.sekolahzivanamontessori.sch.id)
    ├── index.php             # Entry point untuk admin subdomain
    └── .htaccess             # Routing rules untuk admin
```

## File-file yang Perlu Di-upload ke Hostinger

### 1. Upload ke `/home/u189792424/domains/sekolahzivanamontessori.sch.id/public_html/admin/`

Upload 2 file dari folder `admin/` di project Anda:
- `admin/index.php`
- `admin/.htaccess`

### 2. Update File Utama di `/home/u189792424/domains/sekolahzivanamontessori.sch.id/public_html/`

File-file yang sudah diupdate:
- `public/index.php` - Sudah ditambahkan middleware check
- `app/middleware/SubdomainMiddleware.php` - File baru
- `app/helpers/functions.php` - Update fungsi `url()` dan tambah `adminUrl()`
- `config/config.php` - Tambah constant `ADMIN_URL`

## Cara Deploy

### Via FTP/File Manager:

1. **Upload folder admin**
   ```
   Local: admin/
   Remote: /public_html/admin/
   ```

2. **Upload middleware baru**
   ```
   Local: app/middleware/SubdomainMiddleware.php
   Remote: /app/middleware/SubdomainMiddleware.php
   ```

3. **Update file yang sudah dimodifikasi**
   ```
   - public/index.php
   - app/helpers/functions.php
   - config/config.php
   ```

### Via Git (Recommended):

```bash
# Di local
git add .
git commit -m "Setup admin subdomain"
git push origin main

# Di server (via SSH atau terminal Hostinger)
cd /home/u189792424/domains/sekolahzivanamontessori.sch.id
git pull origin main
```

## Testing

### 1. Test Main Domain (dev.sekolahzivanamontessori.sch.id)
- ✅ `https://dev.sekolahzivanamontessori.sch.id/` → Landing page
- ✅ `https://dev.sekolahzivanamontessori.sch.id/about` → About page
- ❌ `https://dev.sekolahzivanamontessori.sch.id/admin/dashboard` → Redirect ke admin subdomain

### 2. Test Admin Subdomain (admin.sekolahzivanamontessori.sch.id)
- ✅ `https://admin.sekolahzivanamontessori.sch.id/` → Redirect ke `/admin/dashboard`
- ✅ `https://admin.sekolahzivanamontessori.sch.id/admin/dashboard` → Dashboard admin
- ✅ `https://admin.sekolahzivanamontessori.sch.id/admin/settings` → Settings page
- ✅ `https://admin.sekolahzivanamontessori.sch.id/login` → Login page
- ❌ `https://admin.sekolahzivanamontessori.sch.id/about` → Redirect ke `/admin/dashboard`

## Cara Kerja

### Main Domain (dev.sekolahzivanamontessori.sch.id):
1. Request masuk ke `/public_html/index.php`
2. Middleware detect bukan admin subdomain
3. Jika akses route admin (`/admin/*`), redirect ke admin subdomain
4. Jika route landing page, tampilkan normal

### Admin Subdomain (admin.sekolahzivanamontessori.sch.id):
1. Request masuk ke `/public_html/admin/index.php`
2. Set constant `IS_ADMIN_SUBDOMAIN = true`
3. Load aplikasi utama dari `../public/index.php`
4. Middleware detect admin subdomain
5. Root `/` redirect ke `/admin/dashboard`
6. Hanya route admin yang diperbolehkan
7. Route landing page redirect ke `/admin/dashboard`

## Troubleshooting

### Admin subdomain menampilkan 404:
- Pastikan file `admin/index.php` dan `admin/.htaccess` sudah terupload
- Check permissions: `chmod 644 admin/index.php`
- Check .htaccess: `chmod 644 admin/.htaccess`

### Redirect loop:
- Clear browser cache dan cookies
- Check constant `IS_ADMIN_SUBDOMAIN` sudah di-set di `admin/index.php`

### CSS/JS tidak load:
- Check path di console browser
- Pastikan path asset relatif ke root: `/assets/...` bukan `../assets/...`

### Database error:
- Check kredensial database di `config/config.php`
- Pastikan production config sudah benar

## Environment Variables (Optional)

Jika ingin lebih fleksibel, tambahkan di `.env`:

```env
APP_URL=https://dev.sekolahzivanamontessori.sch.id
ADMIN_URL=https://admin.sekolahzivanamontessori.sch.id
```

Tapi karena sudah hardcoded di `config/config.php` untuk production, ini optional.
