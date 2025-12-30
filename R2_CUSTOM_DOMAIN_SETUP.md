# Setup Custom Domain untuk R2 Cloudflare

## 🎯 Masalah
- URL R2 public bucket (`pub-xxx.r2.dev`) tidak memiliki SSL certificate yang valid
- Menyebabkan error: `ERR_CERT_COMMON_NAME_INVALID` dan `ERR_CONNECTION_RESET`
- Browser memblokir akses karena HSTS

## ✅ Solusi: Custom Domain

### Langkah 1: Setup Custom Domain di Cloudflare Dashboard

1. **Login ke Cloudflare Dashboard**
   - Buka: https://dash.cloudflare.com
   - Masuk dengan akun Cloudflare Anda

2. **Buka R2 Bucket Settings**
   - Pilih "R2" dari sidebar
   - Klik bucket Anda: `zivana-montessori-public`
   - Klik tab **"Settings"**

3. **Add Custom Domain**
   - Scroll ke section "Public Access"
   - Klik **"Connect Domain"** atau **"Add Custom Domain"**
   - Masukkan subdomain: `cdn.sekolahzivanamontessori.sch.id`
   - Atau bisa: `assets.sekolahzivanamontessori.sch.id`
   - Atau: `media.sekolahzivanamontessori.sch.id`

4. **Verifikasi Domain**
   - Cloudflare akan membuat CNAME record secara otomatis
   - Domain harus sudah ada di Cloudflare DNS
   - Tunggu propagasi DNS (5-10 menit)

### Langkah 2: Update .env Production

Setelah custom domain aktif, update file `.env` di server production:

```env
# Ganti dari URL pub-xxx.r2.dev
# R2_PUBLIC_URL=https://pub-445331fec5ba4ec3af5c54ddc3ec1f4b.r2.dev

# Ke custom domain Anda
R2_PUBLIC_URL=https://cdn.sekolahzivanamontessori.sch.id
```

### Langkah 3: Test Upload & Access

1. Upload gambar baru dari dashboard
2. URL gambar akan otomatis menggunakan custom domain
3. Akses gambar di browser - seharusnya tidak ada SSL error

---

## 🔄 Alternatif: Subdomain yang Disarankan

Pilih salah satu:
- `cdn.sekolahzivanamontessori.sch.id` (untuk CDN/static files)
- `assets.sekolahzivanamontessori.sch.id` (untuk assets)
- `media.sekolahzivanamontessori.sch.id` (untuk media/images)
- `files.sekolahzivanamontessori.sch.id` (untuk files)

---

## 📋 Checklist Setup

- [ ] Login ke Cloudflare Dashboard
- [ ] Buka R2 > Bucket > Settings
- [ ] Add Custom Domain (pilih subdomain)
- [ ] Tunggu DNS propagasi (5-10 menit)
- [ ] Verify custom domain aktif dengan akses di browser
- [ ] Update `.env` di production server
- [ ] Restart PHP/web server (jika perlu)
- [ ] Test upload gambar baru
- [ ] Verify gambar dapat diakses tanpa SSL error

---

## 🔍 Troubleshooting

### Custom Domain tidak muncul di Cloudflare R2?
- Pastikan domain `sekolahzivanamontessori.sch.id` sudah ada di Cloudflare DNS
- Jika belum, tambahkan domain ke Cloudflare terlebih dahulu

### DNS propagation lambat?
- Tunggu hingga 24 jam (biasanya 5-10 menit)
- Cek DNS propagation: https://dnschecker.org

### Gambar lama masih menggunakan URL pub-xxx.r2.dev?
- Gambar lama tetap bisa diakses, tapi akan ada SSL error
- Anda bisa:
  1. **Biarkan saja** - gambar tetap berfungsi di backend
  2. **Migrasi URL** - update database untuk replace URL lama ke custom domain

---

## 📊 Keuntungan Custom Domain

✅ **SSL Certificate Valid** - Disediakan Cloudflare secara gratis  
✅ **Branding** - URL lebih professional (cdn.sekolahzivanamontessori.sch.id)  
✅ **Caching** - Cloudflare CDN automatically cache content  
✅ **Analytics** - Akses analytics di Cloudflare Dashboard  
✅ **No HSTS Issues** - Browser tidak akan memblokir koneksi  

---

## 🚀 Setelah Setup Selesai

1. **Backup `.env` lama** sebelum update
2. **Test upload** dari dashboard admin
3. **Verify gambar** dapat diakses di browser tanpa error
4. **Monitor logs** untuk memastikan tidak ada error
5. **Dokumentasi** - catat subdomain yang digunakan

---

## 💡 Tips

- Custom domain **GRATIS** di Cloudflare
- Tidak perlu setup SSL certificate manual (Cloudflare handle otomatis)
- Bisa menggunakan multiple custom domains untuk bucket yang sama
- Custom domain juga support CORS dan caching rules

---

## 📞 Need Help?

Jika ada kesulitan:
1. Cek Cloudflare R2 documentation
2. Verify DNS records di Cloudflare Dashboard
3. Test connectivity dengan `curl` atau browser dev tools
