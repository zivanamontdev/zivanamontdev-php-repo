<?php
/**
 * Upload Manager
 * 
 * Global helper untuk manage file upload ke local storage atau Cloudflare R2
 * Automatically switch berdasarkan R2_ENABLED config
 * 
 * @author Zivana Montessori Dev Team
 * @version 1.0
 */

class UploadManager {
    
    private static $r2Client = null;
    
    /**
     * Initialize R2 client jika belum ada
     */
    private static function initR2() {
        if (self::$r2Client === null && R2_ENABLED) {
            require_once APP_PATH . '/helpers/CloudflareR2.php';
            self::$r2Client = new CloudflareR2();
        }
    }
    
    /**
     * Upload file (auto detect: R2 or Local)
     * 
     * @param array $file - Data dari $_FILES['field_name']
     * @param string $folder - Folder tujuan (articles, awards, employees, dll)
     * @param string $oldFile - Path file lama untuk dihapus (optional)
     * @return array ['success' => bool, 'path' => string, 'message' => string]
     */
    public static function upload($file, $folder, $oldFile = null) {
        // Delete old file first if exists
        if ($oldFile) {
            self::delete($oldFile);
        }
        
        // Check if R2 is enabled
        if (R2_ENABLED) {
            return self::uploadToR2($file, $folder);
        } else {
            return self::uploadToLocal($file, $folder);
        }
    }
    
    /**
     * Upload ke Cloudflare R2
     */
    private static function uploadToR2($file, $folder) {
        try {
            self::initR2();
            
            // Validate file upload
            if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
                return [
                    'success' => false,
                    'path' => null,
                    'message' => 'File upload error: ' . ($file['error'] ?? 'Unknown error')
                ];
            }
            
            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
            
            // Upload to R2 public bucket
            $result = self::$r2Client->uploadFromRequestPublic(
                $file,
                $folder,
                $filename
            );
            
            if ($result['success']) {
                return [
                    'success' => true,
                    'path' => $result['url'], // Return full URL for R2
                    'message' => 'File uploaded to R2 successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'path' => null,
                    'message' => $result['message']
                ];
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'path' => null,
                'message' => 'R2 upload failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Upload ke Local Storage
     */
    private static function uploadToLocal($file, $folder) {
        try {
            // Validate file upload
            if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
                return [
                    'success' => false,
                    'path' => null,
                    'message' => 'File upload error'
                ];
            }
            
            // Create folder if not exists
            $uploadDir = UPLOAD_PATH . '/' . $folder;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
            $targetPath = $uploadDir . '/' . $filename;
            
            // Upload file
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                return [
                    'success' => true,
                    'path' => 'uploads/' . $folder . '/' . $filename, // Return relative path for local
                    'message' => 'File uploaded to local storage successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'path' => null,
                    'message' => 'Failed to move uploaded file'
                ];
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'path' => null,
                'message' => 'Local upload failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Delete file (auto detect: R2 or Local)
     * 
     * @param string $filePath - Path file (URL untuk R2, relative path untuk local)
     * @return bool
     */
    public static function delete($filePath) {
        if (empty($filePath)) {
            return false;
        }
        
        // Check if it's R2 URL
        if (self::isR2Url($filePath)) {
            return self::deleteFromR2($filePath);
        } else {
            return self::deleteFromLocal($filePath);
        }
    }
    
    /**
     * Check if path is R2 URL
     */
    private static function isR2Url($path) {
        return (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0);
    }
    
    /**
     * Delete dari R2
     */
    private static function deleteFromR2($url) {
        try {
            self::initR2();
            
            // Extract key from URL
            // URL format: https://pub-xxx.r2.dev/folder/filename.jpg
            $parsedUrl = parse_url($url);
            if (!isset($parsedUrl['path'])) {
                return false;
            }
            
            $key = ltrim($parsedUrl['path'], '/');
            
            // Delete from R2
            $result = self::$r2Client->deletePublic($key);
            return $result['success'] ?? false;
            
        } catch (Exception $e) {
            error_log('Failed to delete from R2: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete dari Local Storage
     */
    private static function deleteFromLocal($path) {
        try {
            // Remove 'uploads/' prefix if exists
            $path = str_replace('uploads/', '', $path);
            $fullPath = UPLOAD_PATH . '/' . $path;
            
            if (file_exists($fullPath)) {
                return unlink($fullPath);
            }
            
            return false;
        } catch (Exception $e) {
            error_log('Failed to delete from local: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get full URL dari path
     * Untuk local storage, convert relative path ke full URL
     * Untuk R2, return as is
     * 
     * @param string $path - Path file
     * @return string - Full URL
     */
    public static function getUrl($path) {
        if (empty($path)) {
            return '';
        }
        
        // Already full URL (R2)
        if (self::isR2Url($path)) {
            return $path;
        }
        
        // Local path, convert to URL
        if (strpos($path, 'uploads/') === 0) {
            return url($path);
        }
        
        return url('uploads/' . $path);
    }
    
    /**
     * Validate file type
     * 
     * @param array $file - Data dari $_FILES
     * @param array $allowedTypes - Array of allowed extensions (e.g., ['jpg', 'png'])
     * @return array ['success' => bool, 'message' => string]
     */
    public static function validateFileType($file, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp']) {
        if (!isset($file['name'])) {
            return ['success' => false, 'message' => 'No file uploaded'];
        }
        
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($extension, $allowedTypes)) {
            return [
                'success' => false, 
                'message' => 'Invalid file type. Allowed: ' . implode(', ', $allowedTypes)
            ];
        }
        
        return ['success' => true, 'message' => 'Valid file type'];
    }
    
    /**
     * Validate file size
     * 
     * @param array $file - Data dari $_FILES
     * @param int $maxSize - Maximum size in bytes (default 5MB)
     * @return array ['success' => bool, 'message' => string]
     */
    public static function validateFileSize($file, $maxSize = null) {
        if ($maxSize === null) {
            $maxSize = defined('MAX_UPLOAD_SIZE') ? MAX_UPLOAD_SIZE : 5242880; // 5MB default
        }
        
        if (!isset($file['size'])) {
            return ['success' => false, 'message' => 'Cannot determine file size'];
        }
        
        if ($file['size'] > $maxSize) {
            $maxSizeMB = round($maxSize / 1048576, 2);
            return [
                'success' => false, 
                'message' => "File too large. Maximum: {$maxSizeMB}MB"
            ];
        }
        
        return ['success' => true, 'message' => 'Valid file size'];
    }
}
