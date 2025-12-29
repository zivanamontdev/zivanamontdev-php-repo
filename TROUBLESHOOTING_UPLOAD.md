# Troubleshooting Upload Issues

## 🚨 Issue Reported

User upload gambar di halaman Activities > Informasi Kelas, muncul URL R2:
```
https://pub-445331fec5ba4ec3af5c54ddc3ec1f4b.r2.dev/classes/1766843226_8862f39c5fc8f043_1766843226.png
```

Tapi tidak jelas errornya apa. Gambar tidak muncul/tidak terupload dengan benar.

---

## 🔍 Diagnosis Steps

### Step 1: Test R2 Connection

```bash
# Buka di browser
http://localhost/test_r2_connection.php
```

**Checks:**
- ✅ R2_ENABLED status
- ✅ R2 credentials configuration
- ✅ CloudflareR2 class initialization
- ✅ R2 bucket accessibility

**Expected Result:** All checks should pass with green ✅

---

### Step 2: Test Upload Functionality

```bash
# Buka di browser  
http://localhost/debug_upload.php
```

**What it does:**
1. Shows current configuration
2. Provides upload form
3. Tests actual file upload
4. Verifies file accessibility
5. Shows detailed debug output

**Expected Result:** Upload successful and file accessible

---

### Step 3: Check Error Logs

```bash
# Windows PowerShell
Get-Content storage\logs\error.log -Tail 50

# Or check latest entry
Get-Content storage\logs\error.log | Select-Object -Last 100
```

**Look for:**
- `=== storeClass() called ===`
- `Image file detected, starting upload process...`
- `Upload result: ...`
- Any exception messages

---

## 🐛 Common Issues & Fixes

### Issue 1: R2 Bucket Not Public

**Symptoms:**
- URL generated correctly
- HTTP 403 Forbidden when accessing image
- Image doesn't load in browser

**Fix:**
1. Login to Cloudflare Dashboard
2. Go to R2 > Select your bucket
3. Go to Settings > Public Access
4. Enable "Allow Public Access"
5. Save changes

**Verification:**
```bash
curl -I https://pub-xxxxx.r2.dev/test.jpg
# Should return: HTTP 200 or 404 (not 403)
```

---

### Issue 2: CORS Configuration

**Symptoms:**
- Upload works
- Image URL valid
- Image doesn't load in browser (CORS error in console)

**Fix:**
1. Go to Cloudflare Dashboard > R2
2. Select your bucket
3. Go to Settings > CORS Policy
4. Add CORS rule:
```json
[
  {
    "AllowedOrigins": ["*"],
    "AllowedMethods": ["GET", "HEAD"],
    "AllowedHeaders": ["*"],
    "MaxAgeSeconds": 3600
  }
]
```

---

### Issue 3: R2 Credentials Invalid

**Symptoms:**
- Upload fails
- Error: "R2 credentials not configured"
- CloudflareR2 class initialization fails

**Fix:**
Check `.env` file:
```env
R2_ENABLED=true
R2_ACCESS_KEY_ID=your_actual_key_here
R2_SECRET_ACCESS_KEY=your_actual_secret_here
R2_ACCOUNT_ID=your_account_id
R2_PUBLIC_BUCKET=your-bucket-name
R2_ENDPOINT=https://account_id.r2.cloudflarestorage.com
R2_PUBLIC_URL=https://pub-xxxxx.r2.dev
```

**Regenerate API Token:**
1. Cloudflare Dashboard > R2
2. Manage R2 API Tokens
3. Create new API token with R2 Read & Write permissions
4. Update `.env` with new credentials

---

### Issue 4: File Not Uploaded to R2

**Symptoms:**
- No error shown
- URL generated
- File doesn't exist in R2 bucket

**Debug:**
1. Check error log for upload result
2. Verify AWS SDK is installed:
```bash
composer show aws/aws-sdk-php
```
3. Test with `debug_upload.php`

**Fix:**
```bash
# Reinstall AWS SDK if needed
composer require aws/aws-sdk-php
```

---

### Issue 5: Wrong URL Format

**Symptoms:**
- Database contains local path instead of R2 URL
- Image path looks like: `uploads/classes/file.jpg`

**Check:**
```sql
SELECT id, name, image FROM classes ORDER BY id DESC LIMIT 5;
```

**Expected (R2 Mode):**
```
image: https://pub-xxxxx.r2.dev/classes/1234567890_abc.jpg
```

**If wrong (local path):**
- R2_ENABLED might be false
- Check configuration: `test_r2_connection.php`

