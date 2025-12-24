<?php
/**
 * CSRF Field Component
 * 
 * Outputs a hidden input field with CSRF token for form protection
 * Usage: <?php component('csrf_field'); ?>
 */

require_once __DIR__ . '/../../helpers/Security.php';
?>
<input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
