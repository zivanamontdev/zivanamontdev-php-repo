# Fix: Gambar Tidak Load di Production (Subdomain /admin)

## Masalah
Di production, gambar-gambar di halaman admin (seperti logo dan gambar di halaman login) tidak bisa load ketika diakses melalui subdomain `admin.sekolahzivanamontessori.sch.id`.

## Penyebab
1. **Path Absolut yang Salah**: Gambar menggunakan path absolut seperti `/images/logo.png` yang akan mencari file di root domain, bukan di lokasi yang tepat
2. **Struktur Subdomain**: Di server production, subdomain `/admin` hanya berisi `.htaccess` yang me-redirect ke project utama
3. **File Location**: File gambar sebenarnya ada di `/public/images/` di project utama

### Contoh Masalah
```php
<!-- ❌ SALAH - Path absolut tanpa helper -->
<img src="/images/logo.png" alt="Logo">

<!-- ❌ SALAH - Menggunakan url() untuk static assets di subdomain -->
<img src="<?= url('/images/logo.png') ?>" alt="Logo">
<!-- Di subdomain admin akan menghasilkan: https://admin.domain.com/images/logo.png (FILE TIDAK ADA!) -->

<!-- ✅ BENAR - Menggunakan asset() untuk static assets -->
<img src="<?= asset('images/logo.png') ?>" alt="Logo">
<!-- Selalu menghasilkan: https://domain.com/images/logo.png (FILE ADA DI MAIN DOMAIN) -->
```

## Solusi
Menggunakan fungsi `asset()` helper yang **SELALU mengarah ke main domain (APP_URL)** untuk static assets, tidak peduli dari subdomain mana diakses.

### Perbedaan url() vs asset()
```php
// url() - Dinamis berdasarkan subdomain
// Di main domain: https://sekolahzivanamontessori.sch.id/images/logo.png
// Di admin subdomain: https://admin.sekolahzivanamontessori.sch.id/images/logo.png ❌ SALAH!

// asset() - SELALU ke main domain
// Di main domain: https://sekolahzivanamontessori.sch.id/images/logo.png ✅
// Di admin subdomain: https://sekolahzivanamontessori.sch.id/images/logo.png ✅
```

### Fungsi asset() di app/helpers/functions.php
Fungsi ini **SELALU menggunakan APP_URL** (main domain) untuk static assets:

```php
function asset($path) {
    $baseUrl = APP_URL; // ALWAYS use main domain
    
    // Auto-detect port if localhost
    if (strpos($baseUrl, 'localhost') !== false) {
        if (!preg_match('/localhost:\d+/', $baseUrl)) {
            if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 80 && $_SERVER['SERVER_PORT'] != 443) {
                $baseUrl = rtrim($baseUrl, '/') . ':' . $_SERVER['SERVER_PORT'];
            }
        }
    }
    
    return $baseUrl . '/' . ltrim($path, '/');
}
```

## File yang Sudah Diperbaiki
### File Admin (4 files):
1. ✅ `app/views/admin/auth/login.php`
   - Logo: `url('/images/logo.png')` → `asset('images/logo.png')`
   - Gambar ilustrasi: `url('/images/image_profile_section_1.png')` → `asset('images/image_profile_section_1.png')`

2. ✅ `app/views/admin/auth/reset-password.php`
   - Logo: `url('/images/logo.png')` → `asset('images/logo.png')`

3. ✅ `app/views/components/admin-sidebar.php`
   - Logo full: `url('/images/logo.png')` → `asset('images/logo.png')`
   - Logo mini: `url('/images/activity_logo.png')` → `asset('images/activity_logo.png')`

4. ✅ `app/views/layouts/admin.php`
   - Favicon: `url('/images/activity_logo.png')` → `asset('images/activity_logo.png')`

### File Components & Views (31 files):
**Images (25 files):** footer.php, header.php, page_hero.php, activities_card.php, activities_gallery_card.php, activities_kelas.php, activities_kurikulum.php, articles_grid_section.php, articles_list_card_section.php, articles_other.php, home_about.php, home_hero.php, home_kegiatan.php, home_program.php, home_testimoni.php, profile_galeri.php, profile_section.php, profile_team.php, activities-gallery.php, activities.php, article-detail.php, articles.php, index.php, profile-gallery.php, registration.php

