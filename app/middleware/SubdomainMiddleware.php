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
        
        // Remove port if exists
        $host = preg_replace('/:\d+$/', '', $host);
        
        return (
            $host === 'admin.sekolahzivanamontessori.sch.id' ||
            strpos($host, 'admin.') === 0
        );
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
        try {
            $isAdminSubdomain = self::isAdminSubdomain();
            $isAdminRoute = self::isAdminRoute();
            $uri = $_SERVER['REQUEST_URI'] ?? '';
            $path = parse_url($uri, PHP_URL_PATH);
            $host = $_SERVER['HTTP_HOST'] ?? '';
            
            // Debug logging (comment out in production after fixing)
            error_log("SubdomainMiddleware - Host: $host, Path: $path, IsAdminSubdomain: " . ($isAdminSubdomain ? 'YES' : 'NO') . ", IsAdminRoute: " . ($isAdminRoute ? 'YES' : 'NO'));
        
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
            
            // IMPORTANT: Stop here! Don't execute main domain logic when in admin subdomain
            return;
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
            
            // In production, block admin routes with 404 (admin routes only accessible via admin subdomain)
            if (!$isDev && $isAdminRoute) {
                http_response_code(404);
                
                // Try to load 404 view if VIEW_PATH is defined
                $view404 = defined('VIEW_PATH') ? VIEW_PATH . '/errors/404.php' : null;
                
                if ($view404 && file_exists($view404)) {
                    require $view404;
                } else {
                    // Fallback 404 HTML
                    echo '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 50px; background: #f5f5f5; }
        h1 { color: #C92C2F; font-size: 72px; margin: 0; }
        p { color: #666; font-size: 18px; }
        a { color: #C92C2F; text-decoration: none; }
    </style>
</head>
<body>
    <h1>404</h1>
    <p>Halaman yang Anda cari tidak ditemukan.</p>
    <p><a href="/">← Kembali ke Beranda</a></p>
</body>
</html>';
                }
                exit;
            }
        }
        
        } catch (Exception $e) {
            // Log error and show generic 500 page
            error_log("SubdomainMiddleware Error: " . $e->getMessage());
            http_response_code(500);
            echo "<h1>500 - Internal Server Error</h1>";
            echo "<p>Terjadi kesalahan pada server. Silakan coba lagi nanti.</p>";
            if (defined('APP_DEBUG') && APP_DEBUG) {
                echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
                echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
            }
            exit;
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
