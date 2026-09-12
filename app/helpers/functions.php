<?php
/**
 * Helper Functions
 */

// Load email helper
require_once __DIR__ . '/email.php';

/**
 * Get colors configuration
 */
function colors($key = null) {
    static $colors = null;
    
    if ($colors === null) {
        $colors = require CONFIG_PATH . '/colors.php';
    }
    
    if ($key === null) {
        return $colors;
    }
    
    // Support dot notation: 'primary.600', 'brand.purple'
    $keys = explode('.', $key);
    $value = $colors;
    
    foreach ($keys as $k) {
        if (isset($value[$k])) {
            $value = $value[$k];
        } else {
            return null;
        }
    }
    
    return $value;
}

/**
 * Get Tailwind config script with custom colors
 */
function tailwind_config() {
    $colors = colors();
    $config = [
        'theme' => [
            'extend' => [
                'colors' => [
                    'primary'            => $colors['primary'],
                    'primary-dark'       => $colors['primary_dark'],
                    'secondary'          => $colors['secondary'],
                    'pale-accent'        => $colors['pale_accent'],
                    'pink-accent'        => $colors['pink_accent'],
                    'black-neutral'      => $colors['black_neutral'],
                    'black-soft'         => $colors['black_soft'],
                    'black-highlight'    => $colors['black_highlight'],
                    'black-soft-highlight' => $colors['black_soft_highlight'],
                    'white-neutral'      => $colors['white_neutral'],
                    'white-secondary'    => $colors['white_secondary'],
                    'white-soft'         => $colors['white_soft'],
                    'white-shadow'       => $colors['white_shadow'],
                    'white-dim'          => $colors['white_dim'],
                    'white-pure'         => $colors['white_pure'],
                    'text-dark'          => $colors['text_dark'],
                    'gray-placeholder'   => $colors['gray_placeholder'],
                    'border-light'       => $colors['border_light'],
                    'border-soft'        => $colors['border_soft'],
                ]
            ]
        ]
    ];
    
    return '<script>tailwind.config = ' . json_encode($config) . '</script>';
}

/**
 * Render a UI component
 * 
 * @param string $name Component name (e.g., 'button', 'card')
 * @param array $data Data to pass to component
 * @return void
 */
function component(string $__component_name__, array $__component_data__ = []): void
{
    extract($__component_data__);
    include VIEW_PATH . '/components/' . $__component_name__ . '.php';
}

/**
 * Render a UI component and return as string
 * 
 * @param string $name Component name
 * @param array $data Data to pass to component
 * @return string
 */
function render_component(string $__component_name__, array $__component_data__ = []): string
{
    ob_start();
    component($__component_name__, $__component_data__);
    return ob_get_clean();
}

/**
 * Generate URL based on subdomain context
 */
function url($path = '') {
    // Check if this is admin route
    $isAdminRoute = strpos($path, '/admin') === 0 || strpos($path, 'admin/') !== false;
    
    // Check if we're on admin subdomain
    $isAdminSubdomain = false;
    if (defined('IS_ADMIN_SUBDOMAIN') && IS_ADMIN_SUBDOMAIN === true) {
        $isAdminSubdomain = true;
    } elseif (isset($_SERVER['HTTP_HOST'])) {
        $isAdminSubdomain = strpos($_SERVER['HTTP_HOST'], 'admin.') === 0;
    }
    
    // Determine base URL
    if ($isAdminRoute || $isAdminSubdomain) {
        $baseUrl = ADMIN_URL ?? APP_URL;
    } else {
        $baseUrl = APP_URL;
    }
    
    // Auto-detect port if localhost and port not in URL
    if (strpos($baseUrl, 'localhost') !== false) {
        // Check if port is already specified in the URL (after localhost)
        if (!preg_match('/localhost:\d+/', $baseUrl)) {
            // Check if running on non-standard port
            if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 80 && $_SERVER['SERVER_PORT'] != 443) {
                $baseUrl = rtrim($baseUrl, '/') . ':' . $_SERVER['SERVER_PORT'];
            }
        }
    }
    
    return $baseUrl . '/' . ltrim($path, '/');
}

