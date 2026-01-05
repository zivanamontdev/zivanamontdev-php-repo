<?php
/**
 * Router Class
 * Handles routing and URL dispatching
 */
class Router {
    private $routes = [];
    private $notFoundCallback;
    
    public function get($path, $callback) {
        $this->addRoute('GET', $path, $callback);
    }
    
    public function post($path, $callback) {
        $this->addRoute('POST', $path, $callback);
    }
    
    public function any($path, $callback) {
        $this->addRoute('GET|POST', $path, $callback);
    }
    
    private function addRoute($method, $path, $callback) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }
    
    public function notFound($callback) {
        $this->notFoundCallback = $callback;
    }
    
    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove base path if exists
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '/') {
            $requestUri = str_replace($scriptName, '', $requestUri);
        }
        $requestUri = '/' . trim($requestUri, '/');
        if ($requestUri !== '/') {
            $requestUri = rtrim($requestUri, '/');
        }
        
        foreach ($this->routes as $route) {
            $methods = explode('|', $route['method']);
            
            if (!in_array($requestMethod, $methods)) {
                continue;
            }
            
            $pattern = $this->convertPathToRegex($route['path']);
            
            if (preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches); // Remove full match
                
                // Track page view for GET requests only (exclude admin, api, assets)
                if ($requestMethod === 'GET' && !$this->shouldSkipTracking($requestUri)) {
                    $this->trackPageView($requestUri);
                }
                
                $callback = $route['callback'];
                
                if (is_array($callback)) {
                    list($controller, $method) = $callback;
                    $controllerInstance = new $controller();
                    return call_user_func_array([$controllerInstance, $method], $matches);
                } else {
                    return call_user_func_array($callback, $matches);
                }
            }
        }
        
        // No route found
        if ($this->notFoundCallback) {
            return call_user_func($this->notFoundCallback);
        } else {
            http_response_code(404);
            $this->show404Page();
        }
    }
    
    /**
     * Check if tracking should be skipped for this URL
     */
    private function shouldSkipTracking($uri) {
        $skipPatterns = [
            '/admin',           // Skip admin pages
            '/api',             // Skip API calls
            '/assets',          // Skip assets
            '/uploads',         // Skip uploads
            '/images',          // Skip images
            '.css',             // Skip CSS files
            '.js',              // Skip JS files
            '.jpg', '.jpeg', '.png', '.gif', '.svg', '.ico', // Skip images
        ];
        
        foreach ($skipPatterns as $pattern) {
            if (strpos($uri, $pattern) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Track page view
     */
    private function trackPageView($pageUrl) {
        try {
            // Get client info
            $ipAddress = GeoIP::getClientIP();
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            // Get location (this will use cache if available)
            $location = GeoIP::getLocation($ipAddress);
            
            // Track asynchronously to avoid blocking
            $analyticsModel = new Analytics();
            $analyticsModel->trackPageView($pageUrl, $ipAddress, $userAgent, $location);
            
        } catch (Exception $e) {
            // Silent fail - don't break the app if tracking fails
            error_log("Page tracking error: " . $e->getMessage());
        }
    }
    
    private function convertPathToRegex($path) {
        // Convert :param to regex pattern
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $path);
        return '#^' . $pattern . '$#';
    }
    
    public static function redirect($path, $statusCode = 302) {
        // If already a full URL (external), redirect directly
        if (preg_match('/^https?:\/\//', $path)) {
            header('Location: ' . $path, true, $statusCode);
            exit;
        }
        
        // Use absolute URL with APP_URL for internal paths
        $url = rtrim(APP_URL, '/') . '/' . ltrim($path, '/');
        header('Location: ' . $url, true, $statusCode);
        exit;
    }
    
    /**
     * Show 404 error page
     */
    private function show404Page() {
        $viewPath = VIEW_PATH . '/errors/404.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "<h1>404 - Page Not Found</h1>";
        }
    }
}
