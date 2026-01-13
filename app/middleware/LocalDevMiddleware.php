<?php
/**
 * Local Development Middleware
 * This middleware bypasses subdomain checks when running on localhost
 * It replaces SubdomainMiddleware in local development environment
 */

class LocalDevMiddleware {
    public static function handle() {
        // Check if running on local development server
        $isLocalDev = defined('IS_LOCAL_DEV_SERVER') && IS_LOCAL_DEV_SERVER === true;
        $isLocalHost = isset($_SERVER['HTTP_HOST']) && 
                      (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || 
                       strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);
        
        // If on local development, allow all routes
        if ($isLocalDev || $isLocalHost || (defined('IS_LOCAL_DEV') && IS_LOCAL_DEV === true)) {
            // Log for debugging
            error_log("LocalDevMiddleware - Running on local development, bypassing subdomain checks");
            return true;
        }
        
        // If not local, delegate to SubdomainMiddleware
        require_once ROOT_PATH . '/app/middleware/SubdomainMiddleware.php';
        return SubdomainMiddleware::handle();
    }
}