/**
 * Generate admin URL (always points to admin subdomain)
 */
function adminUrl($path = '') {
    $adminUrl = ADMIN_URL ?? APP_URL;
    
    // Auto-detect port if localhost
    if (strpos($adminUrl, 'localhost') !== false) {
        // Check if port is already specified in the URL (after localhost)
        if (!preg_match('/localhost:\d+/', $adminUrl)) {
            if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 80 && $_SERVER['SERVER_PORT'] != 443) {
                $adminUrl = rtrim($adminUrl, '/') . ':' . $_SERVER['SERVER_PORT'];
            }
        }
    }
    
    return $adminUrl . '/' . ltrim($path, '/');
}

/**
 * Asset URL - Always points to main domain for static assets (images, css, js)
 * Use this for images, CSS, JS, and other static files
 */
function asset($path) {
    $baseUrl = APP_URL;
    
    // Auto-detect port if localhost
    if (strpos($baseUrl, 'localhost') !== false) {
        if (!preg_match('/localhost:\d+/', $baseUrl)) {
            if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 80 && $_SERVER['SERVER_PORT'] != 443) {
                $baseUrl = rtrim($baseUrl, '/') . ':' . $_SERVER['SERVER_PORT'];
            }
        }
    }
    
    return $baseUrl . '/' . ltrim($path, '/');
}

/**
 * Upload URL
 */
function upload($path) {
    return url('uploads/' . ltrim($path, '/'));
}

/**
 * Get image URL with R2 proxy support for local development
 * 
 * @param string $imagePath - Path or URL from database
 * @param bool $useProxy - Force use proxy even in production (default: auto-detect)
 * @return string - Full URL to image
 */
