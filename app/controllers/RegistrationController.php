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
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $search = $_GET['search'] ?? '';
        
        $total = $this->registrationModel->countRegistrations($search);
        $totalPages = max(1, (int)ceil($total / $perPage));
        $page = min($page, $totalPages);
        $registrations = $this->registrationModel->getPaginated($page, $perPage, $search);
        
        $data = [
            'registrations' => $registrations,
            'registrationPage' => $page,
            'perPage' => $perPage,
            'firstRecord' => $total > 0 ? ($page - 1) * $perPage + 1 : 0,
            'lastRecord' => min($page * $perPage, $total),
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
