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
    
    public function __construct() {
        parent::__construct();
        $this->programModel = new Program();
        $this->articleModel = new Article();
        $this->employeeModel = new Employee();
        $this->scheduleModel = new Schedule();
        $this->awardModel = new Award();
        $this->socialMediaModel = new SocialMedia();
        $this->settingModel = new Setting();
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
        
        $registrationModel = new Registration();
        
        $data = [
            'child_name' => sanitize($this->input('child_name')),
            'parent_name' => sanitize($this->input('parent_name')),
            'email' => sanitize($this->input('email')),
            'phone' => sanitize($this->input('phone')),
            'address' => sanitize($this->input('address')),
            'message' => sanitize($this->input('message')),
            'ip_address' => get_client_ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
        ];
        
        // Validation
        if (empty($data['child_name']) || empty($data['parent_name']) || 
            empty($data['email']) || empty($data['phone'])) {
            flash('error', 'Please fill in all required fields');
            set_old($_POST);
            $this->redirect('/registration');
            return;
        }
        
        if (!is_email($data['email'])) {
            flash('error', 'Please provide a valid email address');
            set_old($_POST);
            $this->redirect('/registration');
            return;
        }
        
        // Save to database
        $registrationModel->create($data);
        
        // Build WhatsApp message
        $whatsappNumber = $this->settingModel->get('whatsapp_number', '');
        $message = "Halo, saya ingin mendaftarkan anak saya di Zivana Montessori School\n\n";
        $message .= "Nama Anak: {$data['child_name']}\n";
        $message .= "Nama Orang Tua: {$data['parent_name']}\n";
        $message .= "Email: {$data['email']}\n";
        $message .= "No. Telepon: {$data['phone']}\n";
        if (!empty($data['address'])) {
            $message .= "Alamat: {$data['address']}\n";
        }
        if (!empty($data['message'])) {
            $message .= "Pesan: {$data['message']}\n";
        }
        
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
