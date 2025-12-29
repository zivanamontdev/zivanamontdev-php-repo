<?php
// Test R2 move/swap functionality
require_once '../config/config.php';
require_once ROOT_PATH . '/app/helpers/CloudflareR2.php';

header('Content-Type: application/json');

$r2 = new CloudflareR2();

// Test parameters
$testSource = $_GET['source'] ?? null;
$testDest = $_GET['dest'] ?? null;
$action = $_GET['action'] ?? 'list';

if ($action === 'list') {
    // List files in a specific folder
    $folder = $_GET['folder'] ?? '';
    
    try {
        $s3Client = $r2->getPublicClient();
        $result = $s3Client->listObjects([
            'Bucket' => R2_PUBLIC_BUCKET,
            'Prefix' => $folder,
            'MaxKeys' => 100
        ]);
        
        $files = [];
        if (isset($result['Contents'])) {
            foreach ($result['Contents'] as $object) {
                $files[] = [
                    'key' => $object['Key'],
                    'size' => $object['Size'],
                    'lastModified' => $object['LastModified']->format('Y-m-d H:i:s')
                ];
            }
        }
        
        echo json_encode([
            'success' => true,
            'folder' => $folder,
            'count' => count($files),
            'files' => $files
        ], JSON_PRETTY_PRINT);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ], JSON_PRETTY_PRINT);
    }
    
} elseif ($action === 'move') {
    // Test move operation
    if (!$testSource || !$testDest) {
        echo json_encode([
            'success' => false,
            'error' => 'Missing source or dest parameter'
        ], JSON_PRETTY_PRINT);
        exit;
    }
    
    try {
        error_log("Testing R2 Move: {$testSource} -> {$testDest}");
        
        // Check if source exists
        if (!$r2->existsPublic($testSource)) {
            echo json_encode([
                'success' => false,
                'error' => "Source file does not exist: {$testSource}"
            ], JSON_PRETTY_PRINT);
            exit;
        }
        
        // Perform move
        $result = $r2->moveObject(R2_PUBLIC_BUCKET, $testSource, R2_PUBLIC_BUCKET, $testDest);
        
        if ($result) {
            error_log("Move successful: {$testSource} -> {$testDest}");
            
            // Verify
            $sourceExists = $r2->existsPublic($testSource);
            $destExists = $r2->existsPublic($testDest);
            
            echo json_encode([
                'success' => true,
                'message' => 'Move operation completed',
                'verification' => [
                    'sourceExists' => $sourceExists,  // Should be false
                    'destExists' => $destExists       // Should be true
                ]
            ], JSON_PRETTY_PRINT);
        } else {
            error_log("Move failed: {$testSource} -> {$testDest}");
            echo json_encode([
                'success' => false,
                'error' => 'Move operation failed'
            ], JSON_PRETTY_PRINT);
        }
        
    } catch (Exception $e) {
        error_log("Move exception: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], JSON_PRETTY_PRINT);
    }
    
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid action',
        'usage' => [
            'list' => '?action=list&folder=programs_tahun/1-test',
            'move' => '?action=move&source=programs_tahun/1-test/cover.jpg&dest=programs_tahun/1-test/gallery/gallery-123.jpg'
        ]
    ], JSON_PRETTY_PRINT);
}
