<?php
/**
 * Settings Controller
 * Handles school settings management
 */
class SettingsController extends Controller {
    private $settingModel;
    private $registrationFieldModel;
    private $registrationSettingModel;
    
    public function __construct() {
        parent::__construct();
        $this->middleware(AuthMiddleware::class);
        $this->settingModel = new Setting();
        $this->registrationFieldModel = new RegistrationField();
        $this->registrationSettingModel = new RegistrationSetting();
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
}
