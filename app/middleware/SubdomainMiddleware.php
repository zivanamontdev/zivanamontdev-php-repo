<?php

class SubdomainMiddleware {
    
    /**
     * Check if current request is from admin subdomain
     */
    public static function isAdminSubdomain() {
        // Check if IS_ADMIN_SUBDOMAIN constant is defined (from admin/index.php)
        if (defined('IS_ADMIN_SUBDOMAIN') && IS_ADMIN_SUBDOMAIN === true) {
            return true;
        }
        
        // Fallback: check HTTP_HOST
        $host = $_SERVER['HTTP_HOST'] ?? '';
        return strpos($host, 'admin.') === 0;
    }
    
    /**
     * Check if current URI is admin route
     */
    public static function isAdminRoute() {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $path = parse_url($uri, PHP_URL_PATH);
        
        // Admin routes
        if (strpos($path, '/admin') === 0) return true;
        if (strpos($path, '/login') === 0) return true;
        if (strpos($path, '/logout') === 0) return true;
        if (strpos($path, '/forget-password') === 0) return true;
        if (strpos($path, '/reset-password') === 0) return true;
        
        return false;
    }
    
    /**
     * Handle subdomain restrictions
     */
    public static function handle() {
        $isAdminSubdomain = self::isAdminSubdomain();
        $isAdminRoute = self::isAdminRoute();
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $path = parse_url($uri, PHP_URL_PATH);
        
        // If admin subdomain
        if ($isAdminSubdomain) {
            // Redirect root to /admin/login
            if ($path === '/' || $path === '') {
                self::redirect('/admin/login');
                return;
            }
            
            // Block access to non-admin routes (landing page routes)
            if (!$isAdminRoute && $path !== '/') {
                // Show 404 page instead of redirecting
                http_response_code(404);
                if (file_exists(VIEW_PATH . '/errors/404.php')) {
                    require VIEW_PATH . '/errors/404.php';
                } else {
                    echo "<h1>404 - Halaman Tidak Ditemukan</h1>";
                }
                exit;
            }
        }
        
        // If main domain/subdomain (dev, www, or root)
        if (!$isAdminSubdomain) {
            // Allow admin routes in development (localhost, 127.0.0.1, dev subdomain)
            $host = $_SERVER['HTTP_HOST'] ?? '';
            $isDev = (
                strpos($host, 'localhost') !== false || 
                strpos($host, '127.0.0.1') !== false ||
                strpos($host, 'dev.') === 0
            );
            
            // In production, redirect /admin routes to admin subdomain
            if (!$isDev && $isAdminRoute) {
                $adminUrl = defined('ADMIN_URL') ? ADMIN_URL : 'https://admin.sekolahzivanamontessori.sch.id';
                $fullUrl = rtrim($adminUrl, '/') . $uri;
                header('Location: ' . $fullUrl, true, 301);
                exit;
            }
        }
    }
    
    /**
     * Redirect helper
     */
    private static function redirect($path) {
        header('Location: ' . $path);
        exit;
    }
    
    /**
     * Get base URL based on subdomain context
     */
    public static function getBaseUrl() {
        if (self::isAdminSubdomain()) {
            return $_ENV['ADMIN_URL'] ?? 'https://admin.sekolahzivanamontessori.sch.id';
        }
        
        return $_ENV['APP_URL'] ?? 'https://sekolahzivanamontessori.sch.id';
    }
}
