<?php
/**
 * Settings Controller
 * Handles school settings management
 */
class SettingsController extends Controller {
    private $settingModel;
    private $registrationFieldModel;
    private $registrationSettingModel;
    private $eventModel;
    private $faqModel;
    private $testimonialModel;
    private $highlightProgramModel;
    private $emailSettingModel;
    
    public function __construct() {
        parent::__construct();
        $this->middleware(AuthMiddleware::class);
        $this->settingModel = new Setting();
        $this->registrationFieldModel = new RegistrationField();
        $this->registrationSettingModel = new RegistrationSetting();
        $this->eventModel = new Event();
        $this->faqModel = new Faq();
        $this->testimonialModel = new Testimonial();
        $this->highlightProgramModel = new HighlightProgram();
        $this->emailSettingModel = new EmailSetting();
    }
    
    // Settings Index
    public function index() {
        // Get registration fields and settings
        $fields = $this->registrationFieldModel->getActiveFields();
        $settings = $this->registrationSettingModel->getAll();
        
        $data = [
            'currentPage' => 'settings',
            'fields' => $fields,
            'whatsappNumber' => $settings['whatsapp_number'] ?? '',
            'whatsappTemplate' => $settings['whatsapp_template'] ?? ''
        ];
        
        $this->view('admin/settings/index', $data);
    }
    