function image_url($imagePath, $useProxy = null) {
    if (empty($imagePath)) {
        return '';
    }
    
    // Check if it's already a full URL (R2)
    if (strpos($imagePath, 'http://') === 0 || strpos($imagePath, 'https://') === 0) {
        // R2 URL detected
        
        // Auto-detect if we should use proxy
        if ($useProxy === null) {
            // Use proxy only in local development AND R2 is enabled
            $useProxy = (APP_ENV === 'local' || strpos(APP_URL, 'localhost') !== false) && R2_ENABLED;
        }
        
        if ($useProxy) {
            // Use proxy to bypass SSL issues in local development
            return url('proxy_r2_image.php?url=' . urlencode($imagePath));
        }
        
        // Return R2 URL as-is (production)
        return $imagePath;
    }
    
    // Local path - convert to URL
    if (strpos($imagePath, 'uploads/') === 0) {
        return url($imagePath);
    }
    
    return url('uploads/' . ltrim($imagePath, '/'));
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
 * Legacy function - now uses new Security helper
 */
function csrf_field() {
    // Load Security helper if not already loaded
    if (!function_exists('getCsrfToken')) {
        require_once __DIR__ . '/Security.php';
    }
    
    return '<input type="hidden" name="csrf_token" value="' . getCsrfToken() . '">';
}

/**
 * Verify CSRF Token
 * Legacy function - now uses new Security helper
 */
function csrf_verify() {
    // Load Security helper if not already loaded
    if (!function_exists('verifyCsrfToken')) {
        require_once __DIR__ . '/Security.php';
    }
    
    verifyCsrfToken(false);
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
 * Sanitize error message for user display
 * Logs technical details and returns user-friendly message
 */
function sanitize_error($exception, $userMessage = 'Terjadi kesalahan. Silakan coba lagi.') {
    // Log technical error
    error_log(get_class($exception) . ': ' . $exception->getMessage() . ' in ' . $exception->getFile() . ':' . $exception->getLine());
    
    // Return user-friendly message
    return $userMessage;
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
 * Sanitize article HTML while preserving simple blog formatting.
 */
function sanitize_article_content($html) {
    $html = trim((string) $html);
    if ($html === '') {
        return '';
    }

    $allowedTags = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'strike',
        'ul', 'ol', 'li', 'blockquote', 'h2', 'h3', 'h4', 'a'
    ];
    $blockedTagsWithContent = ['script', 'style', 'iframe', 'object', 'embed'];

    if (!class_exists('DOMDocument')) {
        $html = preg_replace('#<(script|style|iframe|object|embed)[^>]*>.*?</\1>#is', '', $html);
        $html = strip_tags($html, '<' . implode('><', $allowedTags) . '>');
        $html = preg_replace('/\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);
        $html = preg_replace('/\s+(style|class|id)\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);
        $html = preg_replace('/href\s*=\s*("[^"]*javascript:[^"]*"|\'[^\']*javascript:[^\']*\')/i', 'href="#"', $html);
        return trim($html);
    }

    $previousUseErrors = libxml_use_internal_errors(true);
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->loadHTML(
        '<?xml encoding="UTF-8"><div id="article-content-root">' . $html . '</div>',
        LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
    );
    libxml_clear_errors();
    libxml_use_internal_errors($previousUseErrors);

    $root = $dom->getElementById('article-content-root');
    if (!$root) {
        return '';
    }

    $sanitizeNode = function ($node) use (&$sanitizeNode, $allowedTags, $blockedTagsWithContent, $dom) {
        if ($node->nodeType === XML_COMMENT_NODE) {
            $node->parentNode->removeChild($node);
            return;
        }

        if ($node->nodeType !== XML_ELEMENT_NODE) {
            return;
        }

        $tag = strtolower($node->nodeName);
        if (in_array($tag, $blockedTagsWithContent, true)) {
            $node->parentNode->removeChild($node);
            return;
        }

        $children = [];
        foreach ($node->childNodes as $child) {
            $children[] = $child;
        }

        foreach ($children as $child) {
            $sanitizeNode($child);
        }

        if (!in_array($tag, $allowedTags, true) && $node->getAttribute('id') !== 'article-content-root') {
            while ($node->firstChild) {
                $node->parentNode->insertBefore($node->firstChild, $node);
            }
            $node->parentNode->removeChild($node);
            return;
        }

        if ($node->hasAttributes()) {
            $attributesToRemove = [];
            foreach ($node->attributes as $attribute) {
                $name = strtolower($attribute->name);
                $value = trim($attribute->value);

                if ($tag !== 'a' || !in_array($name, ['href', 'target', 'rel'], true)) {
                    if (!($node->getAttribute('id') === 'article-content-root' && $name === 'id')) {
                        $attributesToRemove[] = $attribute->name;
                    }
                    continue;
                }

                if ($name === 'href') {
                    $isSafeHref = preg_match('/^(https?:\/\/|mailto:|tel:|\/|#)/i', $value);
                    if (!$isSafeHref) {
                        $attributesToRemove[] = $attribute->name;
                    }
                }

                if ($name === 'target' && $value !== '_blank') {
                    $attributesToRemove[] = $attribute->name;
                }
            }

            foreach ($attributesToRemove as $attributeName) {
                $node->removeAttribute($attributeName);
            }
        }

        if ($tag === 'a' && $node->getAttribute('target') === '_blank') {
            $node->setAttribute('rel', 'noopener noreferrer');
        }
    };

    $sanitizeNode($root);

    $output = '';
    foreach ($root->childNodes as $child) {
        $output .= $dom->saveHTML($child);
    }

    return trim($output);
}

/**
 * Get readable plain text from article content for validation/excerpts.
 */
function article_plain_text($content) {
    return trim(str_replace("\xc2\xa0", ' ', html_entity_decode(strip_tags((string) $content), ENT_QUOTES, 'UTF-8')));
}

/**
 * Render article content. Plain legacy content keeps line breaks; rich content renders sanitized HTML.
 */
function render_article_content($content) {
    $content = (string) $content;
    if ($content === strip_tags($content)) {
        return nl2br(e($content));
    }

    return sanitize_article_content($content);
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
 * Get asset URL for uploaded files
 */
function asset_url($path) {
    if (empty($path)) {
        return '';
    }
    // If path already starts with http:// or https://, return as is
    if (preg_match('/^https?:\/\//', $path)) {
        return $path;
    }
    // If path starts with /, return as is
    if (substr($path, 0, 1) === '/') {
        return $path;
    }
    // Otherwise, prepend /uploads/
    return '/uploads/' . ltrim($path, '/');
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
