<?php
/**
 * Home Controller
 * Handles homepage and public pages
 */
class HomeController extends Controller {
    private $programModel;
    private $articleModel;
    private $employeeModel;
    private $scheduleModel;
    private $awardModel;
    private $socialMediaModel;
    private $settingModel;
    private $registrationModel;
    private $highlightProgramModel;
    private $testimonialModel;
    private $eventModel;
    private $faqModel;
    private $kepalaSekolahModel;
    private $karyawanModel;
    private $fasilitasModel;
    private $registrationSettingModel;
    private $prakataModel;
    
    public function __construct() {
        parent::__construct();
        $this->programModel = new Program();
        $this->articleModel = new Article();
        $this->employeeModel = new Employee();
        $this->scheduleModel = new Schedule();
        $this->awardModel = new Award();
        $this->socialMediaModel = new SocialMedia();
        $this->settingModel = new Setting();
        $this->registrationModel = new Registration();
        $this->highlightProgramModel = new HighlightProgram();
        $this->testimonialModel = new Testimonial();
        $this->eventModel = new Event();
        $this->faqModel = new Faq();
        $this->kepalaSekolahModel = new KepalaSekolah();
        $this->karyawanModel = new Karyawan();
        $this->fasilitasModel = new Fasilitas();
        $this->registrationSettingModel = new RegistrationSetting();
        $this->prakataModel = new Prakata();
    }
    
    public function index() {
        track_visit('/');
        
        $data = [
            'programs' => $this->programModel->getActive(),
            'articles' => $this->articleModel->getFeatured(3),
            'socialMedia' => $this->socialMediaModel->getActive(),
            'settings' => $this->getSettings(),
            'highlightPrograms' => $this->highlightProgramModel->getAll(),
            'testimonials' => $this->testimonialModel->getAll(),
            'events' => $this->eventModel->getAllEvents(),
            'faqs' => $this->faqModel->getAll(),
            'prakata' => $this->prakataModel->get(),
        ];
        
        $this->view('home/index', $data);
    }
    
    public function activities() {
        track_visit('/activities');
        
        // Fetch classes from database
        $db = Database::getInstance();
        $classes = $db->query("SELECT * FROM classes WHERE is_active = 1 ORDER BY display_order ASC, created_at ASC")->fetchAll();
        
        // Transform data for view
        $kelasData = array_map(function($class) {
            return [
                'image' => $class['image'],
                'title' => $class['name'],
                'usia' => $class['age_range'],
                'durasi' => $class['duration'],
                'jumlah_murid' => $class['max_students']
            ];
        }, $classes);
        
        // Fetch programs tahun ajaran from database
        $programsTahun = $db->query("SELECT * FROM programs_tahun WHERE is_active = 1 ORDER BY display_order ASC, created_at ASC")->fetchAll();
        
        // Transform data for view
        $programsTahunData = array_map(function($program) use ($db) {
            // Fetch cover image (sampul)
            $coverImage = null;
            
            // Check for gallery cover image first
            $galleryImages = $db->query(
                "SELECT * FROM program_gallery WHERE program_id = ? AND is_cover = 1 LIMIT 1", 
                [$program['id']]
            )->fetchAll();
            
            if (!empty($galleryImages)) {
                $coverImage = $galleryImages[0]['image_path'];
            } elseif (!empty($program['image'])) {
                // Fallback to program image
                $coverImage = $program['image'];
            }
            
            return [
                'id' => $program['id'],
                'image' => $coverImage,
                'title' => $program['name'],
                'description' => $program['description']
            ];
        }, $programsTahun);
        
        // Fetch programs harian from database
        $programsHarian = $db->query("SELECT * FROM programs_harian WHERE is_active = 1 ORDER BY display_order ASC, id ASC")->fetchAll();
        
        // Transform data for view
        $programsHarianData = array_map(function($program) use ($db) {
            // Fetch cover image (sampul)
            $coverImage = null;
            
            // Check for gallery cover image first
            $galleryImages = $db->query(
                "SELECT * FROM program_harian_gallery WHERE program_harian_id = ? AND is_cover = 1 LIMIT 1", 
                [$program['id']]
            )->fetchAll();
            
            if (!empty($galleryImages)) {
                $coverImage = $galleryImages[0]['image_path'];
            } elseif (!empty($program['image'])) {
                // Fallback to program image
                $coverImage = $program['image'];
            }
            
            return [
                'id' => $program['id'],
                'image' => $coverImage,
                'title' => $program['program_name'],
                'description' => $program['description']
            ];
        }, $programsHarian);
        
        $data = [
            'programs' => $this->programModel->getAllWithImages(),
            'schedules' => $this->scheduleModel->getAllOrdered(),
            'socialMedia' => $this->socialMediaModel->getActive(),
            'settings' => $this->getSettings(),
            'kelasData' => $kelasData,
            'programsTahunData' => $programsTahunData,
            'programsHarianData' => $programsHarianData,
        ];
        
        $this->view('home/activities', $data);
    }
    
