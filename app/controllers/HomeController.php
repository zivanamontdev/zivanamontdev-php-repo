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
    }
    
    public function index() {
        track_visit('/');
        
        $data = [
            'programs' => $this->programModel->getActive(),
            'articles' => $this->articleModel->getFeatured(3),
            'socialMedia' => $this->socialMediaModel->getActive(),
            'settings' => $this->getSettings(),
        ];
        
        $this->view('home/index', $data);
    }
    
    public function activities() {
        track_visit('/activities');
        
        $data = [
            'programs' => $this->programModel->getAllWithImages(),
            'schedules' => $this->scheduleModel->getAllOrdered(),
            'socialMedia' => $this->socialMediaModel->getActive(),
            'settings' => $this->getSettings(),
        ];
        
        $this->view('home/activities', $data);
    }
    
    public function activitiesGallery() {
        track_visit('/activities-gallery');
        
        $data = [
            'socialMedia' => $this->socialMediaModel->getActive(),
            'settings' => $this->getSettings(),
        ];
        
        $this->view('home/activities-gallery', $data);
    }
    
    public function profile() {
        track_visit('/profile');
        
        $data = [
            'employees' => $this->employeeModel->getAllGrouped(),
            'awards' => $this->awardModel->getAllOrdered(),
            'socialMedia' => $this->socialMediaModel->getActive(),
            'settings' => $this->getSettings(),
        ];
        
        $this->view('home/profile', $data);
    }
    
    public function profileGallery() {
        track_visit('/profile-gallery');
        
        $data = [
            'socialMedia' => $this->socialMediaModel->getActive(),
            'settings' => $this->getSettings(),
        ];
        
        $this->view('home/profile-gallery', $data);
    }
    
    public function articles() {
        track_visit('/articles');
        
        $page = $this->input('page', 1);
        $pagination = $this->articleModel->paginate($page, 9, 'status = :status AND published_at <= NOW()', 
            ['status' => 'published'], 'published_at DESC');
        
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
        
        // Get related articles
        $relatedArticles = $this->articleModel->getPublished(3);
        
        $data = [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
            'socialMedia' => $this->socialMediaModel->getActive(),
            'settings' => $this->getSettings(),
        ];
        
        $this->view('home/article-detail', $data);
    }
    
    public function articleDetail() {
        track_visit('/article-detail');
        
        // Static article data for demo purposes
        $article = [
            'title' => 'Parenting Ala Rasulullah',
            'author_name' => 'Admin Zivana',
            'published_at' => '2025-11-19',
            'content' => ''
        ];
        
        $data = [
            'article' => $article,
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
        
        // Build WhatsApp message
        $whatsappNumber = $this->settingModel->get('whatsapp_number', '6281234567890');
        $message = "Halo, saya ingin mendaftarkan anak saya di Zivana Montessori School\n\n";
        $message .= "Nama Orang Tua: {$data['parent_name']}\n";
        $message .= "Nama Anak: {$data['child_name']}\n";
        $message .= "Usia Anak: {$data['child_age']}\n";
        $message .= "Alamat: {$data['address']}\n";
        $message .= "Nomor WhatsApp: {$data['whatsapp']}\n";
        
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
