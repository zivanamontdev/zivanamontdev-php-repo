<?php
/**
 * Admin Management Controller
 * Handles schedules, awards, social media
 */
class ManagementController extends Controller {
    private $scheduleModel;
    private $awardModel;
    private $socialMediaModel;
    private $settingModel;
    private $prakataModel;
    private $kepalaSekolahModel;
    private $karyawanModel;
    private $fasilitasModel;
    
    public function __construct() {
        parent::__construct();
        $this->middleware(AuthMiddleware::class);
        $this->scheduleModel = new Schedule();
        $this->awardModel = new Award();
        $this->socialMediaModel = new SocialMedia();
        $this->settingModel = new Setting();
        $this->prakataModel = new Prakata();
        $this->kepalaSekolahModel = new KepalaSekolah();
        $this->karyawanModel = new Karyawan();
        $this->fasilitasModel = new Fasilitas();
    }
    
    // Management Index (Prakata, Karyawan, Fasilitas)
    public function index() {
        $prakata = $this->prakataModel->get();
        $kepalaSekolah = $this->kepalaSekolahModel->get();
        $karyawan = $this->karyawanModel->getAllOrdered();
        $fasilitas = $this->fasilitasModel->getAllWithGalleryCount();
        
        // Convert image path to URL if exists
        if (!empty($prakata['image'])) {
            $prakata['image'] = asset_url($prakata['image']);
        }
        
        // Convert photo path to URL for kepala sekolah
        if (!empty($kepalaSekolah['photo'])) {
            $kepalaSekolah['photo'] = asset_url($kepalaSekolah['photo']);
        }
        
        // Convert photo paths for karyawan
        foreach ($karyawan as &$k) {
            if (!empty($k['photo'])) {
                $k['photo'] = asset_url($k['photo']);
            }
        }
        
        // Convert image paths for fasilitas and get gallery images
        foreach ($fasilitas as &$f) {
            if (!empty($f['image'])) {
                $f['image'] = asset_url($f['image']);
            }
            
            // Get gallery images for this fasilitas
            $galleryImages = $this->fasilitasModel->getGalleryImages($f['id']);
            
            // Convert gallery image paths to URLs and separate cover image
            $f['gallery'] = [];
            $f['cover_image'] = null;
            
            foreach ($galleryImages as $galleryItem) {
                $galleryItem['image_path'] = asset_url($galleryItem['image_path']);
                
                // Check if this is the cover image
                if (!empty($galleryItem['is_cover']) && $galleryItem['is_cover'] == 1) {
                    $f['cover_image'] = $galleryItem;
                } else {
                    $f['gallery'][] = $galleryItem;
                }
            }
        }
        
        $data = [
            'currentPage' => 'management',
            'prakata' => $prakata,
            'kepalaSekolah' => $kepalaSekolah,
            'karyawan' => $karyawan,
            'fasilitas' => $fasilitas
        ];
        
        $this->view('admin/management/index', $data);
    }
    
    // ========== PRAKATA METHODS ==========
    
    /**
     * Get prakata data
     */
    public function getPrakata() {
        header('Content-Type: application/json');
        
        $prakata = $this->prakataModel->get();
        
        // Convert image path to URL if exists
        if (!empty($prakata['image'])) {
            $prakata['image'] = asset_url($prakata['image']);
        }
        
        echo json_encode([
            'success' => true,
            'data' => $prakata
        ]);
        exit;
    }
    
    /**
     * Update prakata
     */
    public function updatePrakata() {
        if (!$this->isPost()) {
            $this->redirect('/admin/management');
            return;
        }
        
        header('Content-Type: application/json');
        
        try {
            $title = sanitize($this->input('title'));
            $description = sanitize($this->input('description'));
            
            if (empty($title) || empty($description)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Judul dan deskripsi harus diisi'
                ]);
                exit;
            }
            
