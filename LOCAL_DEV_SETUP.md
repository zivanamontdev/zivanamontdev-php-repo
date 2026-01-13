# Local Development Setup - Admin Access

## 1. Start Local Server

Buka terminal/PowerShell di folder project, lalu jalankan:

```powershell
cd public
php -S localhost:8000 router.php
```

**PENTING**: Harus gunakan `router.php` agar routing berfungsi dengan baik!

Atau jika port 8000 sudah dipakai:
```powershell
php -S localhost:3000 router.php
```

## 2. Akses Admin Panel

Buka browser dan akses:
- **Homepage**: http://localhost:8000/
- **Admin Login**: http://localhost:8000/admin/login
- **Admin Dashboard**: http://localhost:8000/admin/dashboard

## 3. Kredensial Login Default

Jika belum punya akun admin, buat dengan cara:

### Option A: Via Script Reset Password
1. Buka file `reset_admin_password.php` yang sudah ada di root project
2. Akses via browser: http://localhost:8000/../reset_admin_password.php
3. Ikuti instruksi untuk membuat/reset password admin

### Option B: Via Database Direct
Jalankan query SQL di database:

```sql
-- Cek user yang ada
SELECT id, name, email, role FROM users;

-- Update password user admin (password: admin123)
UPDATE users 
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE role = 'admin' 
LIMIT 1;

-- Atau buat user admin baru
INSERT INTO users (name, email, password, role, created_at, updated_at) 
VALUES (
    'Admin', 
    'admin@zivanamontessori.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin',
    NOW(),
    NOW()
);
```

Password hash di atas adalah: **admin123**

## 4. Troubleshooting

### Issue: 404 Not Found saat akses admin
**Solusi**: Pastikan `.htaccess` sudah ada di folder `public/`

### Issue: Cannot login
**Solusi**: 
1. Clear browser cookies/cache
2. Pastikan session berfungsi (check `php.ini` session settings)
3. Check file `storage/logs/error.log` untuk error messages

### Issue: Database connection error
**Solusi**: Check `config/config.php` dan pastikan kredensial database sudah benar

## 5. Quick Commands

```powershell
# Start server (MUST use router.php)
cd public; php -S localhost:8000 router.php

# Check PHP version
php -v

# Test database connection
php -r "require_once '../app/core/Database.php'; require_once '../config/config.php'; \$db = Database::getInstance(); echo 'Database connected!' . PHP_EOL;"

# View error logs
Get-Content ../storage/logs/error.log -Tail 50

# Clear cache (if any)
Remove-Item ../storage/cache/* -Force
```

## 6. Development Workflow

1. **Start server**: `cd public; php -S localhost:8000 router.php`
2. **Login admin**: http://localhost:8000/admin/login
3. **Edit code** dengan editor favorit
4. **Refresh browser** untuk lihat perubahan
5. **Check logs** jika ada error: `storage/logs/error.log`

**Catatan Penting:**
- Selalu gunakan `router.php` saat start server, jika tidak routing akan error 404
- Jika ubah PHP file, tidak perlu restart server (auto reload)
- Jika ubah config/database, restart server dengan Ctrl+C lalu start ulang

## 7. Database Management Tools

Gunakan salah satu tool ini untuk manage database di local:

- **phpMyAdmin**: http://localhost/phpmyadmin (jika pakai XAMPP/MAMP)
- **Adminer**: Download dari https://www.adminer.org/
- **TablePlus**: https://tableplus.com/
- **DBeaver**: https://dbeaver.io/

## 8. Fix Articles Issue

Jika artikel tidak muncul, jalankan fix script:
```
http://localhost:8000/fix_published_at.php
```

Atau debug articles:
```
http://localhost:8000/debug_articles.php
```