    public function activitiesGallery() {
        track_visit('/activities-gallery');
        
        $programId = $this->input('program_id');
        $type = $this->input('type') ?? 'tahun'; // Default to tahun
        $db = Database::getInstance();
        
        // Default data
        $programData = null;
        $galleryData = [];
        
        if ($programId) {
            // Determine which table to query based on type
            if ($type === 'harian') {
                // Fetch program harian details
                $program = $db->query(
                    "SELECT * FROM programs_harian WHERE id = ? AND is_active = 1", 
                    [$programId]
                )->fetch();
                
                if ($program) {
                    $programData = [
                        'id' => $program['id'],
                        'name' => $program['program_name'],
                        'description' => $program['description']
                    ];
                    
                    // Fetch all gallery images for this program harian
                    $gallery = $db->query(
                        "SELECT * FROM program_harian_gallery WHERE program_harian_id = ? ORDER BY display_order ASC", 
                        [$programId]
                    )->fetchAll();
                    
                    // Transform gallery data
                    $galleryData = array_map(function($item) {
                        return [
                            'image' => $item['image_path'],
                            'description' => $item['description'] ?? ''
                        ];
                    }, $gallery);
                    
                    // If program has an image and it's not already in gallery, add it
                    if (!empty($program['image'])) {
                        // Check if program image is already in gallery as cover
                        $hasCover = false;
                        foreach ($gallery as $img) {
                            if ($img['is_cover'] == 1) {
                                $hasCover = true;
                                break;
                            }
                        }
                        
                        // If no cover in gallery, use program image as first item
                        if (!$hasCover) {
                            array_unshift($galleryData, [
                                'image' => $program['image'],
                                'description' => $program['description'] ?? ''
                            ]);
                        }
                    }
                }
            } else {
                // Fetch program tahun details
                $program = $db->query(
                    "SELECT * FROM programs_tahun WHERE id = ? AND is_active = 1", 
                    [$programId]
                )->fetch();
                
                if ($program) {
                    $programData = [
                        'id' => $program['id'],
                        'name' => $program['name'],
                        'description' => $program['description']
                    ];
                    
                    // Fetch all gallery images for this program
                    $gallery = $db->query(
                        "SELECT * FROM program_gallery WHERE program_id = ? ORDER BY display_order ASC", 
                        [$programId]
                    )->fetchAll();
                    
                    // Transform gallery data
                    $galleryData = array_map(function($item) {
                        return [
                            'image' => $item['image_path'],
                            'description' => $item['description'] ?? ''
                        ];
                    }, $gallery);
                    
                    // If program has an image and it's not already in gallery, add it
                    if (!empty($program['image'])) {
                        // Check if program image is already in gallery as cover
                        $hasCover = false;
                        foreach ($gallery as $img) {
                            if ($img['is_cover'] == 1) {
                                $hasCover = true;
                                break;
                            }
                        }
                        
                        // If no cover in gallery, use program image as first item
                        if (!$hasCover) {
                            array_unshift($galleryData, [
                                'image' => $program['image'],
                                'description' => $program['description'] ?? ''
                            ]);
                        }
                    }
                }
            }
        }
        
        $data = [
            'socialMedia' => $this->socialMediaModel->getActive(),
            'settings' => $this->getSettings(),
            'programData' => $programData,
            'galleryData' => $galleryData,
        ];
        
        $this->view('home/activities-gallery', $data);
    }
    
