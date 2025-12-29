# R2 Upload Implementation Guide

## 📋 Overview

Implementasi lengkap untuk migrasi upload dari local storage ke Cloudflare R2, dengan support untuk hybrid mode (dapat bekerja di local maupun production dengan R2).

## 🎯 Key Features

✅ **Auto-detect Environment**: Automatically switch antara R2 dan local storage berdasarkan configuration  
✅ **Backward Compatible**: Support untuk existing local file paths  
✅ **Centralized Management**: Semua upload logic di satu helper class  
✅ **Comprehensive Validation**: File type dan size validation built-in  
✅ **Easy Migration**: Minimal code changes di controllers  

---

## 🏗️ Architecture

```
┌─────────────────────────────────────────────────┐
│           Controller Layer                      │
│  (ActivityController, SettingsController, etc)  │
└─────────────────┬───────────────────────────────┘
                  │
                  │ UploadManager::upload()
                  │ UploadManager::delete()
                  │
┌─────────────────▼───────────────────────────────┐
│            UploadManager Helper                  │
│  (Auto-detect R2_ENABLED configuration)         │
└─────────────┬─────────────────┬─────────────────┘
              │                 │
   R2_ENABLED=true       R2_ENABLED=false
              │                 │
┌─────────────▼──────┐   ┌──────▼─────────────┐
│  CloudflareR2      │   │  Local Storage     │
│  (S3 Compatible)   │   │  (public/uploads/) │
└────────────────────┘   └────────────────────┘
```

---

## 📁 File Structure

```
app/
├── helpers/
│   ├── UploadManager.php      # Main upload manager (NEW)
│   └── CloudflareR2.php       # R2 client wrapper (EXISTING)
└── controllers/
    ├── ActivityController.php  # UPDATED (17 endpoints)
    └── SettingsController.php  # UPDATED (handleImageUpload method)

test_r2_upload_complete.php    # Unit tests
test_r2_endpoints.php           # Integration tests
```

---

## 🔧 Configuration

### Environment Variables (.env)

```env
# Enable/Disable R2
R2_ENABLED=true

# R2 Credentials
R2_ACCESS_KEY_ID=your_access_key_id
R2_SECRET_ACCESS_KEY=your_secret_access_key
R2_ACCOUNT_ID=your_account_id

# R2 Buckets
R2_PUBLIC_BUCKET=your-public-bucket
R2_PRIVATE_BUCKET=your-private-bucket

# R2 Endpoint & URL
R2_ENDPOINT=https://your_account_id.r2.cloudflarestorage.com
R2_PUBLIC_URL=https://pub-xxxxx.r2.dev
```

### Local Development

```env
# Untuk development lokal
R2_ENABLED=false
```

### Production

```env
# Untuk production dengan R2
R2_ENABLED=true
# ... tambahkan R2 credentials
```

---

## 💻 Usage Examples

### Basic Upload

```php
require_once APP_PATH . '/helpers/UploadManager.php';

// Upload gambar baru
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    // Validate
    $validation = UploadManager::validateFileType($_FILES['image'], ['jpg', 'jpeg', 'png']);
    if (!$validation['success']) {
        throw new Exception($validation['message']);
    }
    
    $sizeValidation = UploadManager::validateFileSize($_FILES['image'], 5242880); // 5MB
    if (!$sizeValidation['success']) {
        throw new Exception($sizeValidation['message']);
    }
    
    // Upload
    $result = UploadManager::upload($_FILES['image'], 'articles');
    
    if ($result['success']) {
        $imagePath = $result['path']; // Save this to database
        
        // For R2: Returns full URL like https://pub-xxx.r2.dev/articles/filename.jpg
        // For Local: Returns relative path like uploads/articles/filename.jpg
    } else {
        throw new Exception($result['message']);
    }
}
```

### Replace/Update Image

```php
// Update image - old image akan otomatis dihapus
$oldImagePath = $existingRecord['image']; // From database

$result = UploadManager::upload(
    $_FILES['image'], 
    'articles', 
    $oldImagePath  // Pass old image path as 3rd parameter
);

if ($result['success']) {
    $newImagePath = $result['path'];
    // Update database dengan new path
    $db->query("UPDATE articles SET image = ? WHERE id = ?", [$newImagePath, $id]);
}
```

