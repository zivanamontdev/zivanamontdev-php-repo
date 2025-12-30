<?php
require_once APP_PATH . '/helpers/UploadManager.php';

class ActivityController extends Controller {
    
    public function index() {
        // Fetch classes from database
        $db = Database::getInstance();
        $classes = $db->query("SELECT * FROM classes WHERE is_active = 1 ORDER BY display_order ASC, created_at ASC")->fetchAll();
        
        // Transform data for view
        $kelasData = array_map(function($class) {
            return [
                'id' => $class['id'],
                'image' => $class['image'],
                'title' => $class['name'],
                'age' => $class['age_range'],
                'duration' => $class['duration'],
                'students' => $class['max_students']
            ];
        }, $classes);
        
        // Fetch programs tahun ajaran from database
        $programsTahun = $db->query("SELECT * FROM programs_tahun WHERE is_active = 1 ORDER BY display_order ASC, created_at ASC")->fetchAll();
        
        // Transform data for view with gallery images
        $programsTahunData = array_map(function($program) use ($db) {
            // Fetch gallery images for this program
            $galleryImages = $db->query(
                "SELECT * FROM program_gallery WHERE program_id = ? ORDER BY display_order ASC", 
                [$program['id']]
            )->fetchAll();
            
            // Determine which image is the cover (sampul)
            // Priority: gallery image with is_cover=1, fallback to program.image
            $coverImage = null;
            $coverFromGallery = false;
            
            foreach ($galleryImages as $galleryImg) {
                if ($galleryImg['is_cover'] == 1) {
                    $coverImage = $galleryImg['image_path'];
                    $coverFromGallery = true;
                    break;
                }
            }
            
            // If no gallery cover, use program image as cover
            if (!$coverImage && !empty($program['image'])) {
                $coverImage = $program['image'];
            }
            
            return [
                'id' => $program['id'],
                'name' => $program['name'],
                'description' => $program['description'],
                'image' => $program['image'],
                'cover_image' => $coverImage,
                'cover_from_gallery' => $coverFromGallery,
                'gallery' => $galleryImages
            ];
        }, $programsTahun);
        
        // Fetch programs harian from database (5 days) - one record per day_name
        $programsHarian = $db->query("
            SELECT * FROM programs_harian 
            WHERE id IN (
                SELECT MIN(id) 
                FROM programs_harian 
                WHERE is_active = 1 
                GROUP BY day_name
            )
            ORDER BY display_order ASC
        ")->fetchAll();
        
        // Transform data for view with gallery images
        $programsHarianData = array_map(function($program) use ($db) {
            // Fetch gallery images for this program
            $galleryImages = $db->query(
                "SELECT * FROM program_harian_gallery WHERE program_harian_id = ? ORDER BY display_order ASC", 
                [$program['id']]
            )->fetchAll();
            
            // Determine which image is the cover (sampul)
            // Priority: gallery image with is_cover=1, fallback to program.image
            $coverImage = null;
            $coverFromGallery = false;
            
            foreach ($galleryImages as $galleryImg) {
                if ($galleryImg['is_cover'] == 1) {
                    $coverImage = $galleryImg['image_path'];
                    $coverFromGallery = true;
                    break;
                }
            }
            
            // If no gallery cover, use program image as cover
            if (!$coverImage && !empty($program['image'])) {
                $coverImage = $program['image'];
            }
            
            return [
                'id' => $program['id'],
                'day_name' => $program['day_name'],
                'program_name' => $program['program_name'],
                'description' => $program['description'],
                'image' => $program['image'],
                'cover_image' => $coverImage,
                'cover_from_gallery' => $coverFromGallery,
                'gallery' => $galleryImages
            ];
        }, $programsHarian);
        
        require VIEW_PATH . '/admin/activities/index.php';
    }
    
    public function storeClass() {
        try {
            // Debug: Log request
            error_log('=== storeClass() called ===');
            error_log('POST data: ' . print_r($_POST, true));
            error_log('FILES data: ' . print_r($_FILES, true));
            
            // Validate input
            $name = $_POST['name'] ?? '';
            $age_range = trim($_POST['age_range'] ?? '');
            $duration = trim($_POST['duration'] ?? '');
            $max_students = trim($_POST['max_students'] ?? '');
            
            if (empty($name) || empty($age_range) || empty($duration) || empty($max_students)) {
                throw new Exception('All fields are required');
            }
            
            // Append suffixes for consistent display
            $age_range = $age_range . ' Tahun';
            $duration = $duration . ' jam';
            $max_students = $max_students . ' anak/kelas';
            
            // Handle image upload using UploadManager
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                error_log('Image file detected, starting upload process...');
                
                // Validate file type
                $validation = UploadManager::validateFileType($_FILES['image'], ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                if (!$validation['success']) {
                    error_log('File type validation failed: ' . $validation['message']);
                    throw new Exception($validation['message']);
                }
                error_log('File type validation passed');
                
                // Validate file size (max 5MB)
                $sizeValidation = UploadManager::validateFileSize($_FILES['image'], 5242880);
                if (!$sizeValidation['success']) {
                    error_log('File size validation failed: ' . $sizeValidation['message']);
                    throw new Exception($sizeValidation['message']);
                }
                error_log('File size validation passed');
                
                // Upload file
                error_log('Calling UploadManager::upload() for folder: classes');
                $uploadResult = UploadManager::upload($_FILES['image'], 'classes');
                error_log('Upload result: ' . print_r($uploadResult, true));
                
                if (!$uploadResult['success']) {
                    error_log('Upload failed: ' . $uploadResult['message']);
                    throw new Exception($uploadResult['message']);
                }
                
                $imagePath = $uploadResult['path'];
                error_log('Upload successful, path: ' . $imagePath);
            } else {
                error_log('No image file detected or upload error: ' . ($_FILES['image']['error'] ?? 'N/A'));
            }
            
            // Get max display order
            $db = Database::getInstance();
            $maxOrder = $db->query("SELECT MAX(display_order) as max_order FROM classes")->fetch();
            $displayOrder = ($maxOrder['max_order'] ?? 0) + 1;
            
            // Insert into database
            error_log('Inserting into database with image path: ' . ($imagePath ?? 'NULL'));
            $sql = "INSERT INTO classes (name, age_range, duration, max_students, image, display_order) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $db->query($sql, [$name, $age_range, $duration, $max_students, $imagePath, $displayOrder]);
            
            // Get inserted ID
            $insertedId = $db->getConnection()->lastInsertId();
            error_log('Class inserted successfully with ID: ' . $insertedId);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Class added successfully', 
                'id' => $insertedId,
                'image_path' => $imagePath,
                'image_url' => $imagePath ? UploadManager::getUrl($imagePath) : null
            ]);
            
        } catch (Exception $e) {
            error_log('Exception in storeClass(): ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => sanitize_error($e, 'Gagal menambah kelas. Silakan coba lagi.')]);
        }
    }
    
