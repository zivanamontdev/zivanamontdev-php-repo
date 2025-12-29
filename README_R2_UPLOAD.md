# 🚀 R2 Upload Migration - Quick Start

## TL;DR

Upload system sekarang sudah mendukung **Cloudflare R2** dengan fallback ke local storage. Ganti environment dengan mudah via `.env` file!

---

## 📦 What's New?

✅ **Hybrid Upload System**: R2 untuk production, local untuk development  
✅ **Zero Code Changes**: Switch mode hanya dengan config  
✅ **Auto Cleanup**: Old files otomatis terhapus saat replace  
✅ **Complete Tests**: Unit tests + Integration tests  
✅ **Full Documentation**: Step-by-step guides tersedia  

---

## ⚡ Quick Setup

### 1. Local Development

```env
# .env file
R2_ENABLED=false
```

That's it! Upload akan ke `public/uploads/`

### 2. Production with R2

```env
# .env file
R2_ENABLED=true
R2_ACCESS_KEY_ID=your_key_here
R2_SECRET_ACCESS_KEY=your_secret_here
R2_ACCOUNT_ID=your_account_id
R2_PUBLIC_BUCKET=your-bucket-name
R2_ENDPOINT=https://your_account.r2.cloudflarestorage.com
R2_PUBLIC_URL=https://pub-xxxxx.r2.dev
```

Upload akan ke R2 bucket!

---

## 🧪 Testing

### Run Tests

```bash
# Unit tests (recommended)
php test_r2_upload_complete.php

# Integration tests (optional)
php test_r2_endpoints.php
```

### Expected Result

```
🎉 ALL TESTS PASSED! 🎉
Your R2 upload migration is working correctly!
```

---

## 📚 Documentation

| File | Purpose | Priority |
|------|---------|----------|
| **[QUICK_TEST_GUIDE.md](QUICK_TEST_GUIDE.md)** | Testing steps | ⭐⭐⭐ |
| **[R2_IMPLEMENTATION_GUIDE.md](R2_IMPLEMENTATION_GUIDE.md)** | Complete guide | ⭐⭐ |
| **[SUMMARY.md](SUMMARY.md)** | Implementation summary | ⭐ |
| [UPLOAD_MIGRATION_PLAN.md](UPLOAD_MIGRATION_PLAN.md) | Original plan | ℹ️ |

---

## 🎯 What Works Now?

### All These Endpoints Support R2:

**ActivityController**
- ✅ Classes (upload, update, delete)
- ✅ Programs Tahun (upload, update, delete)
- ✅ Programs Harian (upload, update, delete)
- ✅ Gallery Images (all operations)

**SettingsController**
- ✅ Events (upload, update, delete)
- ✅ Testimonials (upload, update, delete)
- ✅ Highlights (upload, update, delete)

---

## 🔄 How to Switch Modes

### Switch to R2
```bash
# Update .env
R2_ENABLED=true

# Test it
php test_r2_upload_complete.php
```

### Switch to Local
```bash
# Update .env
R2_ENABLED=false

# Test it
php test_r2_upload_complete.php
```

**No code changes needed!**

---

## 🚨 Troubleshooting

### Upload not working?

1. **Check configuration**
   ```bash
   php -r "require 'config/config.php'; var_dump(R2_ENABLED);"
   ```

2. **Run tests**
   ```bash
   php test_r2_upload_complete.php
   ```

3. **Check logs**
   ```bash
   tail -f storage/logs/error.log
   ```

### Images not displaying?

**R2 Mode:**
- Verify bucket is public
- Check CORS settings
- Test URL directly: `https://pub-xxxxx.r2.dev/test.jpg`

**Local Mode:**
- Check file permissions (644)
- Verify folder exists: `public/uploads/`
- Check .htaccess rules

---

## 📖 Need More Info?

### For Developers
→ Read [R2_IMPLEMENTATION_GUIDE.md](R2_IMPLEMENTATION_GUIDE.md)

### For Testing
→ Read [QUICK_TEST_GUIDE.md](QUICK_TEST_GUIDE.md)

### For Summary
→ Read [SUMMARY.md](SUMMARY.md)

---

## ✅ Pre-Production Checklist

Before deploying to production:

- [ ] Run `php test_r2_upload_complete.php` - all tests pass
- [ ] Configure R2 credentials in `.env`
- [ ] Set `R2_ENABLED=true`
- [ ] Test one upload manually via browser
- [ ] Verify image loads from R2 URL
- [ ] Check R2 bucket in Cloudflare dashboard
- [ ] Backup database
- [ ] Monitor error logs after deployment

---

## 🎉 Success!

If tests pass and images upload correctly, you're done! 

The system will now:
- ✅ Upload to R2 (or local based on config)
- ✅ Auto-delete old files when replacing
- ✅ Clean up when records deleted
- ✅ Work seamlessly in both modes

---

## 📞 Support

Issues? Check these in order:

1. [QUICK_TEST_GUIDE.md](QUICK_TEST_GUIDE.md) - Common issues section
2. [R2_IMPLEMENTATION_GUIDE.md](R2_IMPLEMENTATION_GUIDE.md) - Troubleshooting
3. Error logs: `storage/logs/`
4. Run test suite for diagnostics

---

**Version**: 1.0  
**Status**: ✅ Production Ready  
**Last Updated**: December 27, 2025