### Delete Image

```php
// Delete image (works for both R2 URL and local path)
$imagePath = $record['image']; // From database
$deleted = UploadManager::delete($imagePath);

if ($deleted) {
    // Delete record from database
    $db->query("DELETE FROM articles WHERE id = ?", [$id]);
}
```

### Get Full URL

```php
// Convert path to full URL (untuk display di frontend)
$imagePath = $record['image']; // From database
$imageUrl = UploadManager::getUrl($imagePath);

// For R2: Returns as-is (already full URL)
// For Local: Converts to full URL like http://localhost/uploads/articles/filename.jpg
```

---

## 🔄 Migration Steps Completed

### ✅ Phase 1: ActivityController.php

**17 endpoints updated:**

1. `storeClass()` - Upload class image
2. `updateClass($id)` - Replace class image
3. `deleteClass($id)` - Delete class image
4. `storeProgramTahun()` - Upload program tahun image
5. `updateProgramTahun($id)` - Replace program tahun image
6. `deleteProgramTahun($id)` - Delete program tahun image + gallery
7. `storeGalleryImage($programId)` - Upload gallery image
8. `updateGalleryImage($programId, $galleryImageId)` - Replace gallery image
9. `deleteGalleryImage($programId, $galleryImageId)` - Delete gallery image
10. `updateProgramImage($programId)` - Replace program cover image
11. `deleteProgramImage($programId)` - Delete program cover image
12. `storeProgramHarian()` - Upload program harian image
13. **`updateProgramHarian($id)`** - Replace program harian image ✨ **NEWLY UPDATED**
14. `deleteProgramHarian($id)` - Delete program harian image
15. `storeGalleryHarianImage($programId)` - Upload gallery harian image
16. `updateGalleryHarianImage($programId, $galleryImageId)` - Replace gallery harian image
17. `deleteGalleryHarianImage($programId, $galleryImageId)` - Delete gallery harian image

### ✅ Phase 2: SettingsController.php

**Method updated:**

1. **`handleImageUpload($file, $folder)`** - Private helper method ✨ **NEWLY UPDATED**
   - Now uses UploadManager internally
   - All methods that call this (Events, Testimonials, Highlights) automatically support R2

---

## 🧪 Testing

### 1. Unit Tests

Run comprehensive unit tests:

```bash
php test_r2_upload_complete.php
```

**Tests include:**
- Configuration check
- UploadManager class methods
- File validation (type & size)
- Upload functionality
- File replacement
- Delete functionality
- Multiple folder uploads
- Cleanup

### 2. Integration Tests

Run endpoint integration tests:

```bash
php test_r2_endpoints.php
```

**Tests include:**
- Server accessibility
- ActivityController endpoints (POST, PUT, DELETE)
- SettingsController endpoints
- Upload location verification

### 3. Manual Testing

**Test via Browser/Postman:**

1. **Upload New Image**
   - Navigate to admin panel
   - Create new class/program/event with image
   - Verify upload success
   - Check database for correct path/URL

2. **Update Existing Image**
   - Edit existing record
   - Upload new image
   - Verify old image is deleted
   - Verify new image is accessible

3. **Delete Record**
   - Delete a record with image
   - Verify image is deleted from storage

4. **Verify Storage Location**
   - **R2 Mode**: Check Cloudflare R2 bucket dashboard
   - **Local Mode**: Check `public/uploads/` directory

---

## 🔍 Verification Checklist

### Local Development (R2_ENABLED=false)

- [ ] Files uploaded to `public/uploads/` directory
- [ ] File paths saved as relative paths (e.g., `uploads/articles/filename.jpg`)
- [ ] Old files deleted when replacing
- [ ] Files deleted when record deleted
- [ ] Images accessible via browser

### Production with R2 (R2_ENABLED=true)

