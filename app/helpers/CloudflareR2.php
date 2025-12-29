<?php

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

/**
 * Cloudflare R2 Storage Helper
 * 
 * Helper untuk upload dan manage file ke Cloudflare R2 Storage
 * Support dual bucket: Public & Private
 * R2 compatible dengan S3 API
 * 
 * @author Zivana Montessori Dev Team
 * @version 2.0
 */
class CloudflareR2 {
    
    private $publicClient;
    private $privateClient;
    private $publicBucket;
    private $privateBucket;
    private $publicUrl;
    
    /**
     * Initialize Cloudflare R2 Client
     * Auto-detect dari config constants
     */
    public function __construct() {
        if (!R2_ENABLED) {
            throw new Exception('Cloudflare R2 is not enabled. Set R2_ENABLED=true in .env');
        }
        
        if (empty(R2_ACCESS_KEY_ID) || empty(R2_SECRET_ACCESS_KEY)) {
            throw new Exception('R2 credentials not configured. Check .env file');
        }
        
        $this->publicBucket = R2_PUBLIC_BUCKET;
        $this->privateBucket = R2_PRIVATE_BUCKET;
        $this->publicUrl = rtrim(R2_PUBLIC_URL, '/');
        
        // Client configuration
        $clientConfig = [
            'version' => 'latest',
            'region' => 'auto',
            'endpoint' => R2_ENDPOINT,
            'credentials' => [
                'key' => R2_ACCESS_KEY_ID,
                'secret' => R2_SECRET_ACCESS_KEY,
            ],
            'use_path_style_endpoint' => false,
        ];
        
        // Disable SSL verification in development (fix cURL error 60)
        if (defined('APP_ENV') && APP_ENV === 'local') {
            $clientConfig['http'] = [
                'verify' => false
            ];
        }
        
        // Initialize Public Bucket Client
        $this->publicClient = new S3Client($clientConfig);
        
        // Initialize Private Bucket Client (same credentials, different bucket)
        $this->privateClient = new S3Client($clientConfig);
    }
    
    /**
     * Upload file ke R2 Public Bucket
     * 
     * @param string $localFilePath - Path file lokal yang akan diupload
     * @param string $remoteFilePath - Path tujuan di R2 (contoh: 'articles/image.jpg')
     * @param string $contentType - MIME type (optional, auto-detect jika tidak diisi)
     * @return array ['success' => bool, 'url' => string, 'message' => string]
     */
    public function uploadPublic($localFilePath, $remoteFilePath, $contentType = null) {
        return $this->upload($this->publicClient, $this->publicBucket, $localFilePath, $remoteFilePath, $contentType, true);
    }
    
    /**
     * Upload file ke R2 Private Bucket
     * 
     * @param string $localFilePath - Path file lokal yang akan diupload
     * @param string $remoteFilePath - Path tujuan di R2 (contoh: 'documents/report.pdf')
     * @param string $contentType - MIME type (optional, auto-detect jika tidak diisi)
     * @return array ['success' => bool, 'key' => string, 'message' => string]
     */
    public function uploadPrivate($localFilePath, $remoteFilePath, $contentType = null) {
        return $this->upload($this->privateClient, $this->privateBucket, $localFilePath, $remoteFilePath, $contentType, false);
    }
    
