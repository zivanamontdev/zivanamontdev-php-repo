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
        // Get period from query param (default: month)
        $period = $_GET['period'] ?? 'month';
        
        // Get analytics data
        $popularPages = $this->analyticsModel->getPopularPages($period, 5);
        $locationStats = $this->analyticsModel->getTopLocations($period, 5);
        $hourlyData = $this->analyticsModel->getHourlyViews($period);
        
        // Get stats for cards
        $totalViews = $this->analyticsModel->getTotalViews($period);
        $uniqueVisitors = $this->analyticsModel->getUniqueVisitors($period);
        $totalRegistrations = $this->registrationModel->count();
        $recentRegistrations = $this->registrationModel->getRecent(5);
        
        // Calculate percentage changes (dummy for now - you can implement comparison logic)
        $viewsChange = '+12%';
        $visitorsChange = '+8%';
        $registrationsChange = '+24%';
        
        $data = [
            'popularPages' => $popularPages,
            'locationStats' => $locationStats,
            'hourlyData' => $hourlyData,
            'totalViews' => $totalViews,
            'uniqueVisitors' => $uniqueVisitors,
            'totalRegistrations' => $totalRegistrations,
            'viewsChange' => $viewsChange,
            'visitorsChange' => $visitorsChange,
            'registrationsChange' => $registrationsChange,
            'recentRegistrations' => $recentRegistrations,
            'currentPeriod' => $period
        ];
        
        // Check if AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            // Return JSON for AJAX requests
            header('Content-Type: application/json');
            echo json_encode($data);
            exit;
        }
        
        $this->view('admin/dashboard/index', $data);
    }

    public function refreshLocations() {
        if (!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Metode tidak diizinkan'], 405);
            return;
        }
        $token = $_POST['csrf_token'] ?? null;
        if (!is_string($token) || !validateCsrfToken($token)) {
            $this->json(['success' => false, 'message' => 'Sesi kedaluwarsa. Muat ulang halaman lalu coba lagi.'], 403);
            return;
        }
        $cursor = $_POST['cursor'] ?? '';
        if (!is_string($cursor) || strlen($cursor) > 45) {
            $this->json(['success' => false, 'message' => 'Permintaan tidak valid', 'csrf_token' => getCsrfToken()], 400);
            return;
        }
        try {
            require_once APP_PATH . '/helpers/LocationCacheWarmer.php';
            $result = (new LocationCacheWarmer($this->db))->batch($cursor);
            $this->json(array_merge($result, ['success' => true, 'csrf_token' => getCsrfToken()]));
        } catch (Exception $e) {
            $this->json(['success' => false, 'csrf_token' => getCsrfToken(),
                'message' => sanitize_error($e, 'Lokasi belum berhasil diperbarui. Silakan coba lagi.')], 500);
        }
    }
}