            // Validate description length (max 600 characters)
            if (strlen($description) > 600) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Deskripsi maksimal 600 karakter'
                ]);
                exit;
            }
            
            $data = [
                'title' => $title,
                'description' => $description,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Handle image upload if provided
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload = upload_file($_FILES['image'], 'prakata');
                
                if ($upload['success']) {
                    $data['image'] = $upload['path'];
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => $upload['message']
                    ]);
                    exit;
                }
            }
            
            $result = $this->prakataModel->updatePrakata($data);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Prakata berhasil diubah'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal mengubah prakata'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
        exit;
    }
    
    // ========== KEPALA SEKOLAH METHODS ==========
    
    /**
     * Get kepala sekolah data
     */
    public function getKepalaSekolah() {
        header('Content-Type: application/json');
        
        $kepalaSekolah = $this->kepalaSekolahModel->get();
        
        // Convert photo path to URL if exists
        if (!empty($kepalaSekolah['photo'])) {
            $kepalaSekolah['photo'] = asset_url($kepalaSekolah['photo']);
        }
        
        echo json_encode([
            'success' => true,
            'data' => $kepalaSekolah
        ]);
        exit;
    }
    
    /**
     * Update kepala sekolah
     */
    public function updateKepalaSekolah() {
        if (!$this->isPost()) {
            $this->redirect('/admin/management');
            return;
        }
        
        header('Content-Type: application/json');
        
        try {
            $name = sanitize($this->input('name'));
            
            if (empty($name)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Nama kepala sekolah harus diisi'
                ]);
                exit;
            }
            
            $data = [
                'name' => $name,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Check if user wants to remove photo
            $removePhoto = $this->input('remove_photo');
            if ($removePhoto === '1') {
                $data['photo'] = '';
            }
            // Handle photo upload if provided
            elseif (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $upload = upload_file($_FILES['photo'], 'kepala-sekolah');
                
                if ($upload['success']) {
                    $data['photo'] = $upload['path'];
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => $upload['message']
                    ]);
                    exit;
                }
            }
            
            $result = $this->kepalaSekolahModel->updatePrincipal($data);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Kepala sekolah berhasil diubah'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal mengubah kepala sekolah'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
        exit;
    }
    
    // ========== KARYAWAN METHODS ==========
    
    /**
     * Get karyawan by ID
     */
    public function getKaryawan($id) {
        header('Content-Type: application/json');
        
        $karyawan = $this->karyawanModel->getById($id);
        
        if (!$karyawan) {
            echo json_encode([
                'success' => false,
                'message' => 'Karyawan tidak ditemukan'
            ]);
            exit;
        }
        
        // Convert photo path to URL if exists
        if (!empty($karyawan['photo'])) {
            $karyawan['photo'] = asset_url($karyawan['photo']);
        }
        
        echo json_encode([
            'success' => true,
            'data' => $karyawan
        ]);
        exit;
    }
    
    /**
     * Create karyawan
     */
    public function createKaryawan() {
        if (!$this->isPost()) {
            $this->redirect('/admin/management');
            return;
        }
        
        header('Content-Type: application/json');
        
        try {
            $name = sanitize($this->input('name'));
            $role = sanitize($this->input('role'));
            
            if (empty($name) || empty($role)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Nama dan jabatan harus diisi'
                ]);
                exit;
            }
            
            $data = [
                'name' => $name,
                'role' => $role,
                'photo' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Handle photo upload if provided
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $upload = upload_file($_FILES['photo'], 'karyawan');
                
                if ($upload['success']) {
                    $data['photo'] = $upload['path'];
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => $upload['message']
                    ]);
                    exit;
                }
            }
            
            $result = $this->karyawanModel->createEmployee($data);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Karyawan berhasil ditambahkan'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal menambahkan karyawan'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
        exit;
    }
    
    /**
     * Update karyawan
     */
    public function updateKaryawan($id) {
        if (!$this->isPost()) {
            $this->redirect('/admin/management');
            return;
        }
        
        header('Content-Type: application/json');
        
        try {
            $name = sanitize($this->input('name'));
            $role = sanitize($this->input('role'));
            
            if (empty($name) || empty($role)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Nama dan jabatan harus diisi'
                ]);
                exit;
            }
            
            $data = [
                'name' => $name,
                'role' => $role,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Check if user wants to remove photo
            $removePhoto = $this->input('remove_photo');
            if ($removePhoto === '1') {
                $data['photo'] = '';
            }
            // Handle photo upload if provided
            elseif (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $upload = upload_file($_FILES['photo'], 'karyawan');
                
                if ($upload['success']) {
                    $data['photo'] = $upload['path'];
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => $upload['message']
                    ]);
                    exit;
                }
            }
            
            $result = $this->karyawanModel->updateEmployee($id, $data);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Karyawan berhasil diubah'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal mengubah karyawan'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
        exit;
    }
    
    /**
     * Delete karyawan
     */
    public function deleteKaryawan($id) {
        if (!$this->isPost()) {
            $this->redirect('/admin/management');
            return;
        }
        
        header('Content-Type: application/json');
        
        try {
            $result = $this->karyawanModel->deleteEmployee($id);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Karyawan berhasil dihapus'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal menghapus karyawan'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
        exit;
    }
    
    // ========== SCHEDULE METHODS ==========
    
    // Schedules
    public function schedules() {
        $data = [
            'schedules' => $this->scheduleModel->getAllOrdered(),
        ];
        
        $this->view('admin/management/schedules', $data);
    }
    
    public function createSchedule() {
        if (!$this->isPost()) {
            $this->redirect('/admin/management/schedules');
            return;
        }
        
        csrf_verify();
        
        $data = [
            'time_start' => $this->input('time_start'),
            'time_end' => $this->input('time_end'),
            'activity_name' => sanitize($this->input('activity_name')),
            'description' => sanitize($this->input('description')),
            'display_order' => $this->input('display_order', 0),
        ];
        
        if (empty($data['time_start']) || empty($data['activity_name'])) {
            flash('error', 'Time and activity name are required');
            $this->redirect('/admin/management/schedules');
            return;
        }
        
        $this->scheduleModel->create($data);
        
        flash('success', 'Schedule created successfully');
        $this->redirect('/admin/management/schedules');
    }
    
    public function updateSchedule($id) {
        if (!$this->isPost()) {
            $this->redirect('/admin/management/schedules');
            return;
        }
        
        csrf_verify();
        
        $data = [
            'time_start' => $this->input('time_start'),
            'time_end' => $this->input('time_end'),
            'activity_name' => sanitize($this->input('activity_name')),
            'description' => sanitize($this->input('description')),
            'display_order' => $this->input('display_order', 0),
        ];
        
        $this->scheduleModel->update($id, $data);
        
        flash('success', 'Schedule updated successfully');
        $this->redirect('/admin/management/schedules');
    }
    
    public function deleteSchedule($id) {
        csrf_verify();
        
        $this->scheduleModel->delete($id);
        
        flash('success', 'Schedule deleted successfully');
        $this->redirect('/admin/management/schedules');
    }
    
    // Awards
    public function awards() {
        $data = [
            'awards' => $this->awardModel->getAllOrdered(),
        ];
        
        $this->view('admin/management/awards', $data);
    }
    
    public function createAward() {
        if (!$this->isPost()) {
            $this->redirect('/admin/management/awards');
            return;
        }
        
        csrf_verify();
        
        $title = sanitize($this->input('title'));
        $description = sanitize($this->input('description'));
        $yearReceived = $this->input('year_received');
        $displayOrder = $this->input('display_order', 0);
        
        if (empty($title) || empty($yearReceived)) {
            flash('error', 'Title and year are required');
            $this->redirect('/admin/management/awards');
            return;
        }
        
        // Handle image upload
        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload = upload_file($_FILES['image'], 'awards');
            if ($upload['success']) {
                $image = $upload['path'];
            } else {
                flash('error', 'Image upload failed');
                $this->redirect('/admin/management/awards');
                return;
            }
        } else {
            flash('error', 'Award image is required');
            $this->redirect('/admin/management/awards');
            return;
        }
        
        $data = [
            'title' => $title,
            'description' => $description,
            'image' => $image,
            'year_received' => $yearReceived,
            'display_order' => $displayOrder,
        ];
        
        $this->awardModel->create($data);
        
        flash('success', 'Award created successfully');
        $this->redirect('/admin/management/awards');
    }
    
    public function updateAward($id) {
        if (!$this->isPost()) {
            $this->redirect('/admin/management/awards');
            return;
        }
        
        csrf_verify();
        
        $award = $this->awardModel->find($id);
        
        if (!$award) {
            flash('error', 'Award not found');
            $this->redirect('/admin/management/awards');
            return;
        }
        
        $title = sanitize($this->input('title'));
        $description = sanitize($this->input('description'));
        $yearReceived = $this->input('year_received');
        $displayOrder = $this->input('display_order', 0);
        
        // Handle image upload
        $image = $award['image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload = upload_file($_FILES['image'], 'awards');
            if ($upload['success']) {
                // Delete old image
                if ($image) {
                    delete_file($image);
                }
                $image = $upload['path'];
            }
        }
        
        $data = [
            'title' => $title,
            'description' => $description,
            'image' => $image,
            'year_received' => $yearReceived,
            'display_order' => $displayOrder,
        ];
        
        $this->awardModel->update($id, $data);
        
        flash('success', 'Award updated successfully');
        $this->redirect('/admin/management/awards');
    }
    
    public function deleteAward($id) {
        csrf_verify();
        
        $award = $this->awardModel->find($id);
        
        if ($award && $award['image']) {
            delete_file($award['image']);
        }
        
        $this->awardModel->delete($id);
        
        flash('success', 'Award deleted successfully');
        $this->redirect('/admin/management/awards');
    }
    
    // Social Media
    public function socialMedia() {
        $data = [
            'socialMedia' => $this->socialMediaModel->all('display_order ASC'),
        ];
        
        $this->view('admin/management/social-media', $data);
    }
    
    public function createSocialMedia() {
        if (!$this->isPost()) {
            $this->redirect('/admin/management/social-media');
            return;
        }
        
        csrf_verify();
        
        $data = [
            'platform' => sanitize($this->input('platform')),
            'account_name' => sanitize($this->input('account_name')),
            'url' => sanitize($this->input('url')),
            'display_order' => $this->input('display_order', 0),
            'is_active' => $this->input('is_active', 1),
        ];
        
        if (empty($data['platform']) || empty($data['account_name']) || empty($data['url'])) {
            flash('error', 'All fields are required');
            $this->redirect('/admin/management/social-media');
            return;
        }
        
        $this->socialMediaModel->create($data);
        
        flash('success', 'Social media account created successfully');
        $this->redirect('/admin/management/social-media');
    }
    
    public function updateSocialMedia($id) {
        if (!$this->isPost()) {
            $this->redirect('/admin/management/social-media');
            return;
        }
        
        csrf_verify();
        
        $data = [
            'platform' => sanitize($this->input('platform')),
            'account_name' => sanitize($this->input('account_name')),
            'url' => sanitize($this->input('url')),
            'display_order' => $this->input('display_order', 0),
            'is_active' => $this->input('is_active', 1),
        ];
        
        $this->socialMediaModel->update($id, $data);
        
        flash('success', 'Social media account updated successfully');
        $this->redirect('/admin/management/social-media');
    }
    
    public function deleteSocialMedia($id) {
        csrf_verify();
        
        $this->socialMediaModel->delete($id);
        
        flash('success', 'Social media account deleted successfully');
        $this->redirect('/admin/management/social-media');
    }
    
    // Settings
    public function settings() {
        $data = [
            'settings' => $this->settingModel->getAllGrouped(),
        ];
        
        $this->view('admin/management/settings', $data);
    }
    
    public function updateSettings() {
        if (!$this->isPost()) {
            $this->redirect('/admin/management/settings');
            return;
        }
        
        csrf_verify();
        
        $settings = $_POST['settings'] ?? [];
        
        foreach ($settings as $key => $value) {
            $this->settingModel->set($key, $value);
        }
        
        flash('success', 'Settings updated successfully');
        $this->redirect('/admin/management/settings');
    }
    
    // ========== FASILITAS METHODS ==========
    
    /**
     * Get single fasilitas data
     */
    public function getFasilitas($id) {
        header('Content-Type: application/json');
        
        $fasilitas = $this->fasilitasModel->getById($id);
        
        if (!$fasilitas) {
            echo json_encode([
                'success' => false,
                'message' => 'Fasilitas tidak ditemukan'
            ]);
            exit;
        }
        
        // Convert image path to URL
        if (!empty($fasilitas['image'])) {
            $fasilitas['image'] = asset_url($fasilitas['image']);
        }
        
        // Get cover image from gallery (the one with is_cover = 1)
        $galleryImages = $this->fasilitasModel->getGalleryImages($id);
        $fasilitas['cover_image'] = null;
        
        foreach ($galleryImages as $galleryItem) {
            if (!empty($galleryItem['is_cover']) && $galleryItem['is_cover'] == 1) {
                $fasilitas['cover_image'] = asset_url($galleryItem['image_path']);
                break;
            }
        }
        
        echo json_encode([
            'success' => true,
            'data' => $fasilitas
        ]);
        exit;
    }
    
    /**
     * Create new fasilitas
     */
    public function createFasilitas() {
        header('Content-Type: application/json');
        
        if (!$this->isPost()) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
            exit;
        }
        
        $name = $_POST['name'] ?? '';
        
        if (empty($name)) {
            echo json_encode([
                'success' => false,
                'message' => 'Nama fasilitas wajib diisi'
            ]);
            exit;
        }
        
        $data = ['name' => $name];
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = upload_file($_FILES['image'], 'fasilitas');
            
            if ($uploadResult['success']) {
                $data['image'] = $uploadResult['path'];
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => $uploadResult['error']
                ]);
                exit;
            }
        }
        
        $fasilitasId = $this->fasilitasModel->createFasilitas($data);
        
        if ($fasilitasId) {
            // If image was uploaded, also add it to fasilitas_gallery as cover
            if (!empty($data['image'])) {
                $this->fasilitasModel->addGalleryImage(
                    $fasilitasId,
                    $data['image'],
                    'Cover Image',
                    1
                );
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Fasilitas berhasil ditambahkan'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menambahkan fasilitas'
            ]);
        }
        exit;
    }
    
    /**
     * Update fasilitas
     */
    public function updateFasilitas($id) {
        header('Content-Type: application/json');
        
        if (!$this->isPost()) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
            exit;
        }
        
        $name = $_POST['name'] ?? '';
        
        if (empty($name)) {
            echo json_encode([
                'success' => false,
                'message' => 'Nama fasilitas wajib diisi'
            ]);
            exit;
        }
        
        $data = ['name' => $name];
        $fasilitas = $this->fasilitasModel->getById($id);
        
        if (!$fasilitas) {
            echo json_encode([
                'success' => false,
                'message' => 'Fasilitas tidak ditemukan'
            ]);
            exit;
        }
        
        // Handle remove photo flag
        if (isset($_POST['remove_photo']) && $_POST['remove_photo'] === '1') {
            if (!empty($fasilitas['image'])) {
                delete_file($fasilitas['image']);
            }
            $data['image'] = null;
        }
        // Handle new image upload
        else if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // Delete old image if exists
            if (!empty($fasilitas['image'])) {
                delete_file($fasilitas['image']);
            }
            
            $uploadResult = upload_file($_FILES['image'], 'fasilitas');
            
            if ($uploadResult['success']) {
                $data['image'] = $uploadResult['path'];
                
                // IMPORTANT: Also add this image to fasilitas_gallery as cover
                // First, clear all existing cover flags
                $this->fasilitasModel->clearCoverFlags($id);
                
                // Then add the new image to gallery with is_cover = 1
                $this->fasilitasModel->addGalleryImage(
                    $id,
                    $uploadResult['path'],
                    'Cover Image',
                    1
                );
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => $uploadResult['error']
                ]);
                exit;
            }
        }
        
        $result = $this->fasilitasModel->updateFasilitas($id, $data);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Fasilitas berhasil diubah'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal mengubah fasilitas'
            ]);
        }
        exit;
    }
    
    /**
     * Delete fasilitas
     */
    public function deleteFasilitas($id) {
        header('Content-Type: application/json');
        
        if (!$this->isPost()) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
            exit;
        }
        
        $fasilitas = $this->fasilitasModel->getById($id);
        
        if (!$fasilitas) {
            echo json_encode([
                'success' => false,
                'message' => 'Fasilitas tidak ditemukan'
            ]);
            exit;
        }
        
        // Delete main image if exists
        if (!empty($fasilitas['image'])) {
            delete_file($fasilitas['image']);
        }
        
        // Get gallery images and delete them
        $galleryImages = $this->fasilitasModel->getGalleryImages($id);
        foreach ($galleryImages as $image) {
            if (!empty($image['image_path'])) {
                delete_file($image['image_path']);
            }
        }
        
        $result = $this->fasilitasModel->deleteFasilitas($id);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Fasilitas berhasil dihapus'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menghapus fasilitas'
            ]);
        }
        exit;
    }
    
    /**
     * Get gallery images for fasilitas
     */
    public function getGalleryImages($id) {
        header('Content-Type: application/json');
        
        $images = $this->fasilitasModel->getGalleryImages($id);
        
        // Convert paths to URLs
        foreach ($images as &$image) {
            if (!empty($image['image_path'])) {
                $image['image_url'] = asset_url($image['image_path']);
            }
        }
        
        echo json_encode([
            'success' => true,
            'data' => $images
        ]);
        exit;
    }
    
    /**
     * Upload gallery images
     */
    public function uploadGalleryImages($id) {
        header('Content-Type: application/json');
        
        if (!$this->isPost()) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
            exit;
        }
        
        $fasilitas = $this->fasilitasModel->getById($id);
        
        if (!$fasilitas) {
            echo json_encode([
                'success' => false,
                'message' => 'Fasilitas tidak ditemukan'
            ]);
            exit;
        }
        
        if (!isset($_FILES['images']) || empty($_FILES['images']['name'][0])) {
            echo json_encode([
                'success' => false,
                'message' => 'Tidak ada gambar yang diunggah'
            ]);
            exit;
        }
        
        $uploadedCount = 0;
        $errors = [];
        
        // Handle multiple file upload
        $files = $_FILES['images'];
        $fileCount = count($files['name']);
        
        for ($i = 0; $i < $fileCount; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $file = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i]
                ];
                
                $uploadResult = upload_file($file, 'fasilitas/gallery');
                
                if ($uploadResult['success']) {
                    $this->fasilitasModel->addGalleryImage($id, $uploadResult['path']);
                    $uploadedCount++;
                } else {
                    $errors[] = $uploadResult['error'];
                }
            }
        }
        
        if ($uploadedCount > 0) {
            echo json_encode([
                'success' => true,
                'message' => "$uploadedCount gambar berhasil diunggah"
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal mengunggah gambar: ' . implode(', ', $errors)
            ]);
        }
        exit;
    }
    
    /**
     * Delete gallery image
     */
    public function deleteGalleryImage($fasilitasId, $imageId) {
        header('Content-Type: application/json');
        
        if (!$this->isPost()) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
            exit;
        }
        
        // Get image data using model method
        $image = $this->fasilitasModel->getGalleryImageById($imageId);
        
        if (!$image) {
            echo json_encode([
                'success' => false,
                'message' => 'Gambar tidak ditemukan'
            ]);
            exit;
        }
        
        // Check if this is a cover image
        $wasCover = !empty($image['is_cover']) && $image['is_cover'] == 1;
        
        // Delete file
        if (!empty($image['image_path'])) {
            delete_file($image['image_path']);
        }
        
        $result = $this->fasilitasModel->deleteGalleryImage($imageId);
        
        if ($result) {
            // If deleted image was cover, set next image as new cover
            if ($wasCover) {
                // Get remaining gallery images for this fasilitas
                $remainingImages = $this->fasilitasModel->getGalleryImages($fasilitasId);
                
                if (!empty($remainingImages)) {
                    // Set first remaining image as new cover
                    $newCoverImage = $remainingImages[0];
                    
                    // Update gallery: set is_cover = 1
                    $this->fasilitasModel->updateGalleryImage($newCoverImage['id'], [
                        'is_cover' => 1
                    ]);
                    
                    // Update fasilitas.image with new cover
                    $this->fasilitasModel->updateFasilitas($fasilitasId, [
                        'image' => $newCoverImage['image_path']
                    ]);
                } else {
                    // No more images, clear fasilitas.image
                    $this->fasilitasModel->updateFasilitas($fasilitasId, [
                        'image' => null
                    ]);
                }
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Gambar berhasil dihapus'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menghapus gambar'
            ]);
        }
        exit;
    }
    
    /**
     * Store single gallery image with description and is_cover flag
     */
    public function storeGalleryImage($id) {
        header('Content-Type: application/json');
        
        if (!$this->isPost()) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
            exit;
        }
        
        $fasilitas = $this->fasilitasModel->getById($id);
        
        if (!$fasilitas) {
            echo json_encode([
                'success' => false,
                'message' => 'Fasilitas tidak ditemukan'
            ]);
            exit;
        }
        
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode([
                'success' => false,
                'message' => 'Gambar harus diupload'
            ]);
            exit;
        }
        
        $description = $_POST['description'] ?? '';
        $setAsCover = isset($_POST['set_as_cover']) && $_POST['set_as_cover'] == '1';
        
        // Upload image
        $uploadResult = upload_file($_FILES['image'], 'fasilitas/gallery');
        
        if (!$uploadResult['success']) {
            echo json_encode([
                'success' => false,
                'message' => $uploadResult['error']
            ]);
            exit;
        }
        
        // If set as cover, clear previous cover flags and update fasilitas.image
        if ($setAsCover) {
            // Clear all is_cover flags
            $this->fasilitasModel->clearCoverFlags($id);
            
            // Don't delete old fasilitas.image file because it's still referenced in gallery table
            // Just update the pointer to the new cover image
            
            // Update fasilitas.image
            $this->fasilitasModel->updateFasilitas($id, ['image' => $uploadResult['path']]);
        }
        
        // Add to gallery
        $result = $this->fasilitasModel->addGalleryImage($id, $uploadResult['path'], $description, $setAsCover ? 1 : 0);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Foto berhasil ditambahkan ke galeri'
            ]);
        } else {
            // Rollback upload if database insert fails
            delete_file($uploadResult['path']);
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menyimpan data ke database'
            ]);
        }
        exit;
    }
    
    /**
     * Update gallery image
     */
    public function updateGalleryImage($fasilitasId, $imageId) {
        header('Content-Type: application/json');
        
        if (!$this->isPost()) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
            exit;
        }
        
        $galleryImage = $this->fasilitasModel->getGalleryImageById($imageId);
        
        if (!$galleryImage) {
            echo json_encode([
                'success' => false,
                'message' => 'Gambar gallery tidak ditemukan'
            ]);
            exit;
        }
        
        $updateData = [];
        
        // Handle description
        if (isset($_POST['description'])) {
            $updateData['description'] = $_POST['description'];
        }
        
        // Handle is_cover
        $setAsCover = isset($_POST['is_cover']) && $_POST['is_cover'] == '1';
        $updateData['is_cover'] = $setAsCover ? 1 : 0;
        
        // Handle new image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = upload_file($_FILES['image'], 'fasilitas/gallery');
            
            if (!$uploadResult['success']) {
                echo json_encode([
                    'success' => false,
                    'message' => $uploadResult['error']
                ]);
                exit;
            }
            
            // Delete old image
            if (!empty($galleryImage['image_path'])) {
                delete_file($galleryImage['image_path']);
            }
            
            $updateData['image_path'] = $uploadResult['path'];
        }
        
        // If set as cover, clear previous cover flags and update fasilitas.image
        if ($setAsCover) {
            // Clear all is_cover flags
            $this->fasilitasModel->clearCoverFlags($fasilitasId);
            
            // Don't delete old fasilitas.image file because it's still referenced in gallery table
            // Just update the pointer to the new cover image
            
            // Update fasilitas.image with the gallery image path
            $imagePath = $updateData['image_path'] ?? $galleryImage['image_path'];
            $this->fasilitasModel->updateFasilitas($fasilitasId, ['image' => $imagePath]);
        }
        
        // Update gallery image
        $result = $this->fasilitasModel->updateGalleryImage($imageId, $updateData);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Foto berhasil diubah'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal mengubah foto'
            ]);
        }
        exit;
    }
    
    /**
     * Update fasilitas.image directly (for program image editing)
     */
    public function updateFasilitasImage($id) {
        header('Content-Type: application/json');
        
        if (!$this->isPost()) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
            exit;
        }
        
        $fasilitas = $this->fasilitasModel->getById($id);
        
        if (!$fasilitas) {
            echo json_encode([
                'success' => false,
                'message' => 'Fasilitas tidak ditemukan'
            ]);
            exit;
        }
        
        $updateData = [];
        
        // Handle new image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = upload_file($_FILES['image'], 'fasilitas');
            
            if (!$uploadResult['success']) {
                echo json_encode([
                    'success' => false,
                    'message' => $uploadResult['error']
                ]);
                exit;
            }
            
            // Delete old image
            if (!empty($fasilitas['image'])) {
                delete_file($fasilitas['image']);
            }
            
            $updateData['image'] = $uploadResult['path'];
        }
        
        // Handle is_cover (if setting fasilitas.image as cover)
        $setAsCover = isset($_POST['is_cover']) && $_POST['is_cover'] == '1';
        if ($setAsCover) {
            // Clear all gallery is_cover flags
            $this->fasilitasModel->clearCoverFlags($id);
        }
        
        if (!empty($updateData)) {
            $result = $this->fasilitasModel->updateFasilitas($id, $updateData);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Gambar fasilitas berhasil diubah'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal mengubah gambar fasilitas'
                ]);
            }
        } else {
            echo json_encode([
                'success' => true,
                'message' => 'Tidak ada perubahan'
            ]);
        }
        exit;
    }
    
    /**
     * Delete fasilitas.image
     */
    public function deleteFasilitasImage($id) {
        header('Content-Type: application/json');
        
        if (!$this->isPost()) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
            exit;
        }
        
        $fasilitas = $this->fasilitasModel->getById($id);
        
        if (!$fasilitas) {
            echo json_encode([
                'success' => false,
                'message' => 'Fasilitas tidak ditemukan'
            ]);
            exit;
        }
        
        // Delete image file
        if (!empty($fasilitas['image'])) {
            delete_file($fasilitas['image']);
        }
        
        // Update database
        $result = $this->fasilitasModel->updateFasilitas($id, ['image' => null]);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Gambar fasilitas berhasil dihapus'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menghapus gambar fasilitas'
            ]);
        }
        exit;
    }
}

