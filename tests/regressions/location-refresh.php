<?php
require __DIR__ . '/bootstrap.php';
require_once APP_PATH . '/helpers/Security.php';
require APP_PATH . '/helpers/LocationCacheWarmer.php';
require APP_PATH . '/controllers/DashboardController.php';
$checks = 0;
function verify($condition, $message) {
    global $checks;
    if (!$condition) throw new RuntimeException($message);
    $checks++;
}
class LocationFixtureDatabase {
    public $ips;
    public function __construct($ips) { sort($ips); $this->ips = $ips; }
    public function fetchOne($sql) { return ['total' => count($this->ips)]; }
    public function fetchAll($sql, $params) {
        $ips = array_slice(array_filter($this->ips, function($ip) use ($params) { return strcmp($ip, $params['cursor']) > 0; }), 0, 25);
        return array_map(function($ip) { return ['ip_address' => $ip]; }, $ips);
    }
}
$db = new LocationFixtureDatabase(['1.1.1.1', '8.8.8.8', '9.9.9.9']);
$calls = 0;
$lookup = function($ip, $network) use (&$calls) {
    if ($network) $calls++;
    return $network || $ip === '1.1.1.1' ? ['countryCode' => 'ID'] : [];
};
$warmer = new LocationCacheWarmer($db, $lookup, function() { return 0; });
$first = $warmer->batch();
verify($calls === 1 && $first['checked'] === 2, 'Only one network lookup per batch');
verify($first['cursor'] === '8.8.8.8' && !$first['done'], 'Cursor continues after completed IP');
$next = $warmer->batch($first['cursor']);
verify($next['checked'] === 1 && $calls === 2, 'Next batch does not repeat processed entries');
verify($warmer->batch($next['cursor'])['done'], 'Final empty batch completes');
$blocked = new LocationCacheWarmer($db, function() { return []; }, function() { return 45; });
$result = $blocked->batch();
verify($result['cursor'] === '' && $result['checked'] === 0 && $result['retry_after'] === 45, 'Provider cooldown keeps cursor for retry');
$failed = new LocationCacheWarmer($db, function() { return []; }, function() { return 0; });
$result = $failed->batch();
verify($result['unresolved'] === 1 && $result['cursor'] === '1.1.1.1', 'Unavailable lookup reports unresolved without infinite loop');
$cached = new LocationCacheWarmer($db, function() { return ['countryCode' => 'ID']; });
verify($cached->batch()['done'] && $cached->batch()['resolved'] === 3, 'Cached entries complete without network');
$private = new LocationCacheWarmer(new LocationFixtureDatabase(['127.0.0.1']), function() { throw new RuntimeException('Must not look up private IP'); });
verify($private->batch()['checked'] === 1, 'Private addresses skipped safely');
$ips = [];
for ($i = 1; $i <= 30; $i++) $ips[] = '8.8.4.' . $i;
$many = new LocationCacheWarmer(new LocationFixtureDatabase($ips), function() { return ['countryCode' => 'ID']; });
verify($many->batch()['checked'] === 25 && !$many->batch()['done'], 'Bounded cached scan');
class CapturedDashboard extends DashboardController {
    public $response;
    protected function json($data, $statusCode = 200) { $this->response = [$statusCode, $data]; }
}
$controller = new CapturedDashboard();
$_SERVER['REQUEST_METHOD'] = 'GET';
$controller->refreshLocations();
verify($controller->response[0] === 405, 'GET cannot trigger warm-up');
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [];
$controller->refreshLocations();
verify($controller->response[0] === 403, 'Missing CSRF rejected');
$_POST = ['csrf_token' => ['invalid']];
$controller->refreshLocations();
verify($controller->response[0] === 403, 'Non-string CSRF rejected');
Database::$visits = [];
$token = getCsrfToken();
$_POST = ['csrf_token' => $token];
$controller->refreshLocations();
verify($controller->response[0] === 200 && $controller->response[1]['done'], 'Valid request completes empty history');
$newToken = $controller->response[1]['csrf_token'];
verify($newToken !== $token, 'Response returns rotated CSRF');
$controller->refreshLocations();
verify($controller->response[0] === 403, 'Replayed token rejected');
$_POST = ['csrf_token' => $newToken];
$controller->refreshLocations();
verify($controller->response[0] === 200, 'Next request accepts rotated token');
echo "Passed {$checks} location-refresh checks.\n";
