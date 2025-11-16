<?php
/**
 * Base Controller Class
 */
class Controller {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    protected function view($view, $data = []) {
        extract($data);
        
        $viewFile = VIEW_PATH . '/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View not found: {$view}");
        }
    }
    
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function redirect($url, $statusCode = 302) {
        Router::redirect($url, $statusCode);
    }
    
    protected function back() {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }
    
    protected function middleware($middlewareClass) {
        $middleware = new $middlewareClass();
        return $middleware->handle();
    }
    
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
    
    protected function input($key, $default = null) {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }
    
    protected function all() {
        return $_REQUEST;
    }
    
    protected function hasInput($key) {
        return isset($_POST[$key]) || isset($_GET[$key]);
    }
}
