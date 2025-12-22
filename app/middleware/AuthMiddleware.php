<?php
/**
 * Auth Middleware
 * Protects routes that require authentication
 * Also handles auto-login from remember token
 */
class AuthMiddleware {
    public function handle() {
        // Check if already authenticated via session
        if (is_auth()) {
            return true;
        }
        
        // Try to authenticate via remember token
        if ($this->attemptRememberLogin()) {
            return true;
        }
        
        // Not authenticated
        flash('error', 'Please login to access this page');
        Router::redirect('/admin/login');
        return false;
    }
    
    /**
     * Attempt to login user via remember token
     */
    private function attemptRememberLogin() {
        // Check if remember token cookie exists
        if (!isset($_COOKIE['remember_token'])) {
            return false;
        }
        
        $token = $_COOKIE['remember_token'];
        $hashedToken = hash('sha256', $token);
        
        // Load models
        require_once __DIR__ . '/../models/RememberToken.php';
        require_once __DIR__ . '/../models/User.php';
        
        $rememberTokenModel = new RememberToken();
        $userModel = new User();
        
        // Find valid token
        $tokenData = $rememberTokenModel->findValidToken($hashedToken);
        
        if (!$tokenData) {
            // Token not found or expired, clear cookie
            setcookie('remember_token', '', time() - 3600, '/', '', false, true);
            return false;
        }
        
        // Get user data
        $user = $userModel->findById($tokenData['user_id']);
        
        if (!$user || !$user['is_active']) {
            // User not found or inactive, delete token
            $rememberTokenModel->deleteToken($hashedToken);
            setcookie('remember_token', '', time() - 3600, '/', '', false, true);
            return false;
        }
        
        // Auto-login: create session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_data'] = $user;
        
        // Update last login
        $userModel->updateLastLogin($user['id']);
        
        return true;
    }
}

