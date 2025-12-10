<?php
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
        
        // Fetch programs harian from database (5 days)
        $programsHarian = $db->query("SELECT * FROM programs_harian WHERE is_active = 1 ORDER BY display_order ASC")->fetchAll();
        
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
            
            // Debug: Log $_FILES
            error_log('FILES: ' . print_r($_FILES, true));
            
            // Handle image upload
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Validate file type
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                $fileType = $_FILES['image']['type'];
                
                if (!in_array($fileType, $allowedTypes)) {
                    throw new Exception('Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed');
                }
                
                // Validate file size (max 5MB)
                $maxSize = 5 * 1024 * 1024; // 5MB
                if ($_FILES['image']['size'] > $maxSize) {
                    throw new Exception('File size too large. Maximum 5MB allowed');
                }
                
                $uploadDir = __DIR__ . '/../../public/uploads/classes/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = 'class_' . time() . '_' . uniqid() . '.' . $extension;
                $targetPath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $imagePath = '/uploads/classes/' . $filename;
                } else {
                    throw new Exception('Failed to upload image');
                }
            }
            
            // Get max display order
            $db = Database::getInstance();
            $maxOrder = $db->query("SELECT MAX(display_order) as max_order FROM classes")->fetch();
            $displayOrder = ($maxOrder['max_order'] ?? 0) + 1;
            
            // Insert into database
            $sql = "INSERT INTO classes (name, age_range, duration, max_students, image, display_order) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $db->query($sql, [$name, $age_range, $duration, $max_students, $imagePath, $displayOrder]);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Class added successfully']);
            
        } catch (Exception $e) {
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
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Validate file type
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                $fileType = $_FILES['image']['type'];
                
                if (!in_array($fileType, $allowedTypes)) {
                    throw new Exception('Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed');
                }
                
                // Validate file size (max 5MB)
                $maxSize = 5 * 1024 * 1024; // 5MB
                if ($_FILES['image']['size'] > $maxSize) {
                    throw new Exception('File size too large. Maximum 5MB allowed');
                }
                
                // Delete old image
                if ($imagePath && file_exists(__DIR__ . '/../../public' . $imagePath)) {
                    unlink(__DIR__ . '/../../public' . $imagePath);
                }
                
                $uploadDir = __DIR__ . '/../../public/uploads/classes/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = 'class_' . time() . '_' . uniqid() . '.' . $extension;
                $targetPath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $imagePath = '/uploads/classes/' . $filename;
                } else {
                    throw new Exception('Failed to upload image');
                }
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
            
            // Delete image file
            if ($existingClass['image'] && file_exists(__DIR__ . '/../../public' . $existingClass['image'])) {
                unlink(__DIR__ . '/../../public' . $existingClass['image']);
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
            
            // Handle image upload
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Validate file type
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                $fileType = $_FILES['image']['type'];
                
                if (!in_array($fileType, $allowedTypes)) {
                    throw new Exception('Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.');
                }
                
                // Validate file size (max 5MB)
                $maxSize = 5 * 1024 * 1024;
                if ($_FILES['image']['size'] > $maxSize) {
                    throw new Exception('File size exceeds 5MB limit.');
                }
                
                // Create upload directory if it doesn't exist
                $uploadDir = __DIR__ . '/../../public/uploads/programs-tahun/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = 'program_' . time() . '_' . uniqid() . '.' . $extension;
                $targetPath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $imagePath = '/uploads/programs-tahun/' . $filename;
                } else {
                    throw new Exception('Failed to upload image');
                }
            }
            
            // Insert into database
            $db = Database::getInstance();
            $sql = "INSERT INTO programs_tahun (name, description, image, is_active, display_order, created_at, updated_at) 
                    VALUES (?, ?, ?, 1, 0, NOW(), NOW())";
            $db->query($sql, [$name, $description, $imagePath]);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Program created successfully']);
            
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
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                $fileType = $_FILES['image']['type'];
                
                if (!in_array($fileType, $allowedTypes)) {
                    throw new Exception('Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.');
                }
                
                // Validate file size (max 5MB)
                $maxSize = 5 * 1024 * 1024;
                if ($_FILES['image']['size'] > $maxSize) {
                    throw new Exception('File size exceeds 5MB limit.');
                }
                
                // Delete old image
                if ($existingProgram['image'] && file_exists(__DIR__ . '/../../public' . $existingProgram['image'])) {
                    unlink(__DIR__ . '/../../public' . $existingProgram['image']);
                }
                
                // Create upload directory if it doesn't exist
                $uploadDir = __DIR__ . '/../../public/uploads/programs-tahun/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = 'program_' . time() . '_' . uniqid() . '.' . $extension;
                $targetPath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $imagePath = '/uploads/programs-tahun/' . $filename;
                } else {
                    throw new Exception('Failed to upload image');
                }
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
            $db = Database::getInstance();
            
            // Get existing program
            $existingProgram = $db->query("SELECT * FROM programs_tahun WHERE id = ?", [$id])->fetch();
            if (!$existingProgram) {
                throw new Exception('Program not found');
            }
            
            // Delete image file
            if ($existingProgram['image'] && file_exists(__DIR__ . '/../../public' . $existingProgram['image'])) {
                unlink(__DIR__ . '/../../public' . $existingProgram['image']);
            }
            
            // Soft delete (set is_active to 0)
            $sql = "UPDATE programs_tahun SET is_active = 0, updated_at = NOW() WHERE id = ?";
            $db->query($sql, [$id]);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Program deleted successfully']);
            
        } catch (Exception $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => sanitize_error($e, 'Gagal menghapus program. Silakan coba lagi.')]);
        }
    }
    
    public function storeGalleryImage($programId) {
        try {
            // Validate program exists
            $db = Database::getInstance();
            $program = $db->query("SELECT * FROM programs_tahun WHERE id = ? AND is_active = 1", [$programId])->fetch();
            if (!$program) {
                throw new Exception('Program not found');
            }
            
            // Validate input
            $description = trim($_POST['description'] ?? '');
            $isCover = isset($_POST['is_cover']) && $_POST['is_cover'] === '1' ? 1 : 0;
            
            if (empty($description)) {
                throw new Exception('Description is required');
            }
            
            // Handle image upload
            $imagePath = null;
            if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Image is required');
            }
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            $fileType = $_FILES['image']['type'];
            
            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception('Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed');
            }
            
            // Validate file size (max 5MB)
            $maxSize = 5 * 1024 * 1024; // 5MB
            if ($_FILES['image']['size'] > $maxSize) {
                throw new Exception('File size too large. Maximum 5MB allowed');
            }
            
            $uploadDir = __DIR__ . '/../../public/uploads/program-gallery/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = 'gallery_' . $programId . '_' . time() . '_' . uniqid() . '.' . $extension;
            $targetPath = $uploadDir . $filename;
            
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                throw new Exception('Failed to upload image');
            }
            
            $imagePath = '/uploads/program-gallery/' . $filename;
            
            // If this is set as cover, unset all other covers for this program
            if ($isCover) {
                $db->query("UPDATE program_gallery SET is_cover = 0 WHERE program_id = ?", [$programId]);
            }
            
            // Get max display order for this program
            $maxOrder = $db->query("SELECT MAX(display_order) as max_order FROM program_gallery WHERE program_id = ?", [$programId])->fetch();
            $displayOrder = ($maxOrder['max_order'] ?? 0) + 1;
            
            // Insert into database
            $sql = "INSERT INTO program_gallery (program_id, image_path, description, is_cover, display_order) 
                    VALUES (?, ?, ?, ?, ?)";
            $db->query($sql, [$programId, $imagePath, $description, $isCover, $displayOrder]);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Gallery image added successfully',
                'data' => [
                    'image_path' => $imagePath,
                    'description' => $description,
                    'is_cover' => $isCover
                ]
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => sanitize_error($e, 'Gagal menambah gambar galeri. Silakan coba lagi.')]);
        }
    }
    
    public function updateGalleryImage($programId, $galleryImageId) {
        $db = Database::getInstance();
        
        try {
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
            
            // Handle image upload if new image is provided
            $imagePath = $existingImage['image_path'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Validate file type
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($_FILES['image']['type'], $allowedTypes)) {
                    throw new Exception('Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.');
                }
                
                // Validate file size (5MB)
                if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
                    throw new Exception('File size exceeds 5MB');
                }
                
                // Create upload directory if not exists
                $uploadDir = __DIR__ . '/../../public/uploads/program-gallery';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                // Generate unique filename
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = 'gallery_' . $programId . '_' . uniqid() . '.' . $extension;
                $targetPath = $uploadDir . '/' . $filename;
                
                // Upload file
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    throw new Exception('Failed to upload image');
                }
                
                // Delete old image file
                $oldImagePath = __DIR__ . '/../../public' . $existingImage['image_path'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                
                $imagePath = '/uploads/program-gallery/' . $filename;
            }
            
            // If this is set as cover, unset all other covers for this program
            if ($isCover) {
                $db->query("UPDATE program_gallery SET is_cover = 0 WHERE program_id = ? AND id != ?", [$programId, $galleryImageId]);
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
            
            // Handle new image upload (optional)
            $imagePath = $existingProgram['image'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Delete old image if exists
                if ($existingProgram['image']) {
                    $oldImagePath = __DIR__ . '/../../public' . $existingProgram['image'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                
                // Validate file size (5MB)
                if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
                    throw new Exception('File size exceeds 5MB limit');
                }
                
                // Handle image upload
                $uploadDir = __DIR__ . '/../../public/uploads/programs_tahun/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileName = time() . '_' . basename($_FILES['image']['name']);
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $imagePath = '/uploads/programs_tahun/' . $fileName;
                } else {
                    throw new Exception('Failed to upload image');
                }
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
            
            // Delete image file if exists
            if ($existingProgram['image']) {
                $imagePath = __DIR__ . '/../../public' . $existingProgram['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
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
            // Get existing gallery image
            $existingImage = $db->query(
                "SELECT * FROM program_gallery WHERE id = ? AND program_id = ?", 
                [$galleryImageId, $programId]
            )->fetch();
            
            if (!$existingImage) {
                throw new Exception('Gallery image not found');
            }
            
            // Delete image file
            $imagePath = __DIR__ . '/../../public' . $existingImage['image_path'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            
            // Delete from database
            $db->query("DELETE FROM program_gallery WHERE id = ? AND program_id = ?", [$galleryImageId, $programId]);
            
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
            
            // Handle image upload (optional)
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Validate file type
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                $fileType = $_FILES['image']['type'];
                
                if (!in_array($fileType, $allowedTypes)) {
                    throw new Exception('Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed');
                }
                
                // Validate file size (max 5MB)
                $maxSize = 5 * 1024 * 1024; // 5MB
                if ($_FILES['image']['size'] > $maxSize) {
                    throw new Exception('File size too large. Maximum 5MB allowed');
                }
                
                $uploadDir = __DIR__ . '/../../public/uploads/programs_harian/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = 'program_harian_' . time() . '_' . uniqid() . '.' . $extension;
                $targetPath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $imagePath = '/uploads/programs_harian/' . $filename;
                    
                    // Delete old image if exists
                    $existingProgram = $db->query("SELECT image FROM programs_harian WHERE id = ?", [$id])->fetch();
                    if ($existingProgram && !empty($existingProgram['image'])) {
                        $oldImagePath = __DIR__ . '/../../public' . $existingProgram['image'];
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                } else {
                    throw new Exception('Failed to upload image');
                }
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
            // Validate input
            if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Image is required');
            }
            
            $description = $_POST['description'] ?? '';
            $setAsCover = isset($_POST['set_as_cover']) && $_POST['set_as_cover'] == '1';
            
            if (empty($description)) {
                throw new Exception('Description is required');
            }
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            $fileType = $_FILES['image']['type'];
            
            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception('Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed');
            }
            
            // Validate file size (max 5MB)
            $maxSize = 5 * 1024 * 1024; // 5MB
            if ($_FILES['image']['size'] > $maxSize) {
                throw new Exception('File size too large. Maximum 5MB allowed');
            }
            
            // Upload image
            $uploadDir = __DIR__ . '/../../public/uploads/program_harian_gallery/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = 'gallery_harian_' . time() . '_' . uniqid() . '.' . $extension;
            $targetPath = $uploadDir . $filename;
            
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                throw new Exception('Failed to upload image');
            }
            
            $imagePath = '/uploads/program_harian_gallery/' . $filename;
            
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
            
            // Handle image upload (optional)
            $imagePath = $existingImage['image_path'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Validate file type
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                $fileType = $_FILES['image']['type'];
                
                if (!in_array($fileType, $allowedTypes)) {
                    throw new Exception('Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed');
                }
                
                // Validate file size (max 5MB)
                $maxSize = 5 * 1024 * 1024; // 5MB
                if ($_FILES['image']['size'] > $maxSize) {
                    throw new Exception('File size too large. Maximum 5MB allowed');
                }
                
                $uploadDir = __DIR__ . '/../../public/uploads/program_harian_gallery/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = 'gallery_harian_' . time() . '_' . uniqid() . '.' . $extension;
                $targetPath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    // Delete old image
                    $oldImagePath = __DIR__ . '/../../public' . $imagePath;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                    
                    $imagePath = '/uploads/program_harian_gallery/' . $filename;
                } else {
                    throw new Exception('Failed to upload image');
                }
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
            
            // Handle image upload (optional - can update just description/cover status)
            $imagePath = $existingProgram['image'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Validate file type
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                $fileType = $_FILES['image']['type'];
                
                if (!in_array($fileType, $allowedTypes)) {
                    throw new Exception('Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed');
                }
                
                // Validate file size (max 5MB)
                $maxSize = 5 * 1024 * 1024; // 5MB
                if ($_FILES['image']['size'] > $maxSize) {
                    throw new Exception('File size too large. Maximum 5MB allowed');
                }
                
                $uploadDir = __DIR__ . '/../../public/uploads/programs_harian/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = 'program_harian_' . time() . '_' . uniqid() . '.' . $extension;
                $targetPath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    // Delete old image
                    if (!empty($imagePath)) {
                        $oldImagePath = __DIR__ . '/../../public' . $imagePath;
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                    
                    $imagePath = '/uploads/programs_harian/' . $filename;
                } else {
                    throw new Exception('Failed to upload image');
                }
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
            
            // Delete image file
            $imagePath = __DIR__ . '/../../public' . $existingProgram['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            
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
            
            // Delete image file
            $imagePath = __DIR__ . '/../../public' . $existingImage['image_path'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            
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
