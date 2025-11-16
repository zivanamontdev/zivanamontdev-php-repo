<?php
/**
 * Admin Dashboard Controller
 */
class DashboardController extends Controller {
    private $analyticsModel;
    private $registrationModel;
    
    public function __construct() {
        parent::__construct();
        $this->middleware(AuthMiddleware::class);
        $this->analyticsModel = new Analytics();
        $this->registrationModel = new Registration();
    }
    
    public function index() {
        $data = [
            'totalVisits' => $this->analyticsModel->getTotalVisits(30),
            'visitsByDate' => $this->analyticsModel->getVisitsByDate(7),
            'topPages' => $this->analyticsModel->getTopPages(5),
            'deviceStats' => $this->analyticsModel->getDeviceStats(),
            'recentRegistrations' => $this->registrationModel->getRecent(5),
            'totalRegistrations' => $this->registrationModel->count(),
        ];
        
        $this->view('admin/dashboard/index', $data);
    }
}