---

## 🧪 Testing Checklist

Run these tests in order:

- [ ] **Step 1:** Open `test_r2_connection.php` - All checks pass ✅
- [ ] **Step 2:** Open `debug_upload.php` - Test upload works ✅
- [ ] **Step 3:** Check error logs - No errors ✅
- [ ] **Step 4:** Try upload via admin panel - Works ✅
- [ ] **Step 5:** Verify image displays in list - Shows correctly ✅
- [ ] **Step 6:** Check database - Contains R2 URL ✅
- [ ] **Step 7:** Access image URL directly - Loads in browser ✅

---

## 📊 Debug Output Example

### Good Upload (Working):

```
=== storeClass() called ===
POST data: Array ( [name] => Test Class ... )
FILES data: Array ( [image] => Array ( [name] => test.jpg ... ) )
Image file detected, starting upload process...
File type validation passed
File size validation passed
Calling UploadManager::upload() for folder: classes
Upload result: Array ( [success] => 1 [path] => https://pub-xxx.r2.dev/classes/123.jpg [message] => File uploaded to R2 successfully )
Upload successful, path: https://pub-xxx.r2.dev/classes/123.jpg
Inserting into database with image path: https://pub-xxx.r2.dev/classes/123.jpg
Class inserted successfully with ID: 42
```

### Bad Upload (Error):

```
=== storeClass() called ===
...
Image file detected, starting upload process...
File type validation passed
File size validation passed
Calling UploadManager::upload() for folder: classes
Upload result: Array ( [success] => 0 [path] => [message] => R2 upload failed: ... )
Upload failed: R2 upload failed: ...
Exception in storeClass(): R2 upload failed: ...
```

---

## 🔧 Quick Fixes

### Fix 1: Reset to Local Mode (Temporary)

If R2 not working, temporary switch to local:

```env
# .env
R2_ENABLED=false
```

Files will upload to `public/uploads/` instead.

### Fix 2: Clear Cache

```bash
# Clear PHP opcache (if enabled)
# Restart web server

# Windows XAMPP/WAMP
# Restart Apache service
```

### Fix 3: Check File Permissions

```bash
# Windows PowerShell
icacls "public\uploads" /grant Users:F /T

# Linux/Mac
chmod -R 755 public/uploads
```

---

## 📝 Logging Commands

### Enable Verbose Logging

Add to `config/config.php`:
```php
// Debug mode
define('DEBUG_MODE', true);
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../storage/logs/error.log');
```

### View Logs in Real-time

```bash
# Windows PowerShell
Get-Content storage\logs\error.log -Wait -Tail 20

# Linux/Mac
tail -f storage/logs/error.log
```

---

## 🎯 Expected Behavior

### Upload Flow (R2 Mode):

1. User selects image in form
2. Form submits via AJAX to `/admin/activities/classes/store`
3. Server validates file (type, size)
4. UploadManager uploads to R2
5. R2 returns public URL: `https://pub-xxx.r2.dev/classes/file.jpg`
6. URL saved to database
7. Response sent to client: `{success: true, image_url: "https://..."}`
8. Page refreshes, image displays using R2 URL

### Display Flow:

1. View reads database: `image` column
2. If starts with `http`: use as-is (R2 URL)
3. If starts with `uploads/`: convert to local URL
4. HTML: `<img src="{image_url}">`

---

## 🆘 Still Not Working?

If all tests pass but upload still fails:

1. **Check Browser Console:**
   - Open DevTools (F12)
   - Look for JavaScript errors
   - Check Network tab for failed requests

2. **Check Server Response:**
   - Network tab > Find `/admin/activities/classes/store` request
   - Click on it > Response tab
   - Check JSON response

3. **Verify Database:**
   ```sql
   SELECT * FROM classes WHERE id = (SELECT MAX(id) FROM classes);
   ```
   - Check if `image` field contains R2 URL

4. **Test Image URL Directly:**
   - Copy image URL from database
   - Paste in new browser tab
   - Should load the image

5. **Contact Support:**
   - Provide error logs
   - Provide debug output from `debug_upload.php`
   - Provide browser console errors

---

## 📚 Files Created for Debugging

1. **test_r2_connection.php** - Test R2 configuration and connectivity
2. **debug_upload.php** - Comprehensive upload test with detailed output
3. **TROUBLESHOOTING_UPLOAD.md** - This file

---

**Last Updated:** December 27, 2025  
**Version:** 1.0
