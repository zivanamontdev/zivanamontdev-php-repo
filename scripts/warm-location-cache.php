<?php
/** Run once after deployment: php scripts/warm-location-cache.php
 * Enrich existing visit IPs with country/hosting metadata. Does not change page_views.
 */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
define('ROOT_PATH', dirname(__DIR__));
define('STORAGE_PATH', ROOT_PATH . '/storage');
require ROOT_PATH . '/config/config.php';
require ROOT_PATH . '/app/core/Database.php';
require ROOT_PATH . '/app/helpers/geoip.php';
$rows = Database::getInstance()->fetchAll('SELECT DISTINCT ip_address FROM page_views ORDER BY ip_address');
$resolved = 0;
$unknown = 0;
foreach ($rows as $row) {
    $ip = $row['ip_address'];
    if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) continue;
    $details = GeoIP::getDetails($ip, false);
    if (empty($details['countryCode'])) {
        $details = GeoIP::getDetails($ip);
        usleep(1500000); // Stay below the provider's 45 requests/minute limit.
    }
    if (empty($details['countryCode'])) $unknown++; else $resolved++;
}
echo "Location metadata ready: {$resolved}; unresolved: {$unknown}.\n";
if ($unknown > 0) echo "Run again later to retry unresolved entries. Unknown locations stay excluded.\n";