    public function profile() {
        track_visit('/profile');
        
        $data = [
            'employees' => $this->employeeModel->getAllGrouped(),
            'awards' => $this->awardModel->getAllOrdered(),
            'kepalaSekolah' => $this->kepalaSekolahModel->get(),
            'karyawan' => $this->karyawanModel->getAllOrdered(),
            'fasilitas' => $this->fasilitasModel->all('created_at DESC'),
            'socialMedia' => $this->socialMediaModel->getActive(),
            'settings' => $this->getSettings(),
            'prakata' => $this->prakataModel->get(),
        ];
        
        $this->view('home/profile', $data);
    }
    
    public function profileGallery() {
        track_visit('/profile-gallery');
        
        $fasilitasId = $this->input('id', null);
        
        $fasilitas = null;
        $galleryImages = [];
        
        if ($fasilitasId) {
            $fasilitas = $this->fasilitasModel->getById($fasilitasId);
            if ($fasilitas) {
                $galleryImages = $this->fasilitasModel->getGalleryImages($fasilitasId);
            }
        }
        
        $data = [
            'fasilitas' => $fasilitas,
            'galleryImages' => $galleryImages,
            'allFasilitas' => $this->fasilitasModel->all('created_at DESC'),
            'socialMedia' => $this->socialMediaModel->getActive(),
            'settings' => $this->getSettings(),
        ];
        
        $this->view('home/profile-gallery', $data);
    }
    
    public function articles() {
        track_visit('/articles');
        
        // DEBUG: Check all articles in DB
        $db = Database::getInstance();
        $allArticles = $db->query("SELECT id, title, status, published_at, DATE_FORMAT(published_at, '%Y-%m-%d %H:%i:%s') as formatted_date FROM articles ORDER BY id DESC")->fetchAll();
        error_log("=== ALL ARTICLES IN DB ===");
        error_log("Server NOW(): " . date('Y-m-d H:i:s'));
        error_log("Database NOW(): " . $db->query("SELECT NOW() as now")->fetch()['now']);
        foreach ($allArticles as $art) {
            error_log("Article #{$art['id']}: {$art['title']} | Status: {$art['status']} | Published: {$art['formatted_date']}");
        }
        error_log("========================");
        
        $page = $this->input('page', 1);
        // Changed from 9 to 20 to ensure we show enough articles on first page
        $pagination = $this->articleModel->paginate($page, 20, 'status = :status AND published_at <= NOW()', 
            ['status' => 'published'], 'published_at DESC');
        
        error_log("Articles Controller - Total articles fetched: " . count($pagination['data']));
        error_log("Articles Controller - Pagination info: " . json_encode([
            'total' => $pagination['total'],
            'current_page' => $pagination['current_page'],
            'last_page' => $pagination['last_page']
        ]));
        
        $data = [
            'articles' => $pagination['data'],
            'pagination' => $pagination,
            'socialMedia' => $this->socialMediaModel->getActive(),
            'settings' => $this->getSettings(),
        ];
        
        $this->view('home/articles', $data);
    }
    
    public function article($slug) {
        track_visit('/article/' . $slug);
        
        $article = $this->articleModel->getBySlug($slug);
        
        if (!$article || $article['status'] !== 'published') {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }
        
        // Increment views
        $this->articleModel->incrementViews($article['id']);
        
        // Get other articles (excluding current article)
        $otherArticles = $this->articleModel->where(
            'status = :status AND published_at <= NOW() AND id != :id', 
            ['status' => 'published', 'id' => $article['id']], 
            'published_at DESC', 
            4
        );
        
        $data = [
            'article' => $article,
            'otherArticles' => $otherArticles,
            'socialMedia' => $this->socialMediaModel->getActive(),
            'settings' => $this->getSettings(),
        ];
        
        $this->view('home/article-detail', $data);
    }
    
