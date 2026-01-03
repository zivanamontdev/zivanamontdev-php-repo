# Subdomain Setup - Admin Panel

## ⚠️ PENTING: Buat Subdomain Dulu di Hostinger

Sebelum deploy code, Anda **HARUS** membuat subdomain `admin` dulu di Hostinger:

### Langkah 1: Buat Subdomain di Hostinger

1. **Login ke hPanel Hostinger**
   - Buka https://hpanel.hostinger.com
   - Login dengan akun Anda

2. **Buat Subdomain**
   - Pergi ke menu **Domains** → **Subdomains**
   - Klik **Create Subdomain**
   - Isi form:
     - **Subdomain**: `admin`
     - **Domain**: `sekolahzivanamontessori.sch.id`
     - **Document Root**: Biarkan Hostinger auto-create folder (biasanya `/public_html/admin`)
   - Klik **Create**

3. **Tunggu DNS Propagation** (5-30 menit)
   - DNS perlu waktu untuk propagate
   - Test dengan ping: `ping admin.sekolahzivanamontessori.sch.id`
   - Atau buka di browser, seharusnya tidak muncul error `DNS_PROBE_FINISHED_NXDOMAIN` lagi

## Struktur Folder di Hostinger (Development)

```
/home/u189792424/domains/sekolahzivanamontessori.sch.id/
├── public_html/
│   └── dev/                  # Main development (dev.sekolahzivanamontessori.sch.id)
│       ├── index.php
│       ├── .htaccess
│       ├── assets/
│       ├── uploads/
│       └── ... (semua file project)
│
└── public_html/admin/        # Admin subdomain (admin.sekolahzivanamontessori.sch.id)
    ├── index.php             # Entry point untuk admin subdomain
    └── .htaccess             # Routing rules untuk admin
```

**Note**: Hostinger akan otomatis buatkan folder `admin/` saat Anda create subdomain.

## File-file yang Perlu Di-upload ke Hostinger

### Setelah Subdomain Berhasil Dibuat:

### 1. Check Path yang Hostinger Buatkan

Login ke File Manager dan check path subdomain admin:
- Biasanya: `/home/u189792424/domains/sekolahzivanamontessori.sch.id/public_html/admin`
- Atau bisa jadi: `/home/u189792424/public_html/admin`

### 2. Upload File Admin ke Path Subdomain

Upload 2 file dari folder `admin/` di project Anda ke path yang Hostinger buatkan:
- `admin/index.php` → Upload ke folder admin yang dibuat Hostinger
- `admin/.htaccess` → Upload ke folder admin yang dibuat Hostinger

**PENTING**: File `admin/index.php` perlu disesuaikan path-nya tergantung lokasi project utama.

#### Jika project utama di `/public_html/dev/`:

Edit file `admin/index.php` yang akan diupload:

```php
<?php
/**
 * Admin Subdomain Entry Point
 */

// Define that this is admin subdomain
define('IS_ADMIN_SUBDOMAIN', true);

// Load the main application - SESUAIKAN PATH INI
// Dari /public_html/admin/ ke /public_html/dev/public/index.php
require_once __DIR__ . '/../dev/public/index.php';
```

#### Jika project utama di root `/home/u189792424/domains/.../`:

```php
<?php
define('IS_ADMIN_SUBDOMAIN', true);

// Dari /public_html/admin/ ke /public_html/dev/public/
require_once __DIR__ . '/../dev/public/index.php';
```

### 3. Update File Project Utama

File-file yang sudah diupdate di `/public_html/dev/`:
- `public/index.php` - Sudah ditambahkan middleware check
- `app/middleware/SubdomainMiddleware.php` - File baru
- `app/helpers/functions.php` - Update fungsi `url()` dan tambah `adminUrl()`
- `config/config.php` - Tambah constant `ADMIN_URL`

## Cara Deploy

### Via Git (Recommended):

```bash
# Di local
git add .
git commit -m "Setup admin subdomain"
git push origin main

# Di server (via SSH atau terminal Hostinger)
cd /home/u189792424/domains/sekolahzivanamontessori.sch.id/public_html/dev
git pull origin main
```

### Via FTP/File Manager:

1. **Upload ke folder DEV** (`/public_html/dev/`):
   ```
   - public/index.php (updated)
   - app/middleware/SubdomainMiddleware.php (new)
   - app/helpers/functions.php (updated)
   - config/config.php (updated)
   ```

2. **Upload ke folder ADMIN** (`/public_html/admin/`):
   ```
   - admin/index.php → Sesuaikan path ke ../dev/public/index.php
   - admin/.htaccess
   ```

3. **Upload .env** (jika belum ada):
   ```
   - .env.production → Rename ke .env di server
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

### ❌ Error: `DNS_PROBE_FINISHED_NXDOMAIN`
**Masalah**: Subdomain belum dibuat atau DNS belum propagate
**Solusi**: 
1. Buat subdomain `admin` di Hostinger hPanel
2. Tunggu 5-30 menit untuk DNS propagation
3. Clear DNS cache di komputer: `ipconfig /flushdns` (Windows) atau `sudo dscacheutil -flushcache` (Mac)
4. Test dengan `ping admin.sekolahzivanamontessori.sch.id`

### ❌ Admin subdomain menampilkan 404 atau error page:
**Solusi**:
- Pastikan file `admin/index.php` dan `admin/.htaccess` sudah terupload ke folder admin
- Check path di `admin/index.php` menuju `../dev/public/index.php`
- Check permissions: 
  ```bash
  chmod 644 admin/index.php
  chmod 644 admin/.htaccess
  ```

### ❌ Redirect loop atau page kosong:
**Solusi**:
- Clear browser cache dan cookies
- Check constant `IS_ADMIN_SUBDOMAIN` sudah di-set di `admin/index.php`
- Check file `.env` di server sudah correct dengan `ADMIN_URL`

### ❌ CSS/JS tidak load:
**Solusi**:
- Check path di console browser (F12)
- Pastikan path asset absolute: `/assets/...` bukan `../assets/...`
- Check file exists di `/public_html/dev/public/assets/`

### ❌ Database connection error:
**Solusi**:
- Check kredensial database di `config/config.php` atau `.env`
- Pastikan production config sudah benar
- Test koneksi database via phpMyAdmin

## Check DNS Propagation

Gunakan tools online untuk check apakah DNS sudah propagate:
- https://dnschecker.org
- Masukkan: `admin.sekolahzivanamontessori.sch.id`
- Check dari berbagai lokasi

Atau via terminal:
```bash
# Windows
nslookup admin.sekolahzivanamontessori.sch.id

# Mac/Linux
dig admin.sekolahzivanamontessori.sch.id
```

## Environment Variables (Optional)

Jika ingin lebih fleksibel, tambahkan di `.env`:

```env
APP_URL=https://dev.sekolahzivanamontessori.sch.id
ADMIN_URL=https://admin.sekolahzivanamontessori.sch.id
```

Tapi karena sudah hardcoded di `config/config.php` untuk production, ini optional.