    /**
     * Upload file ke R2 (generic method)
     */
    private function upload($client, $bucket, $localFilePath, $remoteFilePath, $contentType = null, $isPublic = true) {
        try {
            // Validate file exists
            if (!file_exists($localFilePath)) {
                return [
                    'success' => false,
                    'url' => null,
                    'message' => 'Local file not found: ' . $localFilePath
                ];
            }
            
            // Auto-detect content type jika tidak diisi
            if ($contentType === null) {
                $contentType = mime_content_type($localFilePath) ?: 'application/octet-stream';
            }
            
            // Upload file
            $result = $client->putObject([
                'Bucket' => $bucket,
                'Key' => $remoteFilePath,
                'SourceFile' => $localFilePath,
                'ContentType' => $contentType,
                'CacheControl' => 'public, max-age=31536000', // Cache 1 tahun
            ]);
            
            // Generate URL
            $url = $isPublic ? $this->getPublicUrl($remoteFilePath) : null;
            
            return [
                'success' => true,
                'url' => $url,
                'key' => $remoteFilePath,
                'bucket' => $bucket,
                'etag' => $result['ETag'] ?? null,
                'message' => 'File uploaded successfully'
            ];
            
        } catch (AwsException $e) {
            return [
                'success' => false,
                'url' => null,
                'message' => 'Upload failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Upload dari uploaded file ($_FILES) ke Public Bucket
     * 
     * @param array $file - Data dari $_FILES['field_name']
     * @param string $destinationPath - Path tujuan di R2 (contoh: 'articles/')
     * @param string $newFileName - Nama file baru (optional, gunakan nama asli jika kosong)
     * @return array ['success' => bool, 'url' => string, 'message' => string]
     */
    public function uploadFromRequestPublic($file, $destinationPath, $newFileName = null) {
        return $this->uploadFromRequest($file, $destinationPath, $newFileName, true);
    }
    
    /**
     * Upload dari uploaded file ($_FILES) ke Private Bucket
     * 
     * @param array $file - Data dari $_FILES['field_name']
     * @param string $destinationPath - Path tujuan di R2 (contoh: 'documents/')
     * @param string $newFileName - Nama file baru (optional, gunakan nama asli jika kosong)
     * @return array ['success' => bool, 'key' => string, 'message' => string]
     */
    public function uploadFromRequestPrivate($file, $destinationPath, $newFileName = null) {
        return $this->uploadFromRequest($file, $destinationPath, $newFileName, false);
    }
    
    /**
     * Upload dari uploaded file (generic method)
     */
    private function uploadFromRequest($file, $destinationPath, $newFileName = null, $isPublic = true) {
        // Validasi upload
        if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return [
                'success' => false,
                'url' => null,
                'message' => 'Upload error: ' . ($file['error'] ?? 'Unknown error')
            ];
        }
        
        // Generate nama file
        if ($newFileName === null) {
            $newFileName = basename($file['name']);
        }
        
        // Sanitize filename
        $newFileName = $this->sanitizeFilename($newFileName);
        
        // Path lengkap di R2
        $remotePath = rtrim($destinationPath, '/') . '/' . $newFileName;
        
        // Upload
        if ($isPublic) {
            return $this->uploadPublic($file['tmp_name'], $remotePath, $file['type']);
        } else {
            return $this->uploadPrivate($file['tmp_name'], $remotePath, $file['type']);
        }
    }
    
    /**
     * Delete file dari Public Bucket
     * 
     * @param string $filePath - Path file di R2
     * @return array ['success' => bool, 'message' => string]
     */
    public function deletePublic($filePath) {
        return $this->delete($this->publicClient, $this->publicBucket, $filePath);
    }
    
    /**
     * Delete file dari Private Bucket
     * 
     * @param string $filePath - Path file di R2
     * @return array ['success' => bool, 'message' => string]
     */
    public function deletePrivate($filePath) {
        return $this->delete($this->privateClient, $this->privateBucket, $filePath);
    }
    
    /**
     * Delete file dari R2 (generic method)
     */
    private function delete($client, $bucket, $filePath) {
        try {
            $client->deleteObject([
                'Bucket' => $bucket,
                'Key' => $filePath,
            ]);
            
            return [
                'success' => true,
                'message' => 'File deleted successfully'
            ];
            
        } catch (AwsException $e) {
            return [
                'success' => false,
                'message' => 'Delete failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Check apakah file exists di Public Bucket
     * 
     * @param string $filePath - Path file di R2
     * @return bool
     */
    public function existsPublic($filePath) {
        return $this->exists($this->publicClient, $this->publicBucket, $filePath);
    }
    
    /**
     * Check apakah file exists di Private Bucket
     * 
     * @param string $filePath - Path file di R2
     * @return bool
     */
    public function existsPrivate($filePath) {
        return $this->exists($this->privateClient, $this->privateBucket, $filePath);
    }
    
    /**
     * Check apakah file exists (generic method)
     */
    private function exists($client, $bucket, $filePath) {
        try {
            $client->headObject([
                'Bucket' => $bucket,
                'Key' => $filePath,
            ]);
            return true;
        } catch (AwsException $e) {
            return false;
        }
    }
    
    /**
     * Get public URL untuk file di Public Bucket
     * 
     * @param string $filePath - Path file di R2
     * @return string - Public URL
     */
    public function getPublicUrl($filePath) {
        return $this->publicUrl . '/' . ltrim($filePath, '/');
    }
    
    /**
     * Generate temporary signed URL untuk Private Bucket
     * 
     * @param string $filePath - Path file di R2
     * @param string $expiration - Expiration time (default: '+1 hour')
     * @return string|null - Signed URL atau null jika gagal
     */
    public function getPrivateUrl($filePath, $expiration = '+1 hour') {
        try {
            $cmd = $this->privateClient->getCommand('GetObject', [
                'Bucket' => $this->privateBucket,
                'Key' => $filePath,
            ]);
            
            $request = $this->privateClient->createPresignedRequest($cmd, $expiration);
            
            return (string) $request->getUri();
        } catch (AwsException $e) {
            return null;
        }
    }
    
    /**
     * List files di Public Bucket
     * 
     * @param string $prefix - Prefix/folder path (contoh: 'articles/')
     * @param int $maxResults - Maximum hasil yang dikembalikan
     * @return array - List of files
     */
    public function listPublic($prefix = '', $maxResults = 1000) {
        return $this->listFiles($this->publicClient, $this->publicBucket, $prefix, $maxResults, true);
    }
    
    /**
     * List files di Private Bucket
     * 
     * @param string $prefix - Prefix/folder path (contoh: 'documents/')
     * @param int $maxResults - Maximum hasil yang dikembalikan
     * @return array - List of files
     */
    public function listPrivate($prefix = '', $maxResults = 1000) {
        return $this->listFiles($this->privateClient, $this->privateBucket, $prefix, $maxResults, false);
    }
    
    /**
     * List files di folder tertentu (generic method)
     */
    private function listFiles($client, $bucket, $prefix = '', $maxResults = 1000, $isPublic = true) {
        try {
            $result = $client->listObjectsV2([
                'Bucket' => $bucket,
                'Prefix' => $prefix,
                'MaxKeys' => $maxResults,
            ]);
            
            $files = [];
            if (isset($result['Contents'])) {
                foreach ($result['Contents'] as $object) {
                    $fileInfo = [
                        'key' => $object['Key'],
                        'size' => $object['Size'],
                        'size_formatted' => self::formatFileSize($object['Size']),
                        'last_modified' => $object['LastModified'],
                    ];
                    
                    if ($isPublic) {
                        $fileInfo['url'] = $this->getPublicUrl($object['Key']);
                    }
                    
                    $files[] = $fileInfo;
                }
            }
            
            return [
                'success' => true,
                'files' => $files,
                'count' => count($files),
                'bucket' => $bucket
            ];
            
        } catch (AwsException $e) {
            return [
                'success' => false,
                'message' => 'List failed: ' . $e->getMessage(),
                'files' => [],
                'count' => 0
            ];
        }
    }
    
    /**
     * Sanitize filename untuk keamanan
     * 
     * @param string $filename
     * @return string
     */
    private function sanitizeFilename($filename) {
        // Get extension
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $basename = pathinfo($filename, PATHINFO_FILENAME);
        
        // Remove special characters, keep alphanumeric, dash, and underscore
        $basename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $basename);
        
        // Add timestamp untuk uniqueness
        $basename = $basename . '_' . time();
        
        return $basename . '.' . $extension;
    }
    
    /**
     * Get file size in human readable format
     * 
     * @param int $bytes
     * @return string
     */
    public static function formatFileSize($bytes) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
