<?php
/**
 * Admin Subdomain Entry Point
 * This file serves as the entry point for admin.sekolahzivanamontessori.sch.id
 * It loads the main application from the parent directory
 */

// Define that this is admin subdomain
define('IS_ADMIN_SUBDOMAIN', true);

// Load the main application
require_once __DIR__ . '/../public/index.php';