    // AJAX: Get all registration fields
    public function getFields() {
        header('Content-Type: application/json');
        
        try {
            $fields = $this->registrationFieldModel->getActiveFields();
            echo json_encode(['success' => true, 'data' => $fields]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // AJAX: Create new field
    public function createField() {
        header('Content-Type: application/json');
        
        try {
            $data = [
                'field_name' => $_POST['field_name'] ?? '',
                'field_label' => $_POST['field_label'] ?? '',
                'field_type' => $_POST['field_type'] ?? 'text',
                'placeholder_text' => $_POST['placeholder_text'] ?? '',
                'is_required' => isset($_POST['is_required']) ? 1 : 0,
                'order_index' => $this->registrationFieldModel->getNextOrderIndex()
            ];
            
            $result = $this->registrationFieldModel->create($data);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Field berhasil ditambahkan']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menambahkan field']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // AJAX: Update field
    public function updateField() {
        header('Content-Type: application/json');
        
        try {
            $id = $_POST['id'] ?? 0;
            
            // Get existing field to preserve order_index
            $existingField = $this->registrationFieldModel->getById($id);
            if (!$existingField) {
                echo json_encode(['success' => false, 'message' => 'Field tidak ditemukan']);
                return;
            }
            
            $data = [
                'field_name' => $_POST['field_name'] ?? '',
                'field_label' => $_POST['field_label'] ?? '',
                'field_type' => $_POST['field_type'] ?? 'text',
                'placeholder_text' => $_POST['placeholder_text'] ?? '',
                'is_required' => isset($_POST['is_required']) ? 1 : 0,
                // Preserve existing order_index when editing
                'order_index' => $existingField['order_index']
            ];
            
            $result = $this->registrationFieldModel->update($id, $data);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Field berhasil diupdate']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal mengupdate field']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // AJAX: Delete field
    public function deleteField() {
        header('Content-Type: application/json');
        
        try {
            $id = $_POST['id'] ?? 0;
            $result = $this->registrationFieldModel->delete($id);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Field berhasil dihapus']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menghapus field']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // AJAX: Update field order
    public function updateFieldOrder() {
        header('Content-Type: application/json');
        
        try {
            $orders = $_POST['orders'] ?? '[]';
            // Decode JSON string to array
            $ordersArray = json_decode($orders, true);
            
            if (!is_array($ordersArray)) {
                echo json_encode(['success' => false, 'message' => 'Format orders tidak valid']);
                return;
            }
            
            $result = $this->registrationFieldModel->updateOrder($ordersArray);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Urutan field berhasil diupdate']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal mengupdate urutan field']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // AJAX: Save registration settings
    public function saveRegistrationSettings() {
        header('Content-Type: application/json');
        
        try {
            $whatsappNumber = $_POST['whatsapp_number'] ?? '';
            $whatsappTemplate = $_POST['whatsapp_template'] ?? '';
            
            $this->registrationSettingModel->set('whatsapp_number', $whatsappNumber);
            $this->registrationSettingModel->set('whatsapp_template', $whatsappTemplate);
            
            echo json_encode(['success' => true, 'message' => 'Pengaturan berhasil disimpan']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // ===== EVENT METHODS =====
    
    // AJAX: Get all events
    public function getEvents() {
        header('Content-Type: application/json');
        
        try {
            $events = $this->eventModel->getAllEvents();
            echo json_encode(['success' => true, 'data' => $events]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // AJAX: Create event
    public function createEvent() {
        header('Content-Type: application/json');
        
        try {
            $data = [
                'event_date' => $_POST['event_date'] ?? '',
                'event_time' => $_POST['event_time'] ?? '',
                'event_name' => $_POST['event_name'] ?? '',
                'event_place' => $_POST['event_place'] ?? '',
                'event_url' => $_POST['event_url'] ?? ''
            ];
            
            // Validate required fields
            if (empty($data['event_date']) || empty($data['event_time']) || empty($data['event_name']) || empty($data['event_place'])) {
                echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi']);
                return;
            }
            
            $result = $this->eventModel->createEvent($data);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Event berhasil ditambahkan']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menambahkan event']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // AJAX: Update event
    public function updateEvent($id) {
        header('Content-Type: application/json');
        
        try {
            $data = [
                'event_date' => $_POST['event_date'] ?? '',
                'event_time' => $_POST['event_time'] ?? '',
                'event_name' => $_POST['event_name'] ?? '',
                'event_place' => $_POST['event_place'] ?? '',
                'event_url' => $_POST['event_url'] ?? '',
                'is_public' => isset($_POST['is_public']) ? $_POST['is_public'] : null
            ];
            
            // Validate required fields
            if (empty($data['event_date']) || empty($data['event_time']) || empty($data['event_name']) || empty($data['event_place'])) {
                echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi']);
                return;
            }
            
            $result = $this->eventModel->updateEvent($id, $data);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Event berhasil diperbarui']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal memperbarui event']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // AJAX: Delete event
    public function deleteEvent($id) {
        header('Content-Type: application/json');
        
        try {
            $result = $this->eventModel->deleteEvent($id);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Event berhasil dihapus']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menghapus event']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // ===== FAQ METHODS =====
    
    // AJAX: Get all FAQs
    public function getFaqs() {
        header('Content-Type: application/json');
        
        try {
            $faqs = $this->faqModel->getAll();
            echo json_encode(['success' => true, 'data' => $faqs]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // AJAX: Create FAQ
    public function createFaq() {
        header('Content-Type: application/json');
        
        try {
            $data = [
                'question' => $_POST['faq_question'] ?? '',
                'answer' => $_POST['faq_answer'] ?? ''
            ];
            
            // Validate required fields
            if (empty($data['question']) || empty($data['answer'])) {
                echo json_encode(['success' => false, 'message' => 'Pertanyaan dan jawaban wajib diisi']);
                return;
            }
            
            $result = $this->faqModel->create($data);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'FAQ berhasil ditambahkan']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menambahkan FAQ']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // AJAX: Update FAQ
    public function updateFaq($id) {
        header('Content-Type: application/json');
        
        try {
            $data = [
                'question' => $_POST['faq_question'] ?? '',
                'answer' => $_POST['faq_answer'] ?? ''
            ];
            
            // Validate required fields
            if (empty($data['question']) || empty($data['answer'])) {
                echo json_encode(['success' => false, 'message' => 'Pertanyaan dan jawaban wajib diisi']);
                return;
            }
            
            $result = $this->faqModel->update($id, $data);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'FAQ berhasil diperbarui']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal memperbarui FAQ']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // AJAX: Delete FAQ
    public function deleteFaq($id) {
        header('Content-Type: application/json');
        
        try {
            $result = $this->faqModel->delete($id);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'FAQ berhasil dihapus']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menghapus FAQ']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // AJAX: Update FAQ order
    public function updateFaqOrder() {
        header('Content-Type: application/json');
        
        try {
            $orders = $_POST['orders'] ?? '[]';
            // Decode JSON string to array
            $ordersArray = json_decode($orders, true);
            
            if (!is_array($ordersArray)) {
                echo json_encode(['success' => false, 'message' => 'Format orders tidak valid']);
                return;
            }
            
            $result = $this->faqModel->updateOrder($ordersArray);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Urutan FAQ berhasil diupdate']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal mengupdate urutan FAQ']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // ===== TESTIMONIAL METHODS =====
    
    // AJAX: Get all testimonials
    public function getTestimonials() {
        header('Content-Type: application/json');
        
        try {
            $testimonials = $this->testimonialModel->getAll();
            echo json_encode(['success' => true, 'data' => $testimonials]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // AJAX: Create testimonial
    public function createTestimonial() {
        header('Content-Type: application/json');
        
        try {
            $data = [
                'parent_name' => $_POST['parent_name'] ?? '',
                'child_name' => $_POST['child_name'] ?? '',
                'testimonial_text' => $_POST['testimonial_text'] ?? '',
                'highlight_text' => $_POST['highlight_text'] ?? ''
            ];
            
            // Validate required fields
            if (empty($data['parent_name']) || empty($data['child_name']) || 
                empty($data['testimonial_text']) || empty($data['highlight_text'])) {
                echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi']);
                return;
            }
            
            // Handle image upload if provided
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->handleImageUpload($_FILES['image'], 'testimonials');
                if ($uploadResult['success']) {
                    $data['image'] = $uploadResult['filename'];
                } else {
                    echo json_encode(['success' => false, 'message' => $uploadResult['message']]);
                    return;
                }
            }
            
            $result = $this->testimonialModel->create($data);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Testimoni berhasil ditambahkan']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menambahkan testimoni']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // AJAX: Update testimonial
    public function updateTestimonial($id) {
        header('Content-Type: application/json');
        
        try {
            $data = [
                'parent_name' => $_POST['parent_name'] ?? '',
                'child_name' => $_POST['child_name'] ?? '',
                'testimonial_text' => $_POST['testimonial_text'] ?? '',
                'highlight_text' => $_POST['highlight_text'] ?? ''
            ];
            
            // Validate required fields
            if (empty($data['parent_name']) || empty($data['child_name']) || 
                empty($data['testimonial_text']) || empty($data['highlight_text'])) {
                echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi']);
                return;
            }
            
            // Handle image upload if provided
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->handleImageUpload($_FILES['image'], 'testimonials');
                if ($uploadResult['success']) {
                    // Delete old image if exists
                    $existingTestimonial = $this->testimonialModel->getById($id);
                    if ($existingTestimonial && !empty($existingTestimonial['image'])) {
                        $oldImagePath = UPLOAD_PATH . '/testimonials/' . $existingTestimonial['image'];
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                    $data['image'] = $uploadResult['filename'];
                } else {
                    echo json_encode(['success' => false, 'message' => $uploadResult['message']]);
                    return;
                }
            }
            
            $result = $this->testimonialModel->update($id, $data);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Testimoni berhasil diperbarui']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal memperbarui testimoni']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // AJAX: Delete testimonial
    public function deleteTestimonial($id) {
        header('Content-Type: application/json');
        
        try {
            // Get testimonial to delete image
            $testimonial = $this->testimonialModel->getById($id);
            
            $result = $this->testimonialModel->delete($id);
            
            if ($result) {
                // Delete image file if exists
                if ($testimonial && !empty($testimonial['image'])) {
                    $imagePath = UPLOAD_PATH . '/testimonials/' . $testimonial['image'];
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                echo json_encode(['success' => true, 'message' => 'Testimoni berhasil dihapus']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menghapus testimoni']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // AJAX: Update testimonial order
    public function updateTestimonialOrder() {
        header('Content-Type: application/json');
        
        try {
            $orders = $_POST['orders'] ?? '[]';
            // Decode JSON string to array
            $ordersArray = json_decode($orders, true);
            
            if (!is_array($ordersArray)) {
                echo json_encode(['success' => false, 'message' => 'Format orders tidak valid']);
                return;
            }
            
            $result = $this->testimonialModel->updateOrder($ordersArray);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Urutan testimoni berhasil diupdate']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal mengupdate urutan testimoni']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // Helper: Handle image upload
    private function handleImageUpload($file, $folder) {
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        // Validate file type
        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'message' => 'Tipe file tidak valid. Hanya JPG, PNG, dan GIF yang diizinkan.'];
        }
        
        // Validate file size
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'Ukuran file terlalu besar. Maksimal 2MB.'];
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        
        // Create folder if not exists
        $uploadDir = UPLOAD_PATH . '/' . $folder;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Move uploaded file
        $destination = $uploadDir . '/' . $filename;
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return ['success' => true, 'filename' => $filename];
        } else {
            return ['success' => false, 'message' => 'Gagal mengupload file.'];
        }
    }
    
    // ===== HIGHLIGHT PROGRAM METHODS =====
    
    // AJAX: Get all highlight programs
    public function getHighlightPrograms() {
        header('Content-Type: application/json');
        
        try {
            $highlights = $this->highlightProgramModel->getAll();
            echo json_encode(['success' => true, 'data' => $highlights]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // AJAX: Get all available programs (programs_tahun that are active)
    public function getAvailablePrograms() {
        header('Content-Type: application/json');
        
        try {
            // Get all active programs from programs_tahun
            $query = "SELECT id, name, description, image 
                      FROM programs_tahun 
                      WHERE is_active = 1 
                      ORDER BY display_order ASC, id ASC";
            $db = Database::getInstance();
            $programs = $db->fetchAll($query);
            
            echo json_encode(['success' => true, 'data' => $programs]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // AJAX: Replace highlight program
    public function replaceHighlightProgram($highlightId) {
        header('Content-Type: application/json');
        
        try {
            $newProgramId = $_POST['program_id'] ?? 0;
            
            if (empty($newProgramId)) {
                echo json_encode(['success' => false, 'message' => 'Program ID tidak valid']);
                return;
            }
            
            $result = $this->highlightProgramModel->replaceHighlight($highlightId, $newProgramId);
            echo json_encode($result);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // AJAX: Remove highlight program
    public function removeHighlightProgram($highlightId) {
        header('Content-Type: application/json');
        
        try {
            $result = $this->highlightProgramModel->removeHighlight($highlightId);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Highlight program berhasil dihapus']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menghapus highlight program']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // AJAX: Update highlight program order
    public function updateHighlightProgramOrder() {
        header('Content-Type: application/json');
        
        try {
            $orders = $_POST['orders'] ?? '[]';
            // Decode JSON string to array
            $ordersArray = json_decode($orders, true);
            
            if (!is_array($ordersArray)) {
                echo json_encode(['success' => false, 'message' => 'Format orders tidak valid']);
                return;
            }
            
            $result = $this->highlightProgramModel->updateOrder($ordersArray);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Urutan highlight program berhasil diupdate']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal mengupdate urutan highlight program']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // Email Settings
    public function emailSettings() {
        $emailConfig = $this->emailSettingModel->getConfig();
        
        $data = [
            'currentPage' => 'settings',
            'emailConfig' => $emailConfig
        ];
        
        $this->view('admin/settings/email', $data);
    }
    
    public function updateEmailSettings() {
        header('Content-Type: application/json');
        
        if (!$this->isPost()) {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        try {
            $username = $this->input('smtp_username');
            $password = $this->input('smtp_password');
            $isEnabled = $this->input('is_enabled') === '1' ? 1 : 0;
            
            // Validate
            if ($isEnabled && (empty($username) || empty($password))) {
                echo json_encode(['success' => false, 'message' => 'Email dan password wajib diisi jika email diaktifkan']);
                return;
            }
            
            // Test connection if enabled
            if ($isEnabled) {
                $testResult = $this->emailSettingModel->testConnection($username, $password);
                if (!$testResult) {
                    echo json_encode(['success' => false, 'message' => 'Koneksi SMTP gagal! Periksa email dan App Password Anda.']);
                    return;
                }
            }
            
            // Save config
            $data = [
                'smtp_username' => $username,
                'smtp_password' => $password,
                'is_enabled' => $isEnabled
            ];
            
            $result = $this->emailSettingModel->updateConfig($data);
            
            if ($result) {
                // Check if test email should be sent
                $testEmail = $this->input('test_email');
                $message = 'Konfigurasi email berhasil disimpan';
                
                if (!empty($testEmail) && filter_var($testEmail, FILTER_VALIDATE_EMAIL) && $isEnabled) {
                    // Send test email automatically
                    $subject = 'Test Email - ' . APP_NAME;
                    $emailMessage = "
                    <html>
                    <body style='font-family: Arial, sans-serif; line-height: 1.6;'>
                        <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                            <h2 style='color: #C92C2F;'>✅ Email SMTP Berhasil Dikonfigurasi!</h2>
                            <p>Selamat! Sistem email SMTP Anda sudah berfungsi dengan baik.</p>
                            <p><strong>Waktu pengiriman:</strong> " . date('Y-m-d H:i:s') . "</p>
                            <p>Ini adalah email test otomatis yang dikirim setelah konfigurasi SMTP berhasil disimpan.</p>
                            <hr style='border: 1px solid #eee; margin: 20px 0;'>
                            <p style='color: #666; font-size: 12px;'>
                                Email ini dikirim dari sistem " . APP_NAME . " menggunakan Gmail SMTP ({$username})
                            </p>
                        </div>
                    </body>
                    </html>
                    ";
                    
                    require_once ROOT_PATH . '/app/helpers/email.php';
                    $emailSent = send_email($testEmail, $subject, $emailMessage);
                    
                    if ($emailSent) {
                        $message = 'Konfigurasi email berhasil disimpan dan test email telah dikirim ke ' . $testEmail;
                    } else {
                        $message = 'Konfigurasi email berhasil disimpan tetapi test email gagal dikirim';
                    }
                }
                
                echo json_encode(['success' => true, 'message' => $message]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menyimpan konfigurasi email']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function testEmail() {
        header('Content-Type: application/json');
        
        if (!$this->isPost()) {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        try {
            $testEmail = $this->input('test_email');
            
            if (empty($testEmail) || !filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'Email tidak valid']);
                return;
            }
            
            // Send test email
            $subject = "Test Email - " . APP_NAME;
            $message = "<h2>Test Email Berhasil!</h2><p>Ini adalah email test dari " . APP_NAME . ".</p><p>Konfigurasi SMTP Anda berfungsi dengan baik.</p>";
            
            $result = send_email($testEmail, $subject, $message);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Email test berhasil dikirim! Silakan cek inbox Anda.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal mengirim email test']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}