**Uploads (11 files):** activities_gallery_card.php, articles_grid_section.php, articles_list_card_section.php, articles_other.php, home_program.php, home_testimoni.php, profile_section.php, article-detail.php, articles.php, profile-gallery.php, profile.php

**Total: 35 files diperbaiki**

## Cara Menggunakan Helper Functions

### asset() - Untuk Static Assets (Images, CSS, JS)
**Gunakan ini untuk semua file static yang ada di main domain**
```php
<!-- Untuk gambar di /images/ -->
<img src="<?= asset('images/logo.png') ?>" alt="Logo">

<!-- Untuk gambar di /uploads/ -->
<img src="<?= asset('uploads/photo.jpg') ?>" alt="Photo">

<!-- Untuk CSS -->
<link rel="stylesheet" href="<?= asset('css/style.css') ?>">

<!-- Untuk JavaScript -->
<script src="<?= asset('js/app.js') ?>"></script>
```

### url() - Untuk Routes/Endpoints
**Gunakan ini untuk form actions, links ke halaman lain**
```php
<!-- Untuk form action -->
<form action="<?= url('/admin/login') ?>" method="POST">

<!-- Untuk link ke halaman -->
<a href="<?= url('/admin/dashboard') ?>">Dashboard</a>

<!-- Untuk redirect -->
header('Location: ' . url('/admin/settings'));
```

## Hasil di Production
### Subdomain Admin (admin.sekolahzivanamontessori.sch.id)
```php
// ✅ asset() - BENAR (Selalu ke main domain)
<?= asset('images/logo.png') ?>
// Output: https://sekolahzivanamontessori.sch.id/images/logo.png

// ✅ url() - BENAR (Untuk routes di admin)
<?= url('/admin/dashboard') ?>
// Output: https://admin.sekolahzivanamontessori.sch.id/admin/dashboard
```

### Main Domain (sekolahzivanamontessori.sch.id)
```php
// ✅ asset() - BENAR
<?= asset('images/logo.png') ?>
// Output: https://sekolahzivanamontessori.sch.id/images/logo.png

// ✅ url() - BENAR
<?= url('/about') ?>
// Output: https://sekolahzivanamontessori.sch.id/about
```

### Development (localhost)
```php
// ✅ asset() - BENAR
<?= asset('images/logo.png') ?>
// Output: http://localhost:8000/images/logo.png

// ✅ url() - BENAR  
<?= url('/admin/dashboard') ?>
// Output: http://localhost:8000/admin/dashboard
```

## Catatan Penting
- ⚠️ **JANGAN** menggunakan path absolut (`/images/...`) untuk assets
- ✅ **GUNAKAN `asset()`** untuk semua gambar, CSS, JS, dan file static
- ✅ **GUNAKAN `url()`** untuk routes, form actions, dan links ke halaman
- ✅ Fungsi `asset()` otomatis mendeteksi environment (production/development)
- ✅ Fungsi `asset()` **SELALU** mengarah ke main domain untuk static files

### Ringkasan Kapan Menggunakan Apa:
| Kebutuhan | Helper Function | Contoh |
|-----------|----------------|---------|
| Gambar | `asset()` | `asset('images/logo.png')` |
| Upload Files | `asset()` | `asset('uploads/photo.jpg')` |
| CSS Files | `asset()` | `asset('css/style.css')` |
| JS Files | `asset()` | `asset('js/app.js')` |
| Form Action | `url()` | `url('/admin/login')` |
| Page Links | `url()` | `url('/about')` |
| Redirects | `url()` | `url('/dashboard')` |

## Checklist untuk Developer
Saat menambahkan gambar atau asset baru, pastikan:
- [ ] Gunakan `asset('path/to/image.jpg')` untuk gambar, CSS, JS
- [ ] Gunakan `url('/path/to/page')` untuk links dan form actions
- [ ] Test di localhost
- [ ] Test di subdomain admin production
- [ ] Test di main domain production
- [ ] Verifikasi gambar load dengan benar di semua environment
