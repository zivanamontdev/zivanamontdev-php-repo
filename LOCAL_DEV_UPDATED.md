# Local Development Setup - Updated

## Perubahan untuk Development Lokal

Kami telah menambahkan support untuk local development yang **TIDAK akan mempengaruhi production**.

### Yang Ditambahkan

1. **LocalDevMiddleware** - Middleware khusus untuk local development
   - File: `app/middleware/LocalDevMiddleware.php`
   - Bypass subdomain checks saat di localhost
   - Otomatis fallback ke SubdomainMiddleware di production

2. **IS_LOCAL_DEV_SERVER Flag** - Di router.php
   - File: `public/router.php`
   - Set flag sebelum load aplikasi
   - Hanya aktif saat menggunakan PHP built-in server

3. **IS_LOCAL_DEV Constant** - Di config.php
   - File: `config/config.php`
   - Detect localhost dari HTTP_HOST
   - Digunakan untuk conditional logic di seluruh aplikasi

### Cara Menjalankan Local Development

```bash
# Di folder public
cd public
php -S localhost:8001 router.php
```

### Akses Admin di Local

Sekarang Anda bisa akses admin langsung tanpa subdomain:

```
http://localhost:8001/admin
http://localhost:8001/admin/login
http://localhost:8001/admin/dashboard
```

### Yang Tetap Sama di Production

1. **SubdomainMiddleware** - Tidak diubah sama sekali
   - Tetap enforce subdomain di production
   - admin.sekolahzivanamontessori.sch.id tetap diperlukan

2. **index.php** - Hanya ditambahkan localhost detection
   - If localhost → allow admin routes
   - If production → block admin routes di main domain

3. **Security** - Tetap terjaga
   - Production tetap require subdomain
   - Localhost hanya untuk development

### Testing Script yang Bisa Dijalankan

```bash
# Test database articles
http://localhost:8001/fix_published_at.php

# Test debug articles
http://localhost:8001/debug_articles.php

# Test R2 connection
http://localhost:8001/test_r2_connection.php
```

### Troubleshooting

Jika masih 404:
1. Pastikan server berjalan dengan router.php
2. Cek terminal untuk log errors
3. Pastikan IS_LOCAL_DEV_SERVER flag terdeteksi

Log yang seharusnya muncul:
```
LocalDevMiddleware - Running on local development, bypassing subdomain checks
```
