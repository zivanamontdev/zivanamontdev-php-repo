<?php
/**
 * Security Helper
 * 
 * Provides security functions including UUID-based CSRF token generation and validation
 */

/**
 * Generate UUID v4 format CSRF token
 * 
 * @return string UUID format token (e.g., 550e8400-e29b-41d4-a716-446655440000)
 */
function generateCsrfToken() {
    // Generate UUID v4 format
    $data = random_bytes(16);
    
    // Set version to 0100 (UUID v4)
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10 (RFC 4122)
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    
    // Format as UUID string
    $token = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    
    // Store in session
    $_SESSION['csrf_token'] = $token;
    $_SESSION['csrf_token_time'] = time();
    
    return $token;
}

/**
 * Get current CSRF token or generate new one if not exists
 * 
 * @return string Current CSRF token
 */
function getCsrfToken() {
    // Check if token exists and is still valid (not expired)
    if (isset($_SESSION['csrf_token']) && isset($_SESSION['csrf_token_time'])) {
        $tokenAge = time() - $_SESSION['csrf_token_time'];
        
        // Token expires after 1 hour (3600 seconds)
        if ($tokenAge < 3600) {
            return $_SESSION['csrf_token'];
        }
    }
    
    // Generate new token if doesn't exist or expired
    return generateCsrfToken();
}

/**
 * Validate CSRF token
 * 
 * @param string $token Token to validate
 * @return bool True if valid, false otherwise
 */
function validateCsrfToken($token) {
    // Check if session token exists
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    
    // Check if token is expired (1 hour = 3600 seconds)
    if (!isset($_SESSION['csrf_token_time']) || (time() - $_SESSION['csrf_token_time']) > 3600) {
        return false;
    }
    
    // Validate token using timing-attack safe comparison
    $isValid = hash_equals($_SESSION['csrf_token'], $token);
    
    // If valid, regenerate token for next request (single-use pattern)
    if ($isValid) {
        generateCsrfToken();
    }
    
    return $isValid;
}

/**
 * Regenerate CSRF token
 * Useful for forcing token regeneration after sensitive operations
 * 
 * @return string New CSRF token
 */
function regenerateCsrfToken() {
    return generateCsrfToken();
}

/**
 * Verify CSRF token from POST request
 * Sends 403 response and terminates script if invalid
 * 
 * @param bool $jsonResponse Whether to return JSON response (default: false)
 * @return void
 */
function verifyCsrfToken($jsonResponse = false) {
    $token = $_POST['csrf_token'] ?? $_POST['_token'] ?? '';
    
    if (!validateCsrfToken($token)) {
        http_response_code(403);
        
        // Log failed CSRF validation
        error_log('CSRF token validation failed. IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . ', URI: ' . ($_SERVER['REQUEST_URI'] ?? 'unknown'));
        
        if ($jsonResponse) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'CSRF token validation failed. Please refresh the page and try again.'
            ]);
        } else {
            echo '<h1>403 Forbidden</h1>';
            echo '<p>CSRF token validation failed. Please refresh the page and try again.</p>';
        }
        
        exit;
    }
}
