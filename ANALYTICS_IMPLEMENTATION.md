# Analytics & GeoLocation Implementation

## 📊 Fitur yang Diimplementasikan

### 1. Page Views Tracking
Sistem otomatis mencatat setiap kunjungan halaman dengan informasi:
- URL halaman yang dikunjungi
- IP address pengunjung
- User agent (browser/device info)
- Lokasi geografis (city/region)
- Timestamp kunjungan

### 2. GeoLocation Detection
Menggunakan API gratis dari ip-api.com untuk mendeteksi lokasi berdasarkan IP address:
- **Cache System**: Hasil lookup disimpan di `storage/cache/geoip_cache.json` untuk performa
- **Private IP Detection**: Mendeteksi localhost dan private IP ranges
- **Fallback**: Jika API gagal, lokasi akan dicatat sebagai "Unknown"
- **Timeout**: Request dibatasi 2 detik untuk menghindari blocking

### 3. Dashboard Analytics
Data analytics ditampilkan di halaman admin dashboard:
- **Halaman Terpopuler**: Top 5 halaman dengan views terbanyak
- **Lokasi**: Top 5 lokasi pengunjung
- **Pengunjung Per Jam**: Grafik 12 slot waktu (2 jam interval)
- **Stats Cards**: Total views, unique visitors, registrations

### 4. Period Filter
Filter periode untuk melihat data:
- **Hari ini** (day)
- **7 Hari** (week)
- **30 Hari** (month)
- **1 Tahun** (year)

## 📁 File yang Dibuat/Dimodifikasi

### Baru Dibuat:
1. `database/migrations/016_create_page_views_table.sql` - Tabel page_views
2. `app/helpers/geoip.php` - GeoIP helper class
3. `storage/cache/` - Folder untuk cache geoip

### Dimodifikasi:
1. `app/models/Analytics.php` - Model untuk query analytics
2. `app/controllers/DashboardController.php` - Controller dashboard dengan data real
3. `app/core/Router.php` - Auto-tracking di setiap page request
4. `app/views/admin/dashboard/index.php` - View dengan data real/fallback

## 🚀 Cara Menggunakan

### 1. Database Setup
Migration sudah dijalankan otomatis. Tabel `page_views` sudah dibuat dengan struktur:
```sql
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- page_url (VARCHAR 255) - URL halaman
- ip_address (VARCHAR 45) - IPv4/IPv6 address
- user_agent (TEXT) - Browser/device info
- location (VARCHAR 100) - City/Region/Country
- visited_at (TIMESTAMP) - Waktu kunjungan
```

### 2. Tracking Otomatis
Tracking berjalan otomatis untuk setiap request GET, kecuali:
- Halaman admin (`/admin/*`)
- API endpoints (`/api/*`)
- Assets (`/assets/*`, `/uploads/*`, `/images/*`)
- File statis (`.css`, `.js`, gambar)

### 3. Melihat Analytics
1. Login ke admin panel
2. Buka Dashboard
3. Data akan ditampilkan secara real-time
4. Gunakan dropdown "Bulan Ini" untuk filter periode

### 4. GeoIP Cache
- Cache disimpan di `storage/cache/geoip_cache.json`
- Setiap IP hanya di-lookup sekali, selanjutnya dari cache
- Untuk clear cache (jika perlu):
```php
GeoIP::clearCache();
```

## ⚙️ Konfigurasi

### API Limit
ip-api.com memiliki limit **45 requests/menit** (free tier).
Dengan cache system, limit ini tidak akan tercapai kecuali ada visitor baru dengan IP berbeda > 45/menit.

### Timeout
Request GeoIP timeout di-set 2 detik. Jika API lambat/down, tracking tetap berjalan dengan location = "Unknown".

### Error Handling
Semua error di-catch dan di-log ke error_log. Tracking failure tidak akan break aplikasi.

## 📈 Analytics Model Methods

### Public Methods:
```php
// Track page view
$analytics->trackPageView($pageUrl, $ipAddress, $userAgent, $location);

// Get popular pages
$pages = $analytics->getPopularPages($period = 'month', $limit = 5);

// Get top locations
$locations = $analytics->getTopLocations($period = 'month', $limit = 5);

// Get hourly views (12 slots x 2 hours)
$hourly = $analytics->getHourlyViews($period = 'day');

// Get totals
$totalViews = $analytics->getTotalViews($period = 'month');
$uniqueVisitors = $analytics->getUniqueVisitors($period = 'month');
```

### Period Options:
- `'day'` - Hari ini
- `'week'` - 7 hari terakhir
- `'month'` - 30 hari terakhir
- `'year'` - 1 tahun terakhir

## 🔧 Troubleshooting

### Data tidak muncul di dashboard?
1. Cek apakah tabel `page_views` sudah dibuat: `SHOW TABLES LIKE 'page_views';`
2. Cek apakah ada data: `SELECT COUNT(*) FROM page_views;`
3. Buka halaman publik (non-admin) untuk generate tracking
4. Cek error log: `storage/logs/`

### Location selalu "Unknown"?
1. Cek koneksi internet server
2. Test API manual: `http://ip-api.com/json/8.8.8.8`
3. Cek apakah IP adalah localhost/private IP (akan jadi "Local")

### Cache tidak bekerja?
1. Cek permission folder: `storage/cache/` harus writable
2. Cek apakah file `geoip_cache.json` ter-create
3. Try manual clear: `GeoIP::clearCache();`

## 🎯 Next Steps (Opsional)

1. **Comparison Logic**: Implementasi perbandingan periode sebelumnya untuk % change yang akurat
2. **Real-time Updates**: Implementasi AJAX/WebSocket untuk live updates
3. **Export Reports**: Export data ke CSV/PDF
4. **Advanced Filters**: Filter by page category, device type, etc.
5. **User Journey**: Track visitor path/flow
6. **Heatmaps**: Visual representation of page popularity

## 📝 Notes

- **Performance**: GeoIP API call hanya untuk IP baru (sisanya dari cache)
- **Privacy**: Hanya menyimpan IP + location, tidak ada data personal
- **Storage**: Tabel `page_views` akan bertambah seiring waktu, consider periodic cleanup
- **Alternative**: Bisa pakai Google Analytics untuk production (lebih powerful)

---

**Status**: ✅ Fully Implemented & Working
**Version**: 1.0.0
**Date**: December 19, 2025
