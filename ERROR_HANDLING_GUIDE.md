# Error Handling & 404 Page Implementation

## Perubahan yang Dilakukan

### 1. Update Router.php
- Menambahkan method `show404Page()` untuk menampilkan halaman 404 yang sudah ada di `app/views/errors/404.php`
- Update method `dispatch()` untuk menggunakan halaman 404 yang proper

### 2. Update index.php
- Menambahkan error handling dengan try-catch untuk menangkap semua exception
- Membedakan antara 404 error dan 500 server error
- Menampilkan halaman error yang sesuai berdasarkan jenis error

### 3. Create 500 Error Page
- Membuat halaman `app/views/errors/500.php` untuk server error
- Menyediakan tombol "Go Back" dan "Go Home"

### 4. Update 404 Error Page
- Mengupdate halaman 404 menjadi bilingual (Bahasa Indonesia)
- Menambahkan tombol "Kembali" dan "Halaman Utama"

### 5. Update SubdomainMiddleware.php
- Mengupdate logic untuk handle subdomain dengan lebih baik
- Memperbolehkan akses admin routes di dev subdomain untuk testing
- Menampilkan 404 page untuk halaman yang tidak ada di admin subdomain
- Menghindari redirect loop

## Subdomain Configuration

### dev.sekolahzivanamontessori.sch.id
- **Purpose**: Development/testing environment
- **Behavior**: 
  - Dapat akses semua routes termasuk admin routes
  - Route yang tidak ada akan menampilkan halaman 404
  - Tidak melakukan redirect otomatis ke admin subdomain

### admin.sekolahzivanamontessori.sch.id
- **Purpose**: Admin portal
- **Behavior**: 
  - Hanya bisa akses admin routes (`/admin/*`)
  - Akses ke non-admin routes akan menampilkan halaman 404
  - Root URL (`/`) akan redirect ke `/admin/dashboard`

### sekolahzivanamontessori.sch.id
- **Purpose**: Production website (landing page)
- **Behavior**: 
  - Hanya bisa akses public routes
  - Akses ke admin routes akan redirect ke admin subdomain
  - Route yang tidak ada akan menampilkan halaman 404

## Testing

### Test 404 Page
1. Akses URL yang tidak ada:
   - `https://dev.sekolahzivanamontessori.sch.id/halaman-tidak-ada`
   - `https://admin.sekolahzivanamontessori.sch.id/halaman-tidak-ada`

2. Seharusnya menampilkan halaman 404 dengan design yang bagus

### Test Admin Login
1. Akses `https://dev.sekolahzivanamontessori.sch.id/admin/login`
   - Seharusnya bisa diakses (karena dev environment)

2. Akses `https://admin.sekolahzivanamontessori.sch.id/admin/login`
   - Seharusnya bisa diakses (admin portal)

3. Akses `https://sekolahzivanamontessori.sch.id/admin/login`
   - Seharusnya redirect ke admin subdomain

## Error Log

Semua error akan dicatat di log dengan informasi:
- Error message
- Stack trace
- HTTP status code

Lokasi log: `storage/logs/`

## Catatan Penting

1. **404 vs 500**: 
   - 404 = Halaman tidak ditemukan (router tidak menemukan match)
   - 500 = Server error (ada exception/error di code)

2. **Production vs Development**:
   - Development: Error detail ditampilkan
   - Production: Error message generic untuk security

3. **Subdomain Routing**:
   - Dev subdomain lebih permissive untuk testing
   - Admin subdomain strict hanya admin routes
   - Production hanya public routes
