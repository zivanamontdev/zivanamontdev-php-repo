<?php
/**
 * Helper Functions
 */

/**
 * Generate URL
 */
function url($path = '') {
    return APP_URL . '/' . ltrim($path, '/');
}

/**
 * Asset URL
 */
function asset($path) {
    return url('assets/' . ltrim($path, '/'));
}

/**
 * Upload URL
 */
function upload($path) {
    return url('uploads/' . ltrim($path, '/'));
}

/**
 * Escape HTML
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF Token
 */
function csrf_token() {
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * CSRF Field
 */
function csrf_field() {
    return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . csrf_token() . '">';
}

/**
 * Verify CSRF Token
 */
function csrf_verify() {
    $token = $_POST[CSRF_TOKEN_NAME] ?? '';
    $sessionToken = $_SESSION[CSRF_TOKEN_NAME] ?? '';
    
    if (!hash_equals($sessionToken, $token)) {
        http_response_code(403);
        die('CSRF token mismatch');
    }
    
    return true;
}

/**
 * Flash message
 */
function flash($key, $message = null) {
    if ($message === null) {
        $message = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $message;
    }
    
    $_SESSION['flash'][$key] = $message;
}

/**
 * Old input value
 */
function old($key, $default = '') {
    return $_SESSION['old'][$key] ?? $default;
}

/**
 * Set old input
 */
function set_old($data) {
    $_SESSION['old'] = $data;
}

/**
 * Clear old input
 */
function clear_old() {
    unset($_SESSION['old']);
}

/**
 * Check if user is authenticated
 */
function is_auth() {
    return isset($_SESSION['user_id']);
}

/**
 * Get authenticated user
 */
function auth_user() {
    if (!is_auth()) {
        return null;
    }
    
    if (!isset($_SESSION['user_data'])) {
        $db = Database::getInstance();
        $user = $db->fetchOne("SELECT * FROM users WHERE id = :id", ['id' => $_SESSION['user_id']]);
        $_SESSION['user_data'] = $user;
    }
    
    return $_SESSION['user_data'];
}

/**
 * Format date
 */
function format_date($date, $format = 'd M Y') {
    return date($format, strtotime($date));
}

/**
 * Format datetime
 */
function format_datetime($datetime, $format = 'd M Y H:i') {
    return date($format, strtotime($datetime));
}

/**
 * Truncate string
 */
function str_limit($string, $limit = 100, $end = '...') {
    if (mb_strlen($string) <= $limit) {
        return $string;
    }
    return mb_substr($string, 0, $limit) . $end;
}

/**
 * Generate slug
 */
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    
    if (empty($text)) {
        return 'n-a';
    }
    
    return $text;
}

/**
 * Sanitize input
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(strip_tags($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email
 */
function is_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Upload file
 */
function upload_file($file, $path = '', $allowedTypes = null) {
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'File upload error'];
    }
    
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        return ['success' => false, 'message' => 'File size exceeds limit'];
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedTypes = $allowedTypes ?? ALLOWED_IMAGE_TYPES;
    
    if (!in_array($extension, $allowedTypes)) {
        return ['success' => false, 'message' => 'File type not allowed'];
    }
    
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $uploadPath = UPLOAD_PATH . '/' . trim($path, '/');
    
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }
    
    $destination = $uploadPath . '/' . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => true, 'filename' => $filename, 'path' => trim($path, '/') . '/' . $filename];
    }
    
    return ['success' => false, 'message' => 'Failed to move uploaded file'];
}

/**
 * Delete file
 */
function delete_file($path) {
    $fullPath = UPLOAD_PATH . '/' . ltrim($path, '/');
    if (file_exists($fullPath)) {
        return unlink($fullPath);
    }
    return false;
}

/**
 * Get client IP
 */
function get_client_ip() {
    $keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
    foreach ($keys as $key) {
        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        }
    }
    return 'UNKNOWN';
}

/**
 * Get device type
 */
function get_device_type() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    if (preg_match('/mobile|android|iphone|ipod|blackberry|iemobile|opera mini/i', $userAgent)) {
        return 'Mobile';
    } elseif (preg_match('/tablet|ipad/i', $userAgent)) {
        return 'Tablet';
    }
    
    return 'Desktop';
}

/**
 * Track page visit
 */
function track_visit($pageUrl) {
    try {
        $db = Database::getInstance();
        $db->insert('analytics', [
            'page_url' => $pageUrl,
            'ip_address' => get_client_ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'device_type' => get_device_type(),
        ]);
    } catch (Exception $e) {
        // Silently fail
    }
}
