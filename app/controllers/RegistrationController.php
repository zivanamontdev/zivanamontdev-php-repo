<?php
/**
 * Admin Registrations Controller
 */
class RegistrationController extends Controller {
    private $registrationModel;
    
    public function __construct() {
        parent::__construct();
        $this->middleware(AuthMiddleware::class);
        $this->registrationModel = new Registration();
    }
    
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        $search = $_GET['search'] ?? '';
        
        $registrations = $this->registrationModel->getPaginated($page, $perPage, $search);
        $total = $this->registrationModel->countRegistrations($search);
        $totalPages = ceil($total / $perPage);
        
        $data = [
            'registrations' => $registrations,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'search' => $search,
        ];
        
        $this->view('admin/registrations/index', $data);
    }
    
    public function show($id) {
        $registration = $this->registrationModel->findRegistration($id);
        
        if (!$registration) {
            flash('error', 'Data pendaftar tidak ditemukan');
            Router::redirect('/admin/registrations');
            return;
        }
        
        $this->view('admin/registrations/show', ['registration' => $registration]);
    }
    
    public function delete($id) {
        $registration = $this->registrationModel->findRegistration($id);
        
        if (!$registration) {
            flash('error', 'Data pendaftar tidak ditemukan');
            Router::redirect('/admin/registrations');
            return;
        }
        
        if ($this->registrationModel->deleteRegistration($id)) {
            flash('success', 'Data pendaftar berhasil dihapus');
        } else {
            flash('error', 'Gagal menghapus data pendaftar');
        }
        
        Router::redirect('/admin/registrations');
    }
}
