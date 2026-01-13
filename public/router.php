<?php
/**
 * Router for PHP Built-in Server
 * This file handles routing for development server
 * Also sets flag for local development to bypass subdomain middleware
 */

// Set local development flag BEFORE loading anything else
// This will be used by SubdomainMiddleware to bypass subdomain checks
define('IS_LOCAL_DEV_SERVER', true);

// Get the requested URI
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve static files directly
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false; // Let PHP built-in server handle static files
}

// All other requests go to index.php
$_SERVER['SCRIPT_NAME'] = '/index.php';
require __DIR__ . '/index.php';