    public function articleDetail() {
        track_visit('/article-detail');
        
        $id = $this->input('id', null);
        
        if (!$id) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }
        
        $article = $this->articleModel->find($id);
        
        if (!$article || $article['status'] !== 'published') {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }
        
        // Increment views
        $this->articleModel->incrementViews($article['id']);
        
        // Get other articles (excluding current article)
        $otherArticles = $this->articleModel->where(
            'status = :status AND published_at <= NOW() AND id != :id', 
            ['status' => 'published', 'id' => $article['id']], 
            'published_at DESC', 
            4
        );
        
        $data = [
            'article' => $article,
            'otherArticles' => $otherArticles,
            'socialMedia' => $this->socialMediaModel->getActive(),
            'settings' => $this->getSettings(),
        ];
        
        $this->view('home/article-detail', $data);
    }
    
    public function registration() {
        track_visit('/registration');
        
        $formFieldModel = new FormField();
        
        $data = [
            'formFields' => $formFieldModel->getActive(),
            'socialMedia' => $this->socialMediaModel->getActive(),
            'settings' => $this->getSettings(),
        ];
        
        $this->view('home/registration', $data);
    }
    
    public function submitRegistration() {
        if (!$this->isPost()) {
            $this->redirect('/registration');
            return;
        }
        
        csrf_verify();
        
        $data = [
            'parent_name' => sanitize($this->input('parent_name')),
            'child_name' => sanitize($this->input('child_name')),
            'child_age' => sanitize($this->input('child_age')),
            'address' => sanitize($this->input('address')),
            'whatsapp' => sanitize($this->input('whatsapp')),
        ];
        
        // Validation
        if (empty($data['parent_name']) || empty($data['child_name']) || 
            empty($data['child_age']) || empty($data['address']) || empty($data['whatsapp'])) {
            flash('error', 'Mohon lengkapi semua field yang diperlukan');
            set_old($_POST);
            $this->redirect('/registration');
            return;
        }
        
        // Save registration to database
        $registrationData = [
            'parent_name' => $data['parent_name'],
            'child_name' => $data['child_name'],
            'child_age' => $data['child_age'],
            'address' => $data['address'],
            'whatsapp' => $data['whatsapp'],
            'status' => 'new',
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
        ];
        
        $this->registrationModel->create($registrationData);
        
        // Get WhatsApp settings from registration_settings table
        $whatsappNumber = $this->registrationSettingModel->get('whatsapp_number') ?? '6281234567890';
        $template = $this->registrationSettingModel->get('whatsapp_template') ?? "*PENDAFTARAN BARU - Zivana Montessori School*\n\n*Nama Anak:* {childName}\n*Nama Orang Tua:* {parentName}\n*Nomor Telepon:* {phone}\n{address}\n{message}\n\nTerima kasih telah mendaftar di Zivana Montessori School!";
        
        // Build WhatsApp message using template
        $message = str_replace(
            ['{childName}', '{childAge}', '{parentName}', '{phone}', '{address}', '{message}'],
            [
                $data['child_name'],
                $data['child_age'],
                $data['parent_name'],
                $data['whatsapp'],
                $data['address'] ? '*Alamat:* ' . $data['address'] : '',
                '' // No additional message for now
            ],
            $template
        );
        
        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=" . urlencode($message);
        
        clear_old();
        $this->redirect($whatsappUrl);
    }
    
    private function getSettings() {
        return [
            'school_name' => $this->settingModel->get('school_name', APP_NAME),
            'school_address' => $this->settingModel->get('school_address', ''),
            'school_phone' => $this->settingModel->get('school_phone', ''),
            'school_email' => $this->settingModel->get('school_email', ''),
            'school_description' => $this->settingModel->get('school_description', ''),
            'whatsapp_number' => $this->settingModel->get('whatsapp_number', ''),
        ];
    }
}
