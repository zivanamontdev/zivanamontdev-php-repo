# Email Configuration Guide

## Cara Kerja Email di Aplikasi

Aplikasi ini memiliki **fallback system** untuk email:

1. **Jika SMTP dikonfigurasi** → Email dikirim via Gmail SMTP
2. **Jika SMTP tidak dikonfigurasi** → Email di-log ke file `storage/logs/emails.log`

## Setup Gmail SMTP (Optional)

Jika Anda ingin email benar-benar terkirim (bukan hanya log):

### 1. Buat App Password Gmail

1. Buka **Google Account**: https://myaccount.google.com/
2. Pilih **Security** → **2-Step Verification** (harus diaktifkan dulu)
3. Scroll ke bawah, klik **App passwords**
4. Pilih **Mail** dan **Other (Custom name)**
5. Beri nama: "Zivana School Website"
6. Klik **Generate**
7. Copy password 16 karakter yang muncul

### 2. Update .env di Server

Edit file `.env` di server, tambahkan:

```env
SMTP_USERNAME=your-email@gmail.com
SMTP_PASSWORD=abcd efgh ijkl mnop
```

**Replace dengan:**
- `your-email@gmail.com` → Email Gmail Anda
- `abcd efgh ijkl mnop` → App Password 16 karakter dari step 1

### 3. Test Email

Coba lagi fitur forget password. Sekarang email akan terkirim ke inbox!

## Cek Email yang Ter-log (Tanpa SMTP)

Jika SMTP tidak dikonfigurasi, email akan di-log ke file. 

**Via cPanel File Manager:**
1. Navigate ke `storage/logs/emails.log`
2. Klik kanan → **View**
3. Lihat email content termasuk reset password link
4. Copy link reset password dan gunakan di browser

**Contoh isi log:**
```
================================================================================
TIMESTAMP: 2025-12-21 15:30:00
TO: admin@example.com
SUBJECT: Reset Password - Zivana Montessori School
MESSAGE:
<html>
...
Reset link: https://dev.sekolahzivanamontessori.sch.id/admin/reset-password?token=abc123...
...
</html>
================================================================================
```

## Troubleshooting

### Email tidak terkirim dan tidak ada di log

**Cek:**
1. Folder `storage/logs` ada dan writable (chmod 755)
2. Lihat error log: `storage/logs/error.log`

### SMTP Error: Authentication failed

**Solusi:**
1. Pastikan 2-Step Verification aktif di Gmail
2. Generate App Password baru (jangan pakai password Gmail biasa!)
3. Copy-paste App Password dengan benar (tanpa spasi)

### SMTP Error: Could not connect to SMTP host

**Solusi:**
1. Cek firewall server allow port 587 (TLS) atau 465 (SSL)
2. Contact Hostinger support untuk enable SMTP
3. Atau gunakan fallback (email ke log file)

## Recommended Setup

Untuk production, **tidak wajib setup SMTP**. 

Aplikasi akan otomatis log email ke file, dan admin bisa:
1. Check log file untuk lihat reset link
2. Atau manual reset password user via admin panel (future feature)

Jika ingin email terkirim otomatis, setup SMTP mengikuti panduan di atas.