- [ ] Files uploaded to R2 bucket
- [ ] Full URLs saved to database (e.g., `https://pub-xxx.r2.dev/articles/filename.jpg`)
- [ ] Old files deleted from R2 when replacing
- [ ] Files deleted from R2 when record deleted
- [ ] Images accessible via public URL
- [ ] No files left in `public/uploads/` directory

---

## 🚨 Troubleshooting

### Problem: Files not uploading to R2

**Solutions:**
1. Check R2 credentials in `.env`
2. Verify `R2_ENABLED=true`
3. Check R2 bucket permissions
4. Review error logs: `storage/logs/`

### Problem: Old files not being deleted

**Solutions:**
1. Verify old file path format is correct
2. Check if path is R2 URL or local path
3. Ensure R2 delete permissions
4. Check file existence before delete

### Problem: Images not displaying

**Solutions:**
1. **R2**: Verify bucket is public
2. **R2**: Check CORS settings
3. **Local**: Verify file permissions (755 for directories, 644 for files)
4. **Local**: Check web server configuration

### Problem: File validation errors

**Solutions:**
1. Check allowed file types in validation
2. Verify file size limits
3. Check PHP upload limits (`upload_max_filesize`, `post_max_size`)

---

## 📊 Path Format Comparison

| Mode | Saved to DB | Display URL |
|------|------------|-------------|
| **R2** | `https://pub-xxx.r2.dev/articles/123.jpg` | Same as saved |
| **Local** | `uploads/articles/123.jpg` | `http://localhost/uploads/articles/123.jpg` |

---

## 🔒 Security Considerations

1. **File Type Validation**: Always validate file types before upload
2. **File Size Limits**: Enforce reasonable size limits
3. **Unique Filenames**: Auto-generated using timestamp + random hash
4. **Path Sanitization**: Paths sanitized to prevent directory traversal
5. **R2 Bucket Access**: Use separate public/private buckets as needed

---

## 🎯 Best Practices

1. **Always validate before upload**
   ```php
   $validation = UploadManager::validateFileType($_FILES['image'], ['jpg', 'jpeg', 'png']);
   $sizeValidation = UploadManager::validateFileSize($_FILES['image'], 5242880);
   ```

2. **Handle errors gracefully**
   ```php
   if (!$result['success']) {
       throw new Exception($result['message']);
   }
   ```

3. **Delete old files when updating**
   ```php
   $result = UploadManager::upload($_FILES['image'], 'folder', $oldImagePath);
   ```

4. **Use getUrl() for display**
   ```php
   $displayUrl = UploadManager::getUrl($record['image']);
   ```

5. **Store raw path in database**
   - Save exactly what UploadManager returns
   - Don't modify or convert paths
   - Let UploadManager handle path conversions

---

## 📝 Migration Notes

### What Changed

1. **ActivityController.php**
   - `updateProgramHarian()`: Replaced manual file handling with UploadManager
   - Now supports R2 upload and auto-delete old files

2. **SettingsController.php**
   - `handleImageUpload()`: Complete rewrite to use UploadManager
   - Maintains backward compatibility with existing code

### What Didn't Change

- Database schema (no changes needed)
- API responses (same structure)
- Frontend code (transparent to users)
- Existing file references (still work)

---

## 🎉 Benefits

1. **Environment Flexibility**: Easy switch between local and R2
2. **Cost Effective**: Use local storage for dev, R2 for production
3. **Scalability**: R2 handles unlimited storage
4. **Performance**: R2 CDN distribution
5. **Maintainability**: Centralized upload logic
6. **Testing**: Easy to test both modes

---

## 📚 Related Documentation

- [UPLOAD_MIGRATION_PLAN.md](UPLOAD_MIGRATION_PLAN.md) - Original migration plan
- [CloudflareR2.php](app/helpers/CloudflareR2.php) - R2 client documentation
- [UploadManager.php](app/helpers/UploadManager.php) - Upload manager source

---

## 🤝 Support

Jika menemukan issue atau butuh bantuan:

1. Check error logs: `storage/logs/`
2. Run test suite: `php test_r2_upload_complete.php`
3. Verify configuration in `.env`
4. Review this documentation

---

**Last Updated**: December 27, 2025  
**Version**: 1.0  
**Status**: ✅ Production Ready
