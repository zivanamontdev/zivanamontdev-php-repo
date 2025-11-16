<?php
/**
 * Auth Middleware
 * Protects routes that require authentication
 */
class AuthMiddleware {
    public function handle() {
        if (!is_auth()) {
            flash('error', 'Please login to access this page');
            Router::redirect('/admin/login');
            return false;
        }
        return true;
    }
}
