<?php
// php -S 127.0.0.1:8013 -t public tests/regressions/router.php
if (PHP_SAPI !== 'cli-server') { http_response_code(404); exit; }
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (preg_match('#^/(images|assets)/#', $path) && is_file(dirname(__DIR__, 2) . '/public' . $path)) return false;
require __DIR__ . '/bootstrap.php';
$count = max(0, min(50, (int)($_GET['count'] ?? 15)));
$settings = [];
$socialMedia = [];
if ($path === '/admin/registrations') {
    seedRegistrations((int)($_GET['count'] ?? 23));
    (new RegistrationController())->index();
    return;
}
$views = ['/' => 'index', '/profile' => 'profile', '/activities' => 'activities', '/activities-gallery' => 'activities-gallery', '/articles' => 'articles'];
if (!isset($views[$path])) { http_response_code(404); exit; }
$kepalaSekolah = ['name' => 'Kepala Sekolah Uji'];
$karyawan = $fasilitas = $galleryData = $programsTahunData = $articles = [];
$kelasData = $programsHarianData = $schedules = [];
$programData = ['name' => 'Program Uji'];
for ($i = 1; $i <= $count; $i++) {
    $karyawan[] = ['id' => $i, 'name' => "Pegawai Uji {$i}", 'role' => 'Guru', 'photo' => ''];
    $galleryData[] = ['image' => asset('images/image_testi.jpg'), 'description' => "Foto Uji {$i}"];
    $programsTahunData[] = ['id' => $i, 'title' => "Program Uji {$i}", 'description' => 'Program sekolah', 'image' => asset('images/image_testi.jpg')];
    $articles[] = ['id' => $i, 'title' => "Artikel Uji {$i}", 'slug' => "artikel-{$i}", 'content' => 'Isi artikel', 'excerpt' => 'Isi artikel', 'author_name' => 'Penulis Uji', 'featured_image' => '', 'published_at' => '2026-09-13'];
}
$testimonials = [['parent_name' => 'Orang Tua Uji', 'child_name' => 'Anak Uji', 'testimonial_text' => 'Testimoni untuk pengujian navigasi berulang.', 'highlight_text' => 'Testimoni Uji']];
require VIEW_PATH . '/home/' . $views[$path] . '.php';
