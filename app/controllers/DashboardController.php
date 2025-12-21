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
        
        $this->view('admin/dashboard/index', $data);
    }
}
