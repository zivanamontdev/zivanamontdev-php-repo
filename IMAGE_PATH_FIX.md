# Fix: Gambar Tidak Load di Production (Subdomain /admin)

## Masalah
Di production, gambar-gambar di halaman admin (seperti logo dan gambar di halaman login) tidak bisa load ketika diakses melalui subdomain `admin.sekolahzivanamontessori.sch.id`.

## Penyebab
1. **Path Absolut yang Salah**: Gambar menggunakan path absolut seperti `/images/logo.png` yang akan mencari file di root domain, bukan di lokasi yang tepat
2. **Struktur Subdomain**: Di server production, subdomain `/admin` hanya berisi `.htaccess` yang me-redirect ke project utama
3. **File Location**: File gambar sebenarnya ada di `/public/images/` di project utama

### Contoh Masalah
```php
<!-- ❌ SALAH - Path absolut -->
<img src="/images/logo.png" alt="Logo">

<!-- ✅ BENAR - Menggunakan fungsi url() -->
<img src="<?= url('/images/logo.png') ?>" alt="Logo">
```

## Solusi
Menggunakan fungsi `url()` helper yang sudah ada untuk generate path yang tepat berdasarkan context (subdomain atau main domain).

### Fungsi url() di app/helpers/functions.php
Fungsi ini otomatis mendeteksi:
- Apakah request dari subdomain admin atau main domain
- Port untuk development (localhost)
- Base URL yang tepat (APP_URL atau ADMIN_URL)

```php
function url($path = '') {
    // Check if this is admin route
    $isAdminRoute = strpos($path, '/admin') === 0 || strpos($path, 'admin/') !== false;
    
    // Check if we're on admin subdomain
    $isAdminSubdomain = false;
    if (defined('IS_ADMIN_SUBDOMAIN') && IS_ADMIN_SUBDOMAIN === true) {
        $isAdminSubdomain = true;
    } elseif (isset($_SERVER['HTTP_HOST'])) {
        $isAdminSubdomain = strpos($_SERVER['HTTP_HOST'], 'admin.') === 0;
    }
    
    // Determine base URL
    if ($isAdminRoute || $isAdminSubdomain) {
        $baseUrl = ADMIN_URL ?? APP_URL;
    } else {
        $baseUrl = APP_URL;
    }
    
    return $baseUrl . '/' . ltrim($path, '/');
}
```

## File yang Sudah Diperbaiki
### File Admin
1. ✅ `app/views/admin/auth/login.php`
   - Logo: `/images/logo.png` → `<?= url('/images/logo.png') ?>`
   - Gambar ilustrasi: `/images/image_profile_section_1.png` → `<?= url('/images/image_profile_section_1.png') ?>`

2. ✅ `app/views/admin/auth/reset-password.php`
   - Logo: `/images/logo.png` → `<?= url('/images/logo.png') ?>`

3. ✅ `app/views/components/admin-sidebar.php`
   - Logo full: `url('images/logo.png')` → `url('/images/logo.png')`
   - Logo mini: `url('images/activity_logo.png')` → `url('/images/activity_logo.png')`

### File Components & Views (Diperbaiki via Script)
Semua file berikut sudah diperbaiki dengan script otomatis (19 files):
- ✅ `footer.php` - Logo white, vector icons (WA, phone, Instagram, TikTok, Facebook)
- ✅ `activities_card.php` - Floating vectors
- ✅ `activities_gallery_card.php` - Placeholder images
- ✅ `activities_kelas.php` - Vector highlight kelas
- ✅ `activities_kurikulum.php` - Vector kurikulum
- ✅ `articles_grid_section.php` - Default article images
- ✅ `articles_list_card_section.php` - Article images & uploads
- ✅ `articles_other.php` - Article thumbnails
- ✅ `profile_galeri.php` - Gallery images
- ✅ `profile_section.php` - Profile vector & uploads
- ✅ `profile_team.php` - Team images
- ✅ `activities-gallery.php` - Vector galeri
- ✅ `activities.php` - Program highlight vectors
- ✅ `article-detail.php` - Article images
- ✅ `articles.php` - Article listings
- ✅ `profile-gallery.php` - Gallery & vector galeri
- ✅ `profile.php` - Profile photos & uploads
- ✅ `registration.php` - Vector registration
- ✅ `admin.php` - Favicon

**Total: 22 files diperbaiki**

## Cara Menggunakan url() Helper
```php
<!-- Untuk gambar -->
<img src="<?= url('/images/logo.png') ?>" alt="Logo">

<!-- Untuk CSS -->
<link rel="stylesheet" href="<?= url('/css/style.css') ?>">

<!-- Untuk JavaScript -->
<script src="<?= url('/js/app.js') ?>"></script>

<!-- Untuk form action -->
<form action="<?= url('/admin/login') ?>" method="POST">

<!-- Untuk link -->
<a href="<?= url('/admin/dashboard') ?>">Dashboard</a>
```

## Hasil di Production
### Subdomain Admin (admin.sekolahzivanamontessori.sch.id)
```php
<?= url('/images/logo.png') ?>
// Output: https://admin.sekolahzivanamontessori.sch.id/images/logo.png
```

### Main Domain (sekolahzivanamontessori.sch.id)
```php
<?= url('/images/logo.png') ?>
// Output: https://sekolahzivanamontessori.sch.id/images/logo.png
```

### Development (localhost)
```php
<?= url('/images/logo.png') ?>
// Output: http://localhost:8000/images/logo.png (atau port yang sedang digunakan)
```

## Catatan Penting
- ⚠️ **JANGAN** menggunakan path absolut (`/images/...`) untuk assets
- ✅ **SELALU** gunakan fungsi `url()` untuk generate path
- ✅ Fungsi `url()` otomatis mendeteksi environment (production/development)
- ✅ Fungsi `url()` otomatis handle subdomain admin

## Checklist untuk Developer
Saat menambahkan gambar atau asset baru, pastikan:
- [ ] Gunakan `<?= url('/path/to/asset') ?>` bukan `/path/to/asset`
- [ ] Test di localhost
- [ ] Test di subdomain admin production
- [ ] Test di main domain production
