<?php
/**
 * Auth Controller
 * Handles authentication (login/logout)
 */
class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }
    
    public function showLogin() {
        $this->middleware(GuestMiddleware::class);
        $this->view('admin/auth/login');
    }
    
    public function login() {
        if (!$this->isPost()) {
            $this->redirect('/admin/login');
            return;
        }
        
        csrf_verify();
        
        $username = $this->input('username');
        $password = $this->input('password');
        $remember = $this->input('remember');
        
        if (empty($username) || empty($password)) {
            flash('error', 'Username and password are required');
            set_old(['username' => $username]);
            $this->redirect('/admin/login');
            return;
        }
        
        $user = $this->userModel->authenticate($username, $password);
        
        if (!$user) {
            flash('error', 'Invalid username or password');
            set_old(['username' => $username]);
            $this->redirect('/admin/login');
            return;
        }
        
        if (!$user['is_active']) {
            flash('error', 'Your account has been deactivated');
            $this->redirect('/admin/login');
            return;
        }
        
        // Create session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_data'] = $user;
        
        // Update last login
        $this->userModel->updateLastLogin($user['id']);
        
        // Set remember me cookie if checked
        if ($remember) {
            setcookie('remember_user', $user['id'], time() + (86400 * 30), '/');
        }
        
        clear_old();
        flash('success', 'Welcome back, ' . $user['full_name']);
        $this->redirect('/admin/dashboard');
    }
    
    public function logout() {
        // Destroy session
        unset($_SESSION['user_id']);
        unset($_SESSION['user_data']);
        
        // Clear remember cookie
        if (isset($_COOKIE['remember_user'])) {
            setcookie('remember_user', '', time() - 3600, '/');
        }
        
        flash('success', 'You have been logged out successfully');
        $this->redirect('/admin/login');
    }
}
