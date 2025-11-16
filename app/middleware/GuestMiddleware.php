<?php
/**
 * Guest Middleware
 * Redirects authenticated users away from guest-only pages
 */
class GuestMiddleware {
    public function handle() {
        if (is_auth()) {
            Router::redirect('/admin/dashboard');
            return false;
        }
        return true;
    }
}