    public function updateClass($id) {
        try {
            // Validate input
            $name = $_POST['name'] ?? '';
            $age_range = trim($_POST['age_range'] ?? '');
            $duration = trim($_POST['duration'] ?? '');
            $max_students = trim($_POST['max_students'] ?? '');
            
            if (empty($name) || empty($age_range) || empty($duration) || empty($max_students)) {
                throw new Exception('All fields are required');
            }
            
            // Append suffixes for consistent display
            $age_range = $age_range . ' Tahun';
            $duration = $duration . ' jam';
            $max_students = $max_students . ' anak/kelas';
            
            $db = Database::getInstance();
            
            // Get existing class
            $existingClass = $db->query("SELECT * FROM classes WHERE id = ?", [$id])->fetch();
            if (!$existingClass) {
                throw new Exception('Class not found');
            }
            
            $imagePath = $existingClass['image'];
            
            // Handle image upload using UploadManager
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Validate file type
                $validation = UploadManager::validateFileType($_FILES['image'], ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                if (!$validation['success']) {
                    throw new Exception($validation['message']);
                }
                
                // Validate file size (max 5MB)
                $sizeValidation = UploadManager::validateFileSize($_FILES['image'], 5242880);
                if (!$sizeValidation['success']) {
                    throw new Exception($sizeValidation['message']);
                }
                
                // Upload file (will delete old file automatically)
                $uploadResult = UploadManager::upload($_FILES['image'], 'classes', $imagePath);
                if (!$uploadResult['success']) {
                    throw new Exception($uploadResult['message']);
                }
                
                $imagePath = $uploadResult['path'];
            }
            
            // Update database
            $sql = "UPDATE classes SET name = ?, age_range = ?, duration = ?, max_students = ?, image = ?, updated_at = NOW() 
                    WHERE id = ?";
            $db->query($sql, [$name, $age_range, $duration, $max_students, $imagePath, $id]);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Class updated successfully']);
            
        } catch (Exception $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => sanitize_error($e, 'Gagal mengubah kelas. Silakan coba lagi.')]);
        }
    }
    
    public function deleteClass($id) {
        try {
            $db = Database::getInstance();
            
            // Get existing class
            $existingClass = $db->query("SELECT * FROM classes WHERE id = ?", [$id])->fetch();
            if (!$existingClass) {
                throw new Exception('Class not found');
            }
            
            // Delete image file using UploadManager
            if ($existingClass['image']) {
                UploadManager::delete($existingClass['image']);
            }
            
            // Soft delete (set is_active to 0) or hard delete
            $sql = "UPDATE classes SET is_active = 0, updated_at = NOW() WHERE id = ?";
            // For hard delete: $sql = "DELETE FROM classes WHERE id = ?";
            $db->query($sql, [$id]);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Class deleted successfully']);
            
        } catch (Exception $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => sanitize_error($e, 'Gagal menghapus kelas. Silakan coba lagi.')]);
        }
    }
    
    // Program Tahun Ajaran methods
    public function storeProgramTahun() {
        try {
            // Validate input
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            
            if (empty($name) || empty($description)) {
                throw new Exception('All fields are required');
            }
            
            // Insert into database first to get program ID
            $db = Database::getInstance();
            $sql = "INSERT INTO programs_tahun (name, description, image, is_active, display_order, created_at, updated_at) 
                    VALUES (?, ?, NULL, 1, 0, NOW(), NOW())";
            $db->query($sql, [$name, $description]);
            
            // Get inserted ID
            $insertedId = $db->getConnection()->lastInsertId();
            
            // Handle image upload using UploadManager
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Validate file type
                $validation = UploadManager::validateFileType($_FILES['image'], ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                if (!$validation['success']) {
                    throw new Exception($validation['message']);
                }
                
                // Validate file size (max 5MB)
                $sizeValidation = UploadManager::validateFileSize($_FILES['image'], 5242880);
                if (!$sizeValidation['success']) {
                    throw new Exception($sizeValidation['message']);
                }
                
                // Create slug from name
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
                
                // Get file extension
                $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                
                // Upload to: programs_tahun/{id}-{slug}/cover.{ext}
                $folderPath = "programs_tahun/{$insertedId}-{$slug}";
                $uploadResult = UploadManager::upload($_FILES['image'], $folderPath, null, 'cover.' . $extension);
                if (!$uploadResult['success']) {
                    throw new Exception($uploadResult['message']);
                }
                
                $imagePath = $uploadResult['path'];
                
                // Update database with image path
                $db->query("UPDATE programs_tahun SET image = ? WHERE id = ?", [$imagePath, $insertedId]);
            }
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Program created successfully', 'id' => $insertedId]);
            
        } catch (Exception $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => sanitize_error($e, 'Gagal membuat program. Silakan coba lagi.')]);
        }
    }
    
    public function updateProgramTahun($id) {
        try {
            // Validate input
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            
            if (empty($name) || empty($description)) {
                throw new Exception('All fields are required');
            }
            
            $db = Database::getInstance();
            
            // Get existing program
            $existingProgram = $db->query("SELECT * FROM programs_tahun WHERE id = ?", [$id])->fetch();
            if (!$existingProgram) {
                throw new Exception('Program not found');
            }
            
            // Handle image upload
            $imagePath = $existingProgram['image']; // Keep existing image by default
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Validate file type
                $validation = UploadManager::validateFileType($_FILES['image'], ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                if (!$validation['success']) {
                    throw new Exception($validation['message']);
                }
                
                // Validate file size (max 5MB)
                $sizeValidation = UploadManager::validateFileSize($_FILES['image'], 5242880);
                if (!$sizeValidation['success']) {
                    throw new Exception($sizeValidation['message']);
                }
                
                // Create slug from name
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
                
                // Get file extension
                $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                
                // Upload to: programs_tahun/{id}-{slug}/cover.{ext}
                $folderPath = "programs_tahun/{$id}-{$slug}";
                $uploadResult = UploadManager::upload($_FILES['image'], $folderPath, $imagePath, 'cover.' . $extension);
                if (!$uploadResult['success']) {
                    throw new Exception($uploadResult['message']);
                }
                
                $imagePath = $uploadResult['path'];
            }
            
            // Update database
            $sql = "UPDATE programs_tahun SET name = ?, description = ?, image = ?, updated_at = NOW() 
                    WHERE id = ?";
            $db->query($sql, [$name, $description, $imagePath, $id]);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Program updated successfully']);
            
        } catch (Exception $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => sanitize_error($e, 'Gagal mengubah program. Silakan coba lagi.')]);
        }
    }
    
    public function deleteProgramTahun($id) {
        try {
            // Debug: Log request
            error_log('=== deleteProgramTahun() called ===');
            error_log('Program ID: ' . $id);
            
            $db = Database::getInstance();
            
            // Get existing program
            $existingProgram = $db->query("SELECT * FROM programs_tahun WHERE id = ?", [$id])->fetch();
            if (!$existingProgram) {
                error_log('ERROR: Program not found');
                throw new Exception('Program not found');
            }
            
            error_log('Program found: ' . $existingProgram['name']);
            
            // Get all gallery images for this program
            $galleryImages = $db->query("SELECT image_path FROM program_gallery WHERE program_id = ?", [$id])->fetchAll();
            error_log('Gallery images count: ' . count($galleryImages));
            
            // Delete cover image
            if ($existingProgram['image']) {
                error_log('Deleting cover image: ' . $existingProgram['image']);
                UploadManager::delete($existingProgram['image']);
            }
            
            // Delete all gallery images
            foreach ($galleryImages as $gallery) {
                if ($gallery['image_path']) {
                    error_log('Deleting gallery image: ' . $gallery['image_path']);
                    UploadManager::delete($gallery['image_path']);
                }
            }
            
            // Delete gallery records from database
            error_log('Deleting gallery records from database for program_id: ' . $id);
            $result = $db->query("DELETE FROM program_gallery WHERE program_id = ?", [$id]);
            $rowsAffected = $result->rowCount();
            error_log('Gallery records deleted. Rows affected: ' . $rowsAffected);
            
            // Soft delete program (set is_active to 0)
            error_log('Soft deleting program (setting is_active = 0)');
            $sql = "UPDATE programs_tahun SET is_active = 0, updated_at = NOW() WHERE id = ?";
            $result = $db->query($sql, [$id]);
            $rowsAffected = $result->rowCount();
            error_log('Program soft deleted. Rows affected: ' . $rowsAffected);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Program deleted successfully']);
            
        } catch (Exception $e) {
            error_log('EXCEPTION in deleteProgramTahun: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => sanitize_error($e, 'Gagal menghapus program. Silakan coba lagi.')]);
        }
    }
    
    public function storeGalleryImage($programId) {
        try {
            // Debug: Log request
            error_log('=== storeGalleryImage() called ===');
            error_log('Program ID: ' . $programId);
            error_log('POST data: ' . print_r($_POST, true));
            error_log('FILES data: ' . print_r($_FILES, true));
            
            // Validate program exists
            $db = Database::getInstance();
            $program = $db->query("SELECT * FROM programs_tahun WHERE id = ? AND is_active = 1", [$programId])->fetch();
            if (!$program) {
                error_log('ERROR: Program not found');
                throw new Exception('Program not found');
            }
            error_log('Program found: ' . $program['name']);
            
            // Validate input
            $description = trim($_POST['description'] ?? '');
            $isCover = isset($_POST['is_cover']) && $_POST['is_cover'] === '1' ? 1 : 0;
            
            if (empty($description)) {
                error_log('ERROR: Description is empty');
                throw new Exception('Description is required');
            }
            error_log('Description: ' . $description);
            error_log('Is Cover: ' . $isCover);
            
            // Handle image upload using UploadManager
            $imagePath = null;
            if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                error_log('ERROR: Image file missing or upload error: ' . ($_FILES['image']['error'] ?? 'N/A'));
                throw new Exception('Image is required');
            }
            
            // Validate file type
            $validation = UploadManager::validateFileType($_FILES['image'], ['jpg', 'jpeg', 'png', 'gif', 'webp']);
            if (!$validation['success']) {
                error_log('ERROR: File type validation failed: ' . $validation['message']);
                throw new Exception($validation['message']);
            }
            error_log('File type validation passed');
            
            // Validate file size (max 5MB)
            $sizeValidation = UploadManager::validateFileSize($_FILES['image'], 5242880);
            if (!$sizeValidation['success']) {
                error_log('ERROR: File size validation failed: ' . $sizeValidation['message']);
                throw new Exception($sizeValidation['message']);
            }
            error_log('File size validation passed');
            
            // Create slug from program name
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $program['name'])));
            
            // Get file extension
            $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            
            // Generate unique filename with timestamp
            $filename = 'gallery-' . time() . '-' . uniqid() . '.' . $extension;
            
            // Upload to: programs_tahun/{id}-{slug}/gallery/{filename}
            $folderPath = "programs_tahun/{$programId}-{$slug}/gallery";
            error_log('Uploading to folder: ' . $folderPath . ' with filename: ' . $filename);
            $uploadResult = UploadManager::upload($_FILES['image'], $folderPath, null, $filename);
            error_log('Upload result: ' . print_r($uploadResult, true));
            
            if (!$uploadResult['success']) {
                error_log('ERROR: Upload failed: ' . $uploadResult['message']);
                throw new Exception($uploadResult['message']);
            }
            
            $imagePath = $uploadResult['path'];
            error_log('Image uploaded successfully to: ' . $imagePath);
            
            // If this is set as cover, unset all other covers for this program
            if ($isCover) {
                error_log('Unsetting other cover images for program ID: ' . $programId);
                $db->query("UPDATE program_gallery SET is_cover = 0 WHERE program_id = ?", [$programId]);
            }
            
            // Get max display order for this program
            $maxOrder = $db->query("SELECT MAX(display_order) as max_order FROM program_gallery WHERE program_id = ?", [$programId])->fetch();
            $displayOrder = ($maxOrder['max_order'] ?? 0) + 1;
            error_log('Display order: ' . $displayOrder);
            
            // Insert into database
            error_log('Inserting into database - program_id: ' . $programId . ', image_path: ' . $imagePath . ', description: ' . $description . ', is_cover: ' . $isCover . ', display_order: ' . $displayOrder);
            $sql = "INSERT INTO program_gallery (program_id, image_path, description, is_cover, display_order) 
                    VALUES (?, ?, ?, ?, ?)";
            $result = $db->query($sql, [$programId, $imagePath, $description, $isCover, $displayOrder]);
            error_log('Insert successful! Rows affected: ' . $result->rowCount());
            
            // Get inserted ID
            $insertedId = $db->getConnection()->lastInsertId();
            error_log('Inserted ID: ' . $insertedId);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Gallery image added successfully',
                'data' => [
                    'id' => $insertedId,
                    'image_path' => $imagePath,
                    'description' => $description,
                    'is_cover' => $isCover
                ]
            ]);
            
        } catch (Exception $e) {
            error_log('EXCEPTION in storeGalleryImage: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => sanitize_error($e, 'Gagal menambah gambar galeri. Silakan coba lagi.')]);
        }
    }
    
    public function updateGalleryImage($programId, $galleryImageId) {
        $db = Database::getInstance();
        
        try {
            // Get program info
            $program = $db->query("SELECT * FROM programs_tahun WHERE id = ?", [$programId])->fetch();
            if (!$program) {
                throw new Exception('Program not found');
            }
            
            // Get existing gallery image
            $existingImage = $db->query(
                "SELECT * FROM program_gallery WHERE id = ? AND program_id = ?", 
                [$galleryImageId, $programId]
            )->fetch();
            
            if (!$existingImage) {
                throw new Exception('Gallery image not found');
            }
            
            // Get form data
            $description = $_POST['description'] ?? '';
            $isCover = isset($_POST['is_cover']) && $_POST['is_cover'] == '1' ? 1 : 0;
            
            // Validate description
            if (empty($description)) {
                throw new Exception('Description is required');
            }
            
            // Handle image upload if new image is provided using UploadManager
            $imagePath = $existingImage['image_path'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Validate file type
                $validation = UploadManager::validateFileType($_FILES['image'], ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                if (!$validation['success']) {
                    throw new Exception($validation['message']);
                }
                
                // Validate file size (max 5MB)
                $sizeValidation = UploadManager::validateFileSize($_FILES['image'], 5242880);
                if (!$sizeValidation['success']) {
                    throw new Exception($sizeValidation['message']);
                }
                
                // Create slug from program name
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $program['name'])));
                
                // Get file extension
                $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                
                // Generate unique filename
                $filename = 'gallery-' . time() . '-' . uniqid() . '.' . $extension;
                
                // Upload to: programs_tahun/{id}-{slug}/gallery/{filename}
                $folderPath = "programs_tahun/{$programId}-{$slug}/gallery";
                $uploadResult = UploadManager::upload($_FILES['image'], $folderPath, $imagePath, $filename);
                if (!$uploadResult['success']) {
                    throw new Exception($uploadResult['message']);
                }
                
                $imagePath = $uploadResult['path'];
            }
            
            // If this is set as cover, swap files between gallery and cover location
            if ($isCover) {
                error_log("=== SET AS COVER TRIGGERED ===");
                
                // Unset all other covers for this program
                $db->query("UPDATE program_gallery SET is_cover = 0 WHERE program_id = ? AND id != ?", [$programId, $galleryImageId]);
                
                // Create slug from program name
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $program['name'])));
                $coverFolderPath = "programs_tahun/{$programId}-{$slug}";
                
                if (R2_ENABLED) {
                    require_once ROOT_PATH . '/app/helpers/CloudflareR2.php';
                    $r2 = new CloudflareR2();
                    
                    // Get current gallery image key and filename
                    $galleryKey = str_replace(R2_PUBLIC_URL . '/', '', $imagePath);
                    $galleryFilename = basename($galleryKey);
                    
                    error_log("Gallery to promote: {$galleryKey}");
                    error_log("Gallery filename: {$galleryFilename}");
                    
                    // STEP 1: Move gallery image OUT to root folder (keep original filename)
                    $newCoverPath = "{$coverFolderPath}/{$galleryFilename}";
                    error_log("=== STEP 1: Moving gallery OUT to become cover ===");
                    error_log("New cover path: {$newCoverPath}");
                    
                    $moveResult = $r2->moveObject(R2_PUBLIC_BUCKET, $galleryKey, R2_PUBLIC_BUCKET, $newCoverPath);
                    if (!$moveResult) {
                        throw new Exception('Failed to move gallery image out');
                    }
                    error_log("Gallery moved out: SUCCESS");
                    
                    // STEP 2: If old cover exists, move it INTO gallery folder (keep original filename)
                    if ($program['image']) {
                        error_log("=== STEP 2: Moving old cover INTO gallery ===");
                        
                        $oldCoverKey = str_replace(R2_PUBLIC_URL . '/', '', $program['image']);
                        $oldCoverFilename = basename($oldCoverKey);
                        $oldCoverNewPath = "{$coverFolderPath}/gallery/{$oldCoverFilename}";
                        
                        error_log("Old cover: {$oldCoverKey}");
                        error_log("Old cover new path: {$oldCoverNewPath}");
                        
                        // Move old cover into gallery
                        $moveResult = $r2->moveObject(R2_PUBLIC_BUCKET, $oldCoverKey, R2_PUBLIC_BUCKET, $oldCoverNewPath);
                        error_log("Move old cover INTO gallery: " . ($moveResult ? 'SUCCESS' : 'FAILED'));
                        
                        if ($moveResult) {
                            // Insert old cover as new gallery image
                            $oldCoverFullPath = R2_PUBLIC_URL . '/' . $oldCoverNewPath;
                            $db->query(
                                "INSERT INTO program_gallery (program_id, image_path, is_cover) VALUES (?, ?, 0)",
                                [$programId, $oldCoverFullPath]
                            );
                            error_log("Old cover added to gallery table");
                        }
                    }
                    
                    // STEP 3: Delete gallery record (it's now the cover)
                    error_log("=== STEP 3: Delete old gallery record ===");
                    $db->query("DELETE FROM program_gallery WHERE id = ?", [$galleryImageId]);
                    
                    // STEP 4: Update program.image with new cover path
                    error_log("=== STEP 4: Update program cover ===");
                    $newCoverFullPath = R2_PUBLIC_URL . '/' . $newCoverPath;
                    $db->query("UPDATE programs_tahun SET image = ? WHERE id = ?", [$newCoverFullPath, $programId]);
                    error_log("Program cover updated to: {$newCoverFullPath}");
                    
                    // Return success with new cover path
                    echo json_encode([
                        'success' => true,
                        'message' => 'Cover berhasil diperbarui',
                        'newCoverPath' => $newCoverFullPath
                    ]);
                    exit;
                    
                } else {
                    // Local: Similar swap logic for local files
                    $gallerySourcePath = ROOT_PATH . '/public/' . $imagePath;
                    $newCoverFullPath = ROOT_PATH . '/public/uploads/' . $newCoverPath;
                    
                    // Step 1: If old cover exists, move it TO gallery folder
                    if ($program['image']) {
                        $oldCoverSource = ROOT_PATH . '/public/' . $program['image'];
                        $oldCoverExtension = strtolower(pathinfo($program['image'], PATHINFO_EXTENSION));
                        
                        $timestamp = time();
                        $oldCoverNewPath = "{$coverFolderPath}/gallery/gallery-{$timestamp}.{$oldCoverExtension}";
                        $oldCoverNewFullPath = ROOT_PATH . '/public/uploads/' . $oldCoverNewPath;
                        
                        // Create directory if needed
                        $destDir = dirname($oldCoverNewFullPath);
                        if (!is_dir($destDir)) {
                            mkdir($destDir, 0755, true);
                        }
                        
                        if (file_exists($oldCoverSource) && rename($oldCoverSource, $oldCoverNewFullPath)) {
                            // Insert old cover as new gallery image
                            $oldCoverPath = 'uploads/' . $oldCoverNewPath;
                            $db->query(
                                "INSERT INTO program_gallery (program_id, image_path, is_cover) VALUES (?, ?, 0)",
                                [$programId, $oldCoverPath]
                            );
                        }
                    }
                    
                    // Step 2: Move gallery image OUT to become cover
                    $destDir = dirname($newCoverFullPath);
                    if (!is_dir($destDir)) {
                        mkdir($destDir, 0755, true);
                    }
                    
                    if (!rename($gallerySourcePath, $newCoverFullPath)) {
                        throw new Exception('Failed to move gallery image to cover location');
                    }
                    
                    // Step 3: Delete gallery record
                    $db->query("DELETE FROM program_gallery WHERE id = ?", [$galleryImageId]);
                    
                    // Step 4: Update program.image
                    $newCoverDbPath = 'uploads/' . $newCoverPath;
                    $db->query("UPDATE programs_tahun SET image = ? WHERE id = ?", [$newCoverDbPath, $programId]);
                    
                    echo json_encode([
                        'success' => true,
                        'message' => 'Cover berhasil diperbarui',
                        'newCoverPath' => $newCoverDbPath
                    ]);
                    exit;
                }
            }
            
            // Update database
            $sql = "UPDATE program_gallery SET image_path = ?, description = ?, is_cover = ? WHERE id = ? AND program_id = ?";
            $db->query($sql, [$imagePath, $description, $isCover, $galleryImageId, $programId]);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Gallery image updated successfully',
                'data' => [
                    'image_path' => $imagePath,
                    'description' => $description,
                    'is_cover' => $isCover
                ]
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => sanitize_error($e, 'Gagal mengubah gambar galeri. Silakan coba lagi.')]);
        }
    }
    
    public function updateProgramImage($programId) {
        $db = Database::getInstance();
        
        try {
            // Get existing program
            $existingProgram = $db->query(
                "SELECT * FROM programs_tahun WHERE id = ?", 
                [$programId]
            )->fetch();
            
            if (!$existingProgram) {
                throw new Exception('Program not found');
            }
            
            // Handle new image upload (optional) using UploadManager
            $imagePath = $existingProgram['image'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Validate file size (max 5MB)
                $sizeValidation = UploadManager::validateFileSize($_FILES['image'], 5242880);
                if (!$sizeValidation['success']) {
                    throw new Exception($sizeValidation['message']);
                }
                
                // Create slug from program name
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $existingProgram['name'])));
                
                // Get file extension
                $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                
                // Upload to: programs_tahun/{id}-{slug}/cover.{ext}
                $folderPath = "programs_tahun/{$programId}-{$slug}";
                $uploadResult = UploadManager::upload($_FILES['image'], $folderPath, $imagePath, 'cover.' . $extension);
                if (!$uploadResult['success']) {
                    throw new Exception($uploadResult['message']);
                }
                
                $imagePath = $uploadResult['path'];
            }
            
            // Handle is_cover checkbox
            $isCover = isset($_POST['is_cover']) && $_POST['is_cover'] == '1';
            
            if ($isCover) {
                // If checked, unset all gallery covers so program.image becomes the cover
                $db->query("UPDATE program_gallery SET is_cover = 0 WHERE program_id = ?", [$programId]);
            }
            
            // Update program.image in database
            $db->query(
                "UPDATE programs_tahun SET image = ? WHERE id = ?",
                [$imagePath, $programId]
            );
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Program image updated successfully'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => sanitize_error($e, 'Gagal mengubah gambar program. Silakan coba lagi.')]);
        }
    }
    
    public function deleteProgramImage($programId) {
        $db = Database::getInstance();
        
        try {
            // Get existing program
            $existingProgram = $db->query(
                "SELECT * FROM programs_tahun WHERE id = ?", 
                [$programId]
            )->fetch();
            
            if (!$existingProgram) {
                throw new Exception('Program not found');
            }
            
            // Delete image file if exists using UploadManager
            if ($existingProgram['image']) {
                UploadManager::delete($existingProgram['image']);
            }
            
            // Set image to NULL in database
            $db->query(
                "UPDATE programs_tahun SET image = NULL WHERE id = ?",
                [$programId]
            );
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Program image deleted successfully'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => sanitize_error($e, 'Gagal menghapus gambar program. Silakan coba lagi.')]);
        }
    }
    
    public function deleteGalleryImage($programId, $galleryImageId) {
        $db = Database::getInstance();
        
        try {
            // Debug: Log request
            error_log('=== deleteGalleryImage() called ===');
            error_log('Program ID: ' . $programId);
            error_log('Gallery Image ID: ' . $galleryImageId);
            
            // Get existing gallery image
            $existingImage = $db->query(
                "SELECT * FROM program_gallery WHERE id = ? AND program_id = ?", 
                [$galleryImageId, $programId]
            )->fetch();
            
            if (!$existingImage) {
                error_log('ERROR: Gallery image not found');
                throw new Exception('Gallery image not found');
            }
            
            error_log('Gallery image found: ' . print_r($existingImage, true));
            
            // Delete image file using UploadManager
            if ($existingImage['image_path']) {
                error_log('Deleting image file: ' . $existingImage['image_path']);
                UploadManager::delete($existingImage['image_path']);
                error_log('Image file deleted successfully');
            }
            
            // Delete from database
            error_log('Deleting from database: gallery_id=' . $galleryImageId . ', program_id=' . $programId);
            $result = $db->query("DELETE FROM program_gallery WHERE id = ? AND program_id = ?", [$galleryImageId, $programId]);
            $rowsAffected = $result->rowCount();
            error_log('Delete query executed. Rows affected: ' . $rowsAffected);
            
            if ($rowsAffected === 0) {
                error_log('WARNING: No rows were deleted from database');
            }
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Gallery image deleted successfully'
            ]);
            
        } catch (Exception $e) {
            error_log('EXCEPTION in deleteGalleryImage: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => sanitize_error($e, 'Gagal menghapus gambar galeri. Silakan coba lagi.')]);
        }
    }
    
    // ========================================
    // Program Harian Methods
    // ========================================
    
    public function updateProgramHarian($id) {
        $db = Database::getInstance();
        
        try {
            // Validate input
            $program_name = $_POST['program_name'] ?? '';
            $description = $_POST['description'] ?? '';
            
            if (empty($program_name) || empty($description)) {
                throw new Exception('Program name and description are required');
            }
            
            // Handle image upload (optional) using UploadManager
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Validate file type
                $validation = UploadManager::validateFileType($_FILES['image'], ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                if (!$validation['success']) {
                    throw new Exception($validation['message']);
                }
                
                // Validate file size (max 5MB)
                $sizeValidation = UploadManager::validateFileSize($_FILES['image'], 5242880);
                if (!$sizeValidation['success']) {
                    throw new Exception($sizeValidation['message']);
                }
                
                // Get old image for deletion
                $existingProgram = $db->query("SELECT image FROM programs_harian WHERE id = ?", [$id])->fetch();
                $oldImagePath = $existingProgram['image'] ?? null;
                
                // Upload file using UploadManager (auto deletes old file)
                $uploadResult = UploadManager::upload($_FILES['image'], 'programs_harian', $oldImagePath);
                if (!$uploadResult['success']) {
                    throw new Exception($uploadResult['message']);
                }
                
                $imagePath = $uploadResult['path'];
            }
            
            // Update database
            if ($imagePath) {
                $db->query(
                    "UPDATE programs_harian SET program_name = ?, description = ?, image = ? WHERE id = ?",
                    [$program_name, $description, $imagePath, $id]
                );
            } else {
                $db->query(
                    "UPDATE programs_harian SET program_name = ?, description = ? WHERE id = ?",
                    [$program_name, $description, $id]
                );
            }
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Program updated successfully'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => sanitize_error($e, 'Gagal mengubah program. Silakan coba lagi.')]);
        }
    }
    
    public function storeGalleryHarianImage($programId) {
        $db = Database::getInstance();
        
        try {
            // Validate program exists
            $program = $db->query("SELECT * FROM programs_harian WHERE id = ?", [$programId])->fetch();
            if (!$program) {
                throw new Exception('Program not found');
            }
            
            // Validate input
            if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Image is required');
            }
            
            $description = $_POST['description'] ?? '';
            $setAsCover = isset($_POST['set_as_cover']) && $_POST['set_as_cover'] == '1';
            
            if (empty($description)) {
                throw new Exception('Description is required');
            }
            
            // Validate and upload using UploadManager
            $validation = UploadManager::validateFileType($_FILES['image'], ['jpg', 'jpeg', 'png', 'gif', 'webp']);
            if (!$validation['success']) {
                throw new Exception($validation['message']);
            }
            
            $sizeValidation = UploadManager::validateFileSize($_FILES['image'], 5242880);
            if (!$sizeValidation['success']) {
                throw new Exception($sizeValidation['message']);
            }
            
            // Create slug from program name (day name)
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $program['day_name'])));
            
            // Get file extension
            $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            
            // Generate unique filename with timestamp
            $filename = 'gallery-' . time() . '-' . uniqid() . '.' . $extension;
            
            // Upload to: programs_harian/{id}-{slug}/gallery/{filename}
            $folderPath = "programs_harian/{$programId}-{$slug}/gallery";
            $uploadResult = UploadManager::upload($_FILES['image'], $folderPath, null, $filename);
            if (!$uploadResult['success']) {
                throw new Exception($uploadResult['message']);
            }
            
            $imagePath = $uploadResult['path'];
            
            // If set as cover, unset all other covers for this program
            if ($setAsCover) {
                $db->query("UPDATE program_harian_gallery SET is_cover = 0 WHERE program_harian_id = ?", [$programId]);
            }
            
            // Get next display order
            $maxOrder = $db->query(
                "SELECT MAX(display_order) as max_order FROM program_harian_gallery WHERE program_harian_id = ?", 
                [$programId]
            )->fetch();
            $displayOrder = ($maxOrder['max_order'] ?? -1) + 1;
            
            // Insert into database
            $db->query(
                "INSERT INTO program_harian_gallery (program_harian_id, image_path, description, is_cover, display_order) VALUES (?, ?, ?, ?, ?)",
                [$programId, $imagePath, $description, $setAsCover ? 1 : 0, $displayOrder]
            );
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Gallery image added successfully'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => sanitize_error($e, 'Gagal menambah gambar galeri. Silakan coba lagi.')]);
        }
    }
    
    public function updateGalleryHarianImage($programId, $galleryImageId) {
        $db = Database::getInstance();
        
        try {
            // Get program info
            $program = $db->query("SELECT * FROM programs_harian WHERE id = ?", [$programId])->fetch();
            if (!$program) {
                throw new Exception('Program not found');
            }
            
            // Validate input
            $description = $_POST['description'] ?? '';
            $isCover = isset($_POST['is_cover']) && $_POST['is_cover'] == '1';
            
            if (empty($description)) {
                throw new Exception('Description is required');
            }
            
            // Get existing gallery image
            $existingImage = $db->query(
                "SELECT * FROM program_harian_gallery WHERE id = ? AND program_harian_id = ?", 
                [$galleryImageId, $programId]
            )->fetch();
            
            if (!$existingImage) {
                throw new Exception('Gallery image not found');
            }
            
            // Handle image upload (optional) using UploadManager
            $imagePath = $existingImage['image_path'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Validate file type
                $validation = UploadManager::validateFileType($_FILES['image'], ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                if (!$validation['success']) {
                    throw new Exception($validation['message']);
                }
                
                // Validate file size (max 5MB)
                $sizeValidation = UploadManager::validateFileSize($_FILES['image'], 5242880);
                if (!$sizeValidation['success']) {
                    throw new Exception($sizeValidation['message']);
                }
                
                // Create slug from day name
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $program['day_name'])));
                
                // Get file extension
                $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                
                // Generate unique filename
                $filename = 'gallery-' . time() . '-' . uniqid() . '.' . $extension;
                
                // Upload to: programs_harian/{id}-{slug}/gallery/{filename}
                $folderPath = "programs_harian/{$programId}-{$slug}/gallery";
                $uploadResult = UploadManager::upload($_FILES['image'], $folderPath, $imagePath, $filename);
                if (!$uploadResult['success']) {
                    throw new Exception($uploadResult['message']);
                }
                
                $imagePath = $uploadResult['path'];
            }
            
            // If set as cover, unset all other covers for this program
            if ($isCover) {
                $db->query("UPDATE program_harian_gallery SET is_cover = 0 WHERE program_harian_id = ?", [$programId]);
            }
            
            // Update database
            $db->query(
                "UPDATE program_harian_gallery SET image_path = ?, description = ?, is_cover = ? WHERE id = ? AND program_harian_id = ?",
                [$imagePath, $description, $isCover ? 1 : 0, $galleryImageId, $programId]
            );
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Gallery image updated successfully'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => sanitize_error($e, 'Gagal mengubah gambar galeri. Silakan coba lagi.')]);
        }
    }
    
    public function updateProgramHarianImage($programId) {
        $db = Database::getInstance();
        
        try {
            // Validate input
            $description = $_POST['description'] ?? '';
            $isCover = isset($_POST['is_cover']) && $_POST['is_cover'] == '1';
            
            // Get existing program
            $existingProgram = $db->query("SELECT * FROM programs_harian WHERE id = ?", [$programId])->fetch();
            
            if (!$existingProgram) {
                throw new Exception('Program not found');
            }
            
            // Handle image upload (optional - can update just description/cover status) using UploadManager
            $imagePath = $existingProgram['image'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Validate file type
                $validation = UploadManager::validateFileType($_FILES['image'], ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                if (!$validation['success']) {
                    throw new Exception($validation['message']);
                }
                
                // Validate file size (max 5MB)
                $sizeValidation = UploadManager::validateFileSize($_FILES['image'], 5242880);
                if (!$sizeValidation['success']) {
                    throw new Exception($sizeValidation['message']);
                }
                
                // Upload file (will delete old file automatically)
                $uploadResult = UploadManager::upload($_FILES['image'], 'programs_harian', $imagePath);
                if (!$uploadResult['success']) {
                    throw new Exception($uploadResult['message']);
                }
                
                $imagePath = $uploadResult['path'];
            }
            
            // If set as cover, unset all gallery covers for this program
            if ($isCover) {
                $db->query("UPDATE program_harian_gallery SET is_cover = 0 WHERE program_harian_id = ?", [$programId]);
            }
            
            // Update program image
            if ($imagePath) {
                $db->query("UPDATE programs_harian SET image = ? WHERE id = ?", [$imagePath, $programId]);
            }
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Program image updated successfully'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => sanitize_error($e, 'Gagal mengubah gambar program. Silakan coba lagi.')]);
        }
    }
    
    public function deleteProgramHarianImage($programId) {
        $db = Database::getInstance();
        
        try {
            // Get existing program
            $existingProgram = $db->query("SELECT image FROM programs_harian WHERE id = ?", [$programId])->fetch();
            
            if (!$existingProgram) {
                throw new Exception('Program not found');
            }
            
            if (empty($existingProgram['image'])) {
                throw new Exception('No image to delete');
            }
            
            // Delete image file using UploadManager
            UploadManager::delete($existingProgram['image']);
            
            // Update database - set image to NULL
            $db->query("UPDATE programs_harian SET image = NULL WHERE id = ?", [$programId]);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Program image deleted successfully'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => sanitize_error($e, 'Gagal menghapus gambar program. Silakan coba lagi.')]);
        }
    }
    
    public function deleteGalleryHarianImage($programId, $galleryImageId) {
        $db = Database::getInstance();
        
        try {
            // Get existing gallery image
            $existingImage = $db->query(
                "SELECT * FROM program_harian_gallery WHERE id = ? AND program_harian_id = ?", 
                [$galleryImageId, $programId]
            )->fetch();
            
            if (!$existingImage) {
                throw new Exception('Gallery image not found');
            }
            
            // Delete image file using UploadManager
            UploadManager::delete($existingImage['image_path']);
            
            // Delete from database
            $db->query("DELETE FROM program_harian_gallery WHERE id = ? AND program_harian_id = ?", [$galleryImageId, $programId]);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Gallery image deleted successfully'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => sanitize_error($e, 'Gagal menghapus gambar galeri. Silakan coba lagi.')]);
        }
    }
}
