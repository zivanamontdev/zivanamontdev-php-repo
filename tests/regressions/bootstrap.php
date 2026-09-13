<?php
// Isolated data doubles: never load .env, production database, email settings or R2.
define('ROOT_PATH', dirname(__DIR__, 2));
define('APP_PATH', ROOT_PATH . '/app');
define('VIEW_PATH', APP_PATH . '/views');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('APP_URL', 'http://127.0.0.1:8013');
define('ADMIN_URL', APP_URL);
define('APP_NAME', 'Zivana regression fixtures');
define('APP_ENV', 'local');
define('R2_ENABLED', false);
define('R2_PUBLIC_URL', 'https://cdn.example.test');
define('CSRF_TOKEN_NAME', 'csrf_token');
define('STORAGE_PATH', sys_get_temp_dir() . '/zivana-regression-cache');
$_SESSION = array_merge($_SESSION ?? [], ['user_id' => 1, 'user_data' => ['full_name' => 'Test Admin', 'email' => 'admin@example.test']]);
require APP_PATH . '/helpers/functions.php';
require APP_PATH . '/helpers/geoip.php';
class Database {
    public static $registrations = [];
    public static $visits = [];
    public static $lastSql;
    public static function getInstance() { static $db; return $db ?? ($db = new self()); }
    public function fetchAll($sql, $params = []) {
        self::$lastSql = $sql;
        if (strpos($sql, 'page_views') !== false) return self::$visits;
        $rows = self::$registrations;
        if ($params) $rows = array_values(array_filter($rows, function($row) use ($params) { return strpos($row['child_name'], trim($params['search'], '%')) !== false; }));
        if (preg_match('/LIMIT (\d+) OFFSET (\d+)/', $sql, $match)) return array_slice($rows, (int)$match[2], (int)$match[1]);
        return $rows;
    }
    public function fetchOne($sql, $params = []) { return ['total' => count($this->fetchAll($sql, $params))]; }
}
require APP_PATH . '/core/Controller.php';
require APP_PATH . '/core/Model.php';
class AuthMiddleware { public function handle() { return true; } }
require APP_PATH . '/models/Registration.php';
require APP_PATH . '/controllers/RegistrationController.php';
require APP_PATH . '/models/Analytics.php';
function seedRegistrations($count) {
    Database::$registrations = [];
    for ($i = 1; $i <= $count; $i++) Database::$registrations[] = ['id' => $i, 'child_name' => "Anak Uji {$i}", 'parent_name' => 'Orang Tua Uji', 'created_at' => '2026-09-13 10:00:00', 'whatsapp' => '', 'child_age' => '4 tahun', 'address' => 'Alamat uji'];
}
