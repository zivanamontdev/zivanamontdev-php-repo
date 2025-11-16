<?php
/**
 * Admin Management Controller
 * Handles schedules, awards, social media
 */
class ManagementController extends Controller {
    private $scheduleModel;
    private $awardModel;
    private $socialMediaModel;
    private $settingModel;
    
    public function __construct() {
        parent::__construct();
        $this->middleware(AuthMiddleware::class);
        $this->scheduleModel = new Schedule();
        $this->awardModel = new Award();
        $this->socialMediaModel = new SocialMedia();
        $this->settingModel = new Setting();
    }
    
    // Schedules
    public function schedules() {
        $data = [
            'schedules' => $this->scheduleModel->getAllOrdered(),
        ];
        
        $this->view('admin/management/schedules', $data);
    }
    
    public function createSchedule() {
        if (!$this->isPost()) {
            $this->redirect('/admin/management/schedules');
            return;
        }
        
        csrf_verify();
        
        $data = [
            'time_start' => $this->input('time_start'),
            'time_end' => $this->input('time_end'),
            'activity_name' => sanitize($this->input('activity_name')),
            'description' => sanitize($this->input('description')),
            'display_order' => $this->input('display_order', 0),
        ];
        
        if (empty($data['time_start']) || empty($data['activity_name'])) {
            flash('error', 'Time and activity name are required');
            $this->redirect('/admin/management/schedules');
            return;
        }
        
        $this->scheduleModel->create($data);
        
        flash('success', 'Schedule created successfully');
        $this->redirect('/admin/management/schedules');
    }
    
    public function updateSchedule($id) {
        if (!$this->isPost()) {
            $this->redirect('/admin/management/schedules');
            return;
        }
        
        csrf_verify();
        
        $data = [
            'time_start' => $this->input('time_start'),
            'time_end' => $this->input('time_end'),
            'activity_name' => sanitize($this->input('activity_name')),
            'description' => sanitize($this->input('description')),
            'display_order' => $this->input('display_order', 0),
        ];
        
        $this->scheduleModel->update($id, $data);
        
        flash('success', 'Schedule updated successfully');
        $this->redirect('/admin/management/schedules');
    }
    
    public function deleteSchedule($id) {
        csrf_verify();
        
        $this->scheduleModel->delete($id);
        
        flash('success', 'Schedule deleted successfully');
        $this->redirect('/admin/management/schedules');
    }
    
    // Awards
    public function awards() {
        $data = [
            'awards' => $this->awardModel->getAllOrdered(),
        ];
        
        $this->view('admin/management/awards', $data);
    }
    
    public function createAward() {
        if (!$this->isPost()) {
            $this->redirect('/admin/management/awards');
            return;
        }
        
        csrf_verify();
        
        $title = sanitize($this->input('title'));
        $description = sanitize($this->input('description'));
        $yearReceived = $this->input('year_received');
        $displayOrder = $this->input('display_order', 0);
        
        if (empty($title) || empty($yearReceived)) {
            flash('error', 'Title and year are required');
            $this->redirect('/admin/management/awards');
            return;
        }
        
        // Handle image upload
        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload = upload_file($_FILES['image'], 'awards');
            if ($upload['success']) {
                $image = $upload['path'];
            } else {
                flash('error', 'Image upload failed');
                $this->redirect('/admin/management/awards');
                return;
            }
        } else {
            flash('error', 'Award image is required');
            $this->redirect('/admin/management/awards');
            return;
        }
        
        $data = [
            'title' => $title,
            'description' => $description,
            'image' => $image,
            'year_received' => $yearReceived,
            'display_order' => $displayOrder,
        ];
        
        $this->awardModel->create($data);
        
        flash('success', 'Award created successfully');
        $this->redirect('/admin/management/awards');
    }
    
    public function updateAward($id) {
        if (!$this->isPost()) {
            $this->redirect('/admin/management/awards');
            return;
        }
        
        csrf_verify();
        
        $award = $this->awardModel->find($id);
        
        if (!$award) {
            flash('error', 'Award not found');
            $this->redirect('/admin/management/awards');
            return;
        }
        
        $title = sanitize($this->input('title'));
        $description = sanitize($this->input('description'));
        $yearReceived = $this->input('year_received');
        $displayOrder = $this->input('display_order', 0);
        
        // Handle image upload
        $image = $award['image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload = upload_file($_FILES['image'], 'awards');
            if ($upload['success']) {
                // Delete old image
                if ($image) {
                    delete_file($image);
                }
                $image = $upload['path'];
            }
        }
        
        $data = [
            'title' => $title,
            'description' => $description,
            'image' => $image,
            'year_received' => $yearReceived,
            'display_order' => $displayOrder,
        ];
        
        $this->awardModel->update($id, $data);
        
        flash('success', 'Award updated successfully');
        $this->redirect('/admin/management/awards');
    }
    
    public function deleteAward($id) {
        csrf_verify();
        
        $award = $this->awardModel->find($id);
        
        if ($award && $award['image']) {
            delete_file($award['image']);
        }
        
        $this->awardModel->delete($id);
        
        flash('success', 'Award deleted successfully');
        $this->redirect('/admin/management/awards');
    }
    
    // Social Media
    public function socialMedia() {
        $data = [
            'socialMedia' => $this->socialMediaModel->all('display_order ASC'),
        ];
        
        $this->view('admin/management/social-media', $data);
    }
    
    public function createSocialMedia() {
        if (!$this->isPost()) {
            $this->redirect('/admin/management/social-media');
            return;
        }
        
        csrf_verify();
        
        $data = [
            'platform' => sanitize($this->input('platform')),
            'account_name' => sanitize($this->input('account_name')),
            'url' => sanitize($this->input('url')),
            'display_order' => $this->input('display_order', 0),
            'is_active' => $this->input('is_active', 1),
        ];
        
        if (empty($data['platform']) || empty($data['account_name']) || empty($data['url'])) {
            flash('error', 'All fields are required');
            $this->redirect('/admin/management/social-media');
            return;
        }
        
        $this->socialMediaModel->create($data);
        
        flash('success', 'Social media account created successfully');
        $this->redirect('/admin/management/social-media');
    }
    
    public function updateSocialMedia($id) {
        if (!$this->isPost()) {
            $this->redirect('/admin/management/social-media');
            return;
        }
        
        csrf_verify();
        
        $data = [
            'platform' => sanitize($this->input('platform')),
            'account_name' => sanitize($this->input('account_name')),
            'url' => sanitize($this->input('url')),
            'display_order' => $this->input('display_order', 0),
            'is_active' => $this->input('is_active', 1),
        ];
        
        $this->socialMediaModel->update($id, $data);
        
        flash('success', 'Social media account updated successfully');
        $this->redirect('/admin/management/social-media');
    }
    
    public function deleteSocialMedia($id) {
        csrf_verify();
        
        $this->socialMediaModel->delete($id);
        
        flash('success', 'Social media account deleted successfully');
        $this->redirect('/admin/management/social-media');
    }
    
    // Settings
    public function settings() {
        $data = [
            'settings' => $this->settingModel->getAllGrouped(),
        ];
        
        $this->view('admin/management/settings', $data);
    }
    
    public function updateSettings() {
        if (!$this->isPost()) {
            $this->redirect('/admin/management/settings');
            return;
        }
        
        csrf_verify();
        
        $settings = $_POST['settings'] ?? [];
        
        foreach ($settings as $key => $value) {
            $this->settingModel->set($key, $value);
        }
        
        flash('success', 'Settings updated successfully');
        $this->redirect('/admin/management/settings');
    }
}
