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
        // Set flash message BEFORE destroying session data
        flash('success', 'Anda telah berhasil keluar');
        
        // Destroy session
        unset($_SESSION['user_id']);
        unset($_SESSION['user_data']);
        
        // Clear remember cookie
        if (isset($_COOKIE['remember_user'])) {
            setcookie('remember_user', '', time() - 3600, '/');
        }
        
        $this->redirect('/admin/login');
    }
    
    public function showForgetPassword() {
        $this->middleware(GuestMiddleware::class);
        $this->view('admin/auth/forget-password');
    }
    
    public function forgetPassword() {
        if (!$this->isPost()) {
            $this->redirect('/admin/forget-password');
            return;
        }
        
        csrf_verify();
        
        $email = $this->input('email');
        
        if (empty($email)) {
            flash('error', 'Email harus diisi');
            set_old(['email' => $email]);
            $this->redirect('/admin/forget-password');
            return;
        }
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Format email tidak valid');
            set_old(['email' => $email]);
            $this->redirect('/admin/forget-password');
            return;
        }
        
        // Check if user exists
        $user = $this->userModel->findByEmail($email);
        
        if (!$user) {
            // Don't reveal if email exists or not for security
            flash('success', 'Jika email terdaftar, link reset password telah dikirim ke email Anda');
            clear_old();
            $this->redirect('/admin/forget-password');
            return;
        }
        
        // Generate reset token
        $token = generate_reset_token();
        $expiry = time() + 3600; // 1 hour from now
        
        // Store token in session (for local testing)
        // In production, store in database
        if (!isset($_SESSION['reset_tokens'])) {
            $_SESSION['reset_tokens'] = [];
        }
        $_SESSION['reset_tokens'][$token] = [
            'email' => $email,
            'user_id' => $user['id'],
            'expires' => $expiry
        ];
        
        // Send reset email
        try {
            $emailSent = send_password_reset_email($email, $token);
            
            // Log token to server log for local testing
            error_log("==============================================");
            error_log("PASSWORD RESET TOKEN GENERATED");
            error_log("==============================================");
            error_log("Email: " . $email);
            error_log("Token: " . $token);
            error_log("Reset Link: " . url('/admin/reset-password?token=' . $token));
            error_log("Expires: " . date('Y-m-d H:i:s', $expiry));
            error_log("==============================================");
            
            if ($emailSent) {
                flash('success', 'Link reset password telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
            } else {
                flash('error', 'Gagal mengirim email. Silakan coba lagi');
            }
        } catch (Exception $e) {
            error_log("Error sending reset email: " . $e->getMessage());
            flash('error', 'Error: ' . $e->getMessage());
        }
        
        clear_old();
        $this->redirect('/admin/forget-password');
    }
    
    public function showResetPassword() {
        $this->middleware(GuestMiddleware::class);
        
        // Get token from URL
        $token = $_GET['token'] ?? null;
        
        if (!$token) {
            flash('error', 'Token reset password tidak valid');
            $this->redirect('/admin/forget-password');
            return;
        }
        
        // Validate token
        if (!isset($_SESSION['reset_tokens'][$token])) {
            flash('error', 'Token reset password tidak valid atau sudah kadaluarsa');
            $this->redirect('/admin/forget-password');
            return;
        }
        
        $tokenData = $_SESSION['reset_tokens'][$token];
        
        // Check if token expired
        if (time() > $tokenData['expires']) {
            unset($_SESSION['reset_tokens'][$token]);
            flash('error', 'Token reset password sudah kadaluarsa. Silakan request ulang');
            $this->redirect('/admin/forget-password');
            return;
        }
        
        // Pass token to view
        $this->view('admin/auth/reset-password', ['token' => $token]);
    }
    
    public function resetPassword() {
        if (!$this->isPost()) {
            $this->redirect('/admin/forget-password');
            return;
        }
        
        csrf_verify();
        
        $password = $this->input('password');
        $confirmPassword = $this->input('confirm_password');
        $token = $this->input('token');
        
        if (!$token) {
            flash('error', 'Token tidak valid');
            $this->redirect('/admin/forget-password');
            return;
        }
        
        // Validate token
        if (!isset($_SESSION['reset_tokens'][$token])) {
            flash('error', 'Token reset password tidak valid atau sudah kadaluarsa');
            $this->redirect('/admin/forget-password');
            return;
        }
        
        $tokenData = $_SESSION['reset_tokens'][$token];
        
        // Check if token expired
        if (time() > $tokenData['expires']) {
            unset($_SESSION['reset_tokens'][$token]);
            flash('error', 'Token reset password sudah kadaluarsa. Silakan request ulang');
            $this->redirect('/admin/forget-password');
            return;
        }
        
        if (empty($password) || empty($confirmPassword)) {
            flash('error', 'Semua field harus diisi');
            $this->redirect('/admin/reset-password?token=' . $token);
            return;
        }
        
        if ($password !== $confirmPassword) {
            flash('error', 'Kata sandi tidak cocok');
            $this->redirect('/admin/reset-password?token=' . $token);
            return;
        }
        
        if (strlen($password) < 8) {
            flash('error', 'Kata sandi minimal 8 karakter');
            $this->redirect('/admin/reset-password?token=' . $token);
            return;
        }
        
        // Update password in database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $updated = $this->userModel->updatePassword($tokenData['user_id'], $hashedPassword);
        
        if ($updated) {
            // Remove token from session
            unset($_SESSION['reset_tokens'][$token]);
            
            flash('success', 'Kata sandi berhasil direset. Silakan login dengan kata sandi baru Anda');
            $this->redirect('/admin/login');
        } else {
            flash('error', 'Gagal mereset kata sandi. Silakan coba lagi');
            $this->redirect('/admin/reset-password?token=' . $token);
        }
    }
}
