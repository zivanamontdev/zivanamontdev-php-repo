# Quick Test Guide - R2 Upload Migration

## 🚀 Quick Start

### 1. Run Unit Tests (Wajib)

```bash
cd d:\Projects\zivanamontdev-php-repo
php test_r2_upload_complete.php
```

**Expected Output:**
```
=======================================================
  R2 UPLOAD MIGRATION - COMPLETE TEST SUITE
=======================================================

Environment: [R2 ENABLED ✓ atau LOCAL MODE ✓]

✓ PASS: R2_ENABLED constant is defined
✓ PASS: UPLOAD_PATH constant is defined
✓ PASS: UploadManager class exists
...
🎉 ALL TESTS PASSED! 🎉
```

### 2. Run Integration Tests (Optional, butuh auth)

```bash
php test_r2_endpoints.php
```

---

## 🧪 Manual Testing Checklist

### Test 1: Upload Gambar Baru (POST)

1. **Navigate to**: `/admin/activities/classes`
2. **Action**: Klik "Add Class" dan upload gambar
3. **Check Database**:
   ```sql
   SELECT id, name, image FROM classes ORDER BY id DESC LIMIT 1;
   ```
4. **Verify**:
   - R2 Mode: `image` field contains full URL (https://...)
   - Local Mode: `image` field contains relative path (uploads/...)
5. **Check Storage**:
   - R2 Mode: Login ke Cloudflare R2 dashboard, cek bucket
   - Local Mode: Check `public/uploads/classes/` directory

---

### Test 2: Update Gambar (PUT)

1. **Navigate to**: `/admin/activities/classes`
2. **Action**: Edit existing class, upload gambar baru
3. **Verify**:
   - ✅ Old image dihapus dari storage
   - ✅ New image berhasil upload
   - ✅ Database updated dengan path/URL baru
4. **Check Storage**:
   - R2 Mode: Old file tidak ada di R2 bucket
   - Local Mode: Old file tidak ada di `public/uploads/classes/`

---

### Test 3: Delete Record (DELETE)

1. **Navigate to**: `/admin/activities/classes`
2. **Action**: Delete class yang punya gambar
3. **Verify**:
   - ✅ Record dihapus dari database
   - ✅ Image file dihapus dari storage
4. **Check Storage**:
   - R2 Mode: File tidak ada di R2 bucket
   - Local Mode: File tidak ada di `public/uploads/classes/`

---

### Test 4: Semua Folder Types

Test upload untuk semua folder yang didukung:

- [ ] `/admin/activities/classes` (classes)
- [ ] `/admin/activities/programs-tahun` (programs_tahun)
- [ ] `/admin/activities/programs-tahun/{id}/gallery` (programs_tahun/gallery)
- [ ] `/admin/activities/programs-harian` (programs_harian)
- [ ] `/admin/activities/programs-harian/{id}/gallery` (programs_harian/gallery)
- [ ] `/admin/settings/events` (events)
- [ ] `/admin/settings/testimonials` (testimonials)
- [ ] `/admin/settings/highlights` (highlights)

---

## 🔍 Verification Commands

### Check Database Records

```sql
-- Check latest uploads
SELECT id, name, image FROM classes ORDER BY id DESC LIMIT 5;
SELECT id, title, image FROM events ORDER BY id DESC LIMIT 5;
SELECT id, name, image FROM programs_tahun ORDER BY id DESC LIMIT 5;
SELECT id, program_name, image FROM programs_harian ORDER BY id DESC LIMIT 5;
```

### Check Local Storage (if R2_ENABLED=false)

```bash
# Windows
dir public\uploads\classes
dir public\uploads\programs_tahun
dir public\uploads\events

# PowerShell
Get-ChildItem -Path "public\uploads\classes" -Recurse
```

### Check R2 Bucket (if R2_ENABLED=true)

1. Login to Cloudflare Dashboard
2. Navigate to R2
3. Open your public bucket
4. Verify files are uploaded in correct folders

---

## 🎯 Test Scenarios by Mode

### Local Development (R2_ENABLED=false)

**Scenario 1: Fresh Upload**
```
Input: Upload test.jpg for new class
Expected Database: uploads/classes/1735344000_abc123def456.jpg
Expected Storage: File exists in public/uploads/classes/
Expected URL: http://localhost/uploads/classes/1735344000_abc123def456.jpg
```

**Scenario 2: Update Upload**
```
Before: uploads/classes/old_file.jpg
Action: Upload new image
Expected Database: uploads/classes/1735344100_xyz789abc456.jpg
Expected Storage: old_file.jpg deleted, new file exists
```

---

### Production with R2 (R2_ENABLED=true)

**Scenario 1: Fresh Upload**
```
Input: Upload test.jpg for new class
Expected Database: https://pub-xxxxx.r2.dev/classes/1735344000_abc123def456.jpg
Expected Storage: File exists in R2 bucket under /classes/
Expected URL: https://pub-xxxxx.r2.dev/classes/1735344000_abc123def456.jpg (publicly accessible)
```

**Scenario 2: Update Upload**
```
Before: https://pub-xxxxx.r2.dev/classes/old_file.jpg
Action: Upload new image
Expected Database: https://pub-xxxxx.r2.dev/classes/1735344100_xyz789abc456.jpg
Expected Storage: old_file.jpg deleted from R2, new file exists
```

---

## 🚨 Common Issues & Quick Fixes

### Issue 1: Files not uploading

**Check:**
```php
// In browser console or PHP error log
// Look for:
// - "File upload error"
// - "R2 upload failed"
// - "Failed to move uploaded file"
```

**Fix:**
- Check R2 credentials if R2_ENABLED=true
- Check folder permissions if R2_ENABLED=false
- Check PHP upload limits (php.ini)

---

### Issue 2: Old files not deleted

**Check:**
```php
// Verify old file path format
var_dump($oldImagePath);

// For R2: Should start with http:// or https://
// For Local: Should be like "uploads/folder/file.jpg"
```

**Fix:**
- Ensure old file path is passed correctly
- Check UploadManager::delete() is called
- Verify R2 delete permissions

---

### Issue 3: Images not displaying

**Check:**
```
// R2 Mode
curl -I https://pub-xxxxx.r2.dev/classes/filename.jpg
# Should return 200 OK

// Local Mode
curl -I http://localhost/uploads/classes/filename.jpg
# Should return 200 OK
```

**Fix:**
- R2: Check bucket is public
- R2: Check CORS settings
- Local: Check file permissions (644)
- Local: Check .htaccess rules

---

## 📊 Performance Benchmarks

### Expected Upload Times

| Mode | File Size | Expected Time |
|------|-----------|---------------|
| Local | 1MB | < 1 second |
| Local | 5MB | < 2 seconds |
| R2 | 1MB | 2-5 seconds |
| R2 | 5MB | 5-10 seconds |

*Times may vary based on internet connection for R2*

---

## ✅ Final Checklist Before Production

- [ ] All unit tests pass
- [ ] Manual testing completed for all endpoints
- [ ] R2 credentials configured in production .env
- [ ] R2 bucket is public and accessible
- [ ] Database backups created
- [ ] Old upload files backed up (if needed)
- [ ] Error logging enabled
- [ ] Monitoring setup for R2 usage
- [ ] Documentation updated
- [ ] Team notified of changes

---

## 🎉 Success Criteria

✅ Unit tests: 100% pass rate  
✅ Upload: Files appear in correct location (R2 or local)  
✅ Update: Old files deleted, new files uploaded  
✅ Delete: Files removed from storage  
✅ Display: Images accessible via public URL  
✅ Performance: Upload times within expected range  
✅ Errors: No errors in logs  

---

## 📞 Need Help?

1. Check [R2_IMPLEMENTATION_GUIDE.md](R2_IMPLEMENTATION_GUIDE.md) for detailed docs
2. Review error logs: `storage/logs/`
3. Run test suite with verbose output
4. Check Cloudflare R2 dashboard for bucket status

---

**Quick Reference:**
- Test File 1: `test_r2_upload_complete.php` (Unit tests)
- Test File 2: `test_r2_endpoints.php` (Integration tests)
- Documentation: `R2_IMPLEMENTATION_GUIDE.md`
- Migration Plan: `UPLOAD_MIGRATION_PLAN.md`
