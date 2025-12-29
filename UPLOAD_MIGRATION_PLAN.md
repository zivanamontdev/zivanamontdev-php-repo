# Upload Migration Plan - R2 Integration

## Overview
Migrasi dari local storage (`public/uploads/`) ke Cloudflare R2 object storage.

## Helper Created
**File:** `app/helpers/UploadManager.php`

### Usage
```php
require_once APP_PATH . '/helpers/UploadManager.php';

// Upload gambar baru (POST)
$result = UploadManager::upload($_FILES['image'], 'articles');
if ($result['success']) {
    $imagePath = $result['path']; // Save to database
}

// Replace gambar lama (PUT)
$result = UploadManager::upload($_FILES['image'], 'articles', $oldImagePath);

// Delete gambar (DELETE)
UploadManager::delete($imagePath);

// Validate file
$validation = UploadManager::validateFileType($_FILES['image'], ['jpg', 'png']);
$sizeCheck = UploadManager::validateFileSize($_FILES['image'], 5242880); // 5MB
```

## Endpoints yang Perlu Dimodifikasi

### 1. ActivityController.php (17 endpoints)

#### POST Operations (Uploads)
1. **`storeClass()`** - Line 114
   - Upload: `$_FILES['image']`
   - Folder: `classes`
   
2. **`storeProgramTahun()`** - Line 300
   - Upload: `$_FILES['image']`
   - Folder: `programs_tahun`
   
3. **`storeGalleryImage($programId)`** - Line 464
   - Upload: `$_FILES['image']`
   - Folder: `programs_tahun/gallery`
   
4. **`storeGalleryHarianImage($programId)`** - Line 877
   - Upload: `$_FILES['image']`
   - Folder: `programs_harian/gallery`

#### PUT Operations (Replace)
5. **`updateClass($id)`** - Line 188
   - Upload: `$_FILES['image']`
   - Folder: `classes`
   - Delete old: Yes
   
6. **`updateProgramTahun($id)`** - Line 361
   - Upload: `$_FILES['image']`
   - Folder: `programs_tahun`
   - Delete old: Yes
   
7. **`updateGalleryImage($programId, $galleryImageId)`** - Line 549
   - Upload: `$_FILES['image']`
   - Folder: `programs_tahun/gallery`
   - Delete old: Yes
   
8. **`updateProgramImage($programId)`** - Line 639
   - Upload: `$_FILES['image']`
   - Folder: `programs_tahun`
   - Delete old: Yes
   
9. **`updateProgramHarian($id)`** - Line 796
   - Upload: `$_FILES['image']`
   - Folder: `programs_harian`
   - Delete old: Yes
   
10. **`updateGalleryHarianImage($programId, $galleryImageId)`** - Line 955
    - Upload: `$_FILES['image']`
    - Folder: `programs_harian/gallery`
    - Delete old: Yes
    
11. **`updateProgramHarianImage($programId)`** - Line 1041
    - Upload: `$_FILES['image']`
    - Folder: `programs_harian`
    - Delete old: Yes

#### DELETE Operations
12. **`deleteClass($id)`** - Line 268
    - Delete image: Yes
    
13. **`deleteProgramTahun($id)`** - Line 434
    - Delete image: Yes
    - Delete gallery images: Yes
    
14. **`deleteProgramImage($programId)`** - Line 713
    - Delete image: Yes
    
15. **`deleteGalleryImage($programId, $galleryImageId)`** - Line 755
    - Delete image: Yes
    
16. **`deleteProgramHarianImage($programId)`** - Line 1121
    - Delete image: Yes
    
17. **`deleteGalleryHarianImage($programId, $galleryImageId)`** - Line 1159
    - Delete image: Yes

---

### 2. SettingsController.php (6 endpoints)

#### POST Operations
1. **`createEvent()`** - Line 195
   - Upload: `$_FILES['image']`
   - Folder: `events`

2. **`createTestimonial()`** - Line 402
   - Upload: `$_FILES['image']`
   - Folder: `testimonials`

3. **`createHighlight()`** - Line ~550
   - Upload: `$_FILES['image']`
   - Folder: `highlights`

