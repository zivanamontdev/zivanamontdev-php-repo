<?php
require __DIR__ . '/bootstrap.php';
$checks = 0;
function check($condition, $message) {
    global $checks;
    if (!$condition) throw new RuntimeException($message);
    $checks++;
}
class CapturedRegistrationController extends RegistrationController {
    public $rendered;
    protected function view($view, $data = []) { $this->rendered = $data; }
}
function registrationData($count, $page, $search = '') {
    seedRegistrations($count);
    $_GET = ['page' => $page, 'search' => $search];
    $controller = new CapturedRegistrationController();
    $controller->index();
    return $controller->rendered;
}
foreach ([1 => [1, 10, 10], 2 => [11, 20, 10], 3 => [21, 23, 3]] as $page => $expected) {
    $data = registrationData(23, $page);
    check($data['firstRecord'] === $expected[0] && $data['lastRecord'] === $expected[1], "Page {$page} range");
    check(count($data['registrations']) === $expected[2], "Page {$page} rows");
    check($data['registrationPage'] === $page && $data['totalPages'] === 3, 'Page metadata');
}
check(registrationData(23, 0)['registrationPage'] === 1, 'Clamp zero');
check(registrationData(23, -3)['registrationPage'] === 1, 'Clamp negative');
check(registrationData(23, 99)['registrationPage'] === 3, 'Clamp past end');
check(registrationData(0, 1)['firstRecord'] === 0, 'Empty range');
check(registrationData(23, 99, 'Anak Uji 2')['registrationPage'] === 1, 'Search clamps page');
seedRegistrations(23);
$_GET = ['page' => 2];
$_SERVER['REQUEST_URI'] = '/admin/registrations';
ob_start();
(new RegistrationController())->index();
$html = ob_get_clean();
check(strpos($html, 'Menampilkan 11 - 20 dari 23 data') !== false, 'Rendering keeps page separate from sidebar');
check(strpos($html, '?page=3') !== false, 'Rendered next link advances');
(new Registration())->getPaginated(-1, 50);
check(strpos(Database::$lastSql, 'LIMIT 10 OFFSET 0') !== false, 'Model caps page size');
$id = ['countryCode' => 'ID', 'city' => 'Makassar', 'hosting' => false];
check(GeoIP::isIndonesianVisitor($id, 'Mozilla/5.0 Chrome/128.0'), 'Indonesian visitor included');
foreach (['Googlebot', 'facebookexternalhit', 'HeadlessChrome', 'curl/8.0', ''] as $agent) check(!GeoIP::isIndonesianVisitor($id, $agent), 'Exclude automated agent');
check(!GeoIP::isIndonesianVisitor(array_merge($id, ['countryCode' => 'US']), 'Mozilla/5.0'), 'Exclude foreign traffic');
check(!GeoIP::isIndonesianVisitor(array_merge($id, ['hosting' => true]), 'Mozilla/5.0'), 'Exclude hosting');
check(!GeoIP::isIndonesianVisitor(['city' => 'Makassar'], 'Mozilla/5.0'), 'Do not infer country from a city name');
$cache = new ReflectionProperty(GeoIP::class, 'cache');
$cache->setValue(null, ['8.8.8.8' => $id, '1.1.1.1' => array_merge($id, ['countryCode' => 'US'])]);
$cacheFile = new ReflectionProperty(GeoIP::class, 'cacheFile');
$cacheFile->setValue(null, STORAGE_PATH . '/unused.json');
Database::$visits = [
    ['ip_address' => '8.8.8.8', 'user_agent' => 'Mozilla/5.0', 'views' => 4],
    ['ip_address' => '8.8.8.8', 'user_agent' => 'Mozilla/5.0 Mobile', 'views' => 3],
    ['ip_address' => '8.8.8.8', 'user_agent' => 'Googlebot', 'views' => 100],
    ['ip_address' => '1.1.1.1', 'user_agent' => 'Mozilla/5.0', 'views' => 500],
];
check((new Analytics())->getTopLocations() === [['location' => 'Makassar', 'views' => 7]], 'Aggregate only eligible visits before ranking');
check(GeoIP::getDetails('9.9.9.9', false)['countryCode'] === '', 'Cache-only lookup stays unresolved');
echo "Passed {$checks} regression checks.\n";
