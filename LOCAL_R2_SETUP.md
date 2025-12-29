# Local R2 Development Setup

## Problem
When accessing Cloudflare R2 images directly from local development (localhost), browsers block the requests with `ERR_CERT_COMMON_NAME_INVALID` due to SSL certificate validation issues.

## Solution
We've implemented a **dual-mode system** that automatically detects the environment and serves images appropriately:

### Architecture

```
┌─────────────────────────────────────────────────────┐
│                                                     │
│   Local Development (localhost)                    │
│                                                     │
│   Browser Request                                  │
│        │                                           │
│        ▼                                           │
│   image_url() Helper                              │
│        │                                           │
│        ├──> Detects: APP_ENV=local                │
│        │                                           │
│        ▼                                           │
│   proxy_r2_image.php                              │
│        │                                           │
│        ├──> cURL with SSL_VERIFYPEER=false        │
│        │                                           │
│        ▼                                           │
│   Cloudflare R2 (zivana-public)                   │
│        │                                           │
│        ▼                                           │
│   Image displayed successfully                     │
│                                                     │
└─────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────┐
│                                                     │
│   Production                                       │
│                                                     │
│   Browser Request                                  │
│        │                                           │
│        ▼                                           │
│   image_url() Helper                              │
│        │                                           │
│        ├──> Detects: APP_ENV=production           │
│        │                                           │
│        ▼                                           │
│   Direct R2 URL                                   │
│        │                                           │
│        ▼                                           │
│   Cloudflare R2 (zivana-public)                   │
│        │                                           │
│        ▼                                           │
│   Image displayed successfully                     │
│                                                     │
└─────────────────────────────────────────────────────┘
```

## Implementation Details

### 1. Helper Function (`app/helpers/functions.php`)

```php
function image_url($imagePath, $useProxy = null) {
    if (empty($imagePath)) {
        return '';
    }
    
    // Check if it's already a full URL (R2)
    if (strpos($imagePath, 'http://') === 0 || strpos($imagePath, 'https://') === 0) {
        // Auto-detect if we should use proxy
        if ($useProxy === null) {
            $useProxy = (APP_ENV === 'local' || strpos(APP_URL, 'localhost') !== false) && R2_ENABLED;
        }
        
        if ($useProxy) {
            // Use proxy to bypass SSL issues in local development
            return url('proxy_r2_image.php?url=' . urlencode($imagePath));
        }
        
        // Return R2 URL as-is (production)
        return $imagePath;
    }
    
    // Local path - convert to URL
    if (strpos($imagePath, 'uploads/') === 0) {
        return url($imagePath);
    }
    
    return url('uploads/' . ltrim($imagePath, '/'));
}
```

### 2. Proxy Script (`public/proxy_r2_image.php`)

```php
<?php
// Validate R2 URL
$url = $_GET['url'] ?? '';
if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    exit('Invalid URL');
}

// Only allow R2 domains
$allowedDomains = ['r2.dev'];
$parsed = parse_url($url);
$isAllowed = false;
foreach ($allowedDomains as $domain) {
    if (strpos($parsed['host'], $domain) !== false) {
        $isAllowed = true;
        break;
    }
}

if (!$isAllowed) {
    http_response_code(403);
    exit('Domain not allowed');
}

// Fetch image with SSL verification disabled
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Bypass SSL for local
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$imageData = curl_exec($ch);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    http_response_code($httpCode);
    exit('Failed to fetch image');
}

// Output image
header('Content-Type: ' . $contentType);
header('Cache-Control: public, max-age=86400'); // Cache for 1 day
echo $imageData;
```

### 3. View Updates

All image src attributes in views now use the `image_url()` helper:

**Before:**
```php
<img src="<?= e($kelas['image']) ?>" alt="..." />
```

**After:**
```php
<img src="<?= image_url($kelas['image']) ?>" alt="..." />
```

## Configuration

### `.env` Settings

```env
# Enable R2 uploads
R2_ENABLED=true

# R2 Configuration
R2_PUBLIC_BUCKET=zivana-public
R2_PUBLIC_URL=https://pub-445331fec5ba4ec3af5c54ddc3ec1f4b.r2.dev
R2_ACCESS_KEY_ID=your_key_id
R2_SECRET_ACCESS_KEY=your_secret_key
R2_ENDPOINT=https://445331fec5ba4ec3af5c54ddc3ec1f4b.r2.cloudflarestorage.com

# Environment (auto-detected)
APP_ENV=local  # or production
APP_URL=http://localhost:8080
```