#### PUT Operations
4. **`updateEvent($id)`** - Line 226
   - Upload: `$_FILES['image']`
   - Folder: `events`
   - Delete old: Yes

5. **`updateTestimonial($id)`** - Line 444
   - Upload: `$_FILES['image']`
   - Folder: `testimonials`
   - Delete old: Yes

6. **`updateHighlight($id)`** - Line ~600
   - Upload: `$_FILES['image']`
   - Folder: `highlights`
   - Delete old: Yes

#### DELETE Operations
7. **`deleteEvent($id)`** - Line 258
   - Delete image: Yes

8. **`deleteTestimonial($id)`** - Line 494
   - Delete image: Yes

9. **`deleteHighlight($id)`** - Line ~650
   - Delete image: Yes

---

## Implementation Strategy

### ✅ Phase 1: ActivityController.php (Priority: HIGH) - COMPLETED
- ✅ Most active endpoints
- ✅ 17 methods updated and using UploadManager
- ✅ All POST, PUT, DELETE operations migrated
- **Latest Update**: updateProgramHarian() now uses UploadManager

### ✅ Phase 2: SettingsController.php (Priority: MEDIUM) - COMPLETED
- ✅ handleImageUpload() method rewritten to use UploadManager
- ✅ Events, Testimonials, Highlights automatically supported
- ✅ Backward compatible with existing code

### Phase 3: Other Controllers (To be discovered)
- ArticleController - To be reviewed
- EmployeeController - To be reviewed
- ManagementController - To be reviewed

---

## Migration Checklist

### Per Endpoint:
- [x] Replace `move_uploaded_file()` with `UploadManager::upload()`
- [x] Add file validation before upload
- [x] For PUT: Pass old file path as 3rd parameter
- [x] For DELETE: Add `UploadManager::delete($imagePath)` before DB deletion
- [x] Test upload functionality
- [x] Test delete functionality
- [x] Verify R2 bucket content

### Global:
- [x] Ensure all controllers include UploadManager.php
- [x] Test with R2_ENABLED=true
- [x] Test with R2_ENABLED=false (local fallback)
- [x] Update documentation

### Completed Implementations:
- [x] ActivityController.php - All 17 endpoints ✅
  - [x] updateProgramHarian() - NOW USES UploadManager ✅
- [x] SettingsController.php - handleImageUpload() method ✅
  - [x] All Events, Testimonials, Highlights automatically supported ✅

---

## Test Files Created

### 1. test_r2_upload_complete.php
Comprehensive unit tests for UploadManager functionality:
- Configuration validation
- Class method checks
- File validation tests
- Upload/Replace/Delete functionality
- Multiple folder support
- Automatic cleanup

### 2. test_r2_endpoints.php
Integration tests for actual HTTP endpoints:
- Server accessibility
- ActivityController endpoints
- SettingsController endpoints
- Upload location verification

### 3. R2_IMPLEMENTATION_GUIDE.md
Complete documentation covering:
- Architecture overview
- Configuration guide
- Usage examples
- Testing procedures
- Troubleshooting guide
- Best practices

---

## Testing Checklist

### Test Cases:
1. **Upload baru (POST)**
   - ✅ File berhasil upload ke R2
   - ✅ URL R2 disimpan ke database
   - ✅ File accessible via public URL

2. **Replace image (PUT)**
   - ✅ Old file dihapus dari R2
   - ✅ New file berhasil upload
   - ✅ Database updated dengan URL baru

3. **Delete record (DELETE)**
   - ✅ File dihapus dari R2
   - ✅ Database record dihapus

4. **Fallback Mode (R2_ENABLED=false)**
   - ✅ Upload ke local storage
   - ✅ Delete dari local storage

---

## Next Steps

1. Implementasi ActivityController.php (17 methods)
2. Implementasi SettingsController.php (9 methods)
3. Discover other controllers dengan upload functionality
4. Testing end-to-end
5. Production deployment

---

## Notes

- **R2_ENABLED**: Control via `.env` file
- **Folder Structure**: Maintain same structure as local (`articles`, `events`, etc.)
- **Backward Compatibility**: Helper supports both R2 URLs and local paths
- **Error Handling**: All methods return structured response with success/error messages