## Files Modified

### Core Files
1. **`app/helpers/functions.php`** - Added `image_url()` helper
2. **`public/proxy_r2_image.php`** - NEW: Proxy script for local SSL bypass
3. **`app/views/admin/activities/index.php`** - Updated all image src attributes

### Upload System (Already Implemented)
1. **`app/helpers/UploadManager.php`** - Centralized upload handler
2. **`app/helpers/CloudflareR2.php`** - R2 client wrapper
3. **`app/controllers/ActivityController.php`** - Uses UploadManager
4. **`app/controllers/SettingsController.php`** - Uses UploadManager

## How It Works

### Upload Flow
1. User uploads file through form
2. `UploadManager::upload()` detects `R2_ENABLED=true`
3. File uploaded to R2 bucket `zivana-public`
4. Returns R2 public URL: `https://pub-xxx.r2.dev/classes/image.jpg`
5. URL saved to database

### Display Flow (Local Development)
1. View calls `image_url($dbImagePath)`
2. Helper detects:
   - Path is R2 URL (starts with https://)
   - `APP_ENV=local` or `APP_URL` contains `localhost`
   - `R2_ENABLED=true`
3. Returns: `http://localhost:8080/proxy_r2_image.php?url=https://pub-xxx.r2.dev/classes/image.jpg`
4. Browser requests proxy
5. Proxy fetches from R2 with SSL verification disabled
6. Image displayed successfully

### Display Flow (Production)
1. View calls `image_url($dbImagePath)`
2. Helper detects:
   - Path is R2 URL
   - `APP_ENV=production`
3. Returns R2 URL directly: `https://pub-xxx.r2.dev/classes/image.jpg`
4. Browser requests R2 directly
5. Image displayed successfully (SSL valid in production)

## Testing

### Test Upload in Local
1. Go to `/admin/activities`
2. Click "Informasi Kelas" tab
3. Click "Tambah Kelas"
4. Upload an image
5. Submit form
6. Check:
   - ✅ Network tab shows upload to `/activities/store-class`
   - ✅ Response contains R2 URL: `https://pub-xxx.r2.dev/classes/xxx.jpg`
   - ✅ Image displays via proxy: `proxy_r2_image.php?url=...`
   - ✅ No SSL errors in console

### Test Display in Local
1. Refresh the page
2. Check:
   - ✅ All class images display correctly
   - ✅ Inspect element shows src: `proxy_r2_image.php?url=...`
   - ✅ No console errors
   - ✅ Images load fast (cached after first load)

### Test in Production (After Deploy)
1. Deploy to production server
2. Go to `/admin/activities`
3. Check:
   - ✅ All images display correctly
   - ✅ Inspect element shows direct R2 URLs
   - ✅ No proxy used
   - ✅ SSL certificates valid

## Benefits

✅ **Seamless Local Development** - No SSL certificate errors in local
✅ **Optimal Production Performance** - Direct R2 access, no proxy overhead
✅ **Automatic Detection** - No manual configuration needed
✅ **Centralized Logic** - Single helper function for all image URLs
✅ **Cache Support** - Proxy caches images for 1 day
✅ **Security** - Proxy validates R2 domains only
✅ **Backward Compatible** - Works with local uploads too

## Troubleshooting

### Images not displaying in local
1. Check `.env`: `R2_ENABLED=true`
2. Check `APP_ENV=local` or `APP_URL` contains `localhost`
3. Test proxy directly: `http://localhost:8080/proxy_r2_image.php?url=https://pub-xxx.r2.dev/test.jpg`
4. Check browser console for errors
5. Check PHP error logs

### Images not displaying in production
1. Check R2 bucket permissions (public read)
2. Check R2 credentials in `.env`
3. Check network tab for failed requests
4. Verify R2_PUBLIC_URL is correct

### Upload fails
1. Check R2 credentials
2. Check bucket name: `zivana-public`
3. Check `UploadManager` logs
4. Check `ActivityController` verbose logs
5. Test with debug tool: `public/debug_upload.php`

## Next Steps

- [ ] Test upload in "Informasi Kelas" tab
- [ ] Verify images display in local via proxy
- [ ] Test Program Tahun Ajaran gallery images
- [ ] Test Program Harian gallery images
- [ ] Deploy to production
- [ ] Verify direct R2 access in production
- [ ] Monitor performance and cache hit rate
