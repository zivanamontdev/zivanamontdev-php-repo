<?php
/**
 * Admin Program Controller
 */
class ProgramController extends Controller {
    private $programModel;
    private $imageModel;
    
    public function __construct() {
        parent::__construct();
        $this->middleware(AuthMiddleware::class);
        $this->programModel = new Program();
        $this->imageModel = new Image();
    }
    
    public function index() {
        $data = [
            'programs' => $this->programModel->all('display_order ASC, created_at DESC'),
        ];
        
        $this->view('admin/programs/index', $data);
    }
    
    public function create() {
        $this->view('admin/programs/create');
    }
    
    public function store() {
        if (!$this->isPost()) {
            $this->redirect('/admin/programs');
            return;
        }
        
        csrf_verify();
        
        $data = [
            'name' => sanitize($this->input('name')),
            'description' => $this->input('description'), // Allow HTML
            'is_active' => $this->input('is_active', 1),
            'display_order' => $this->input('display_order', 0),
        ];
        
        if (empty($data['name']) || empty($data['description'])) {
            flash('error', 'Name and description are required');
            set_old($_POST);
            $this->redirect('/admin/programs/create');
            return;
        }
        
        $id = $this->programModel->create($data);
        
        flash('success', 'Program created successfully');
        $this->redirect('/admin/programs/' . $id . '/edit');
    }
    
    public function edit($id) {
        $program = $this->programModel->getWithImages($id);
        
        if (!$program) {
            flash('error', 'Program not found');
            $this->redirect('/admin/programs');
            return;
        }
        
        $data = [
            'program' => $program,
        ];
        
        $this->view('admin/programs/edit', $data);
    }
    
    public function update($id) {
        if (!$this->isPost()) {
            $this->redirect('/admin/programs/' . $id . '/edit');
            return;
        }
        
        csrf_verify();
        
        $program = $this->programModel->find($id);
        
        if (!$program) {
            flash('error', 'Program not found');
            $this->redirect('/admin/programs');
            return;
        }
        
        $data = [
            'name' => sanitize($this->input('name')),
            'description' => $this->input('description'),
            'is_active' => $this->input('is_active', 1),
            'display_order' => $this->input('display_order', 0),
        ];
        
        if (empty($data['name']) || empty($data['description'])) {
            flash('error', 'Name and description are required');
            set_old($_POST);
            $this->redirect('/admin/programs/' . $id . '/edit');
            return;
        }
        
        $this->programModel->update($id, $data);
        
        flash('success', 'Program updated successfully');
        $this->redirect('/admin/programs/' . $id . '/edit');
    }
    
    public function delete($id) {
        csrf_verify();
        
        $program = $this->programModel->find($id);
        
        if (!$program) {
            flash('error', 'Program not found');
            $this->redirect('/admin/programs');
            return;
        }
        
        // Delete associated images
        $this->imageModel->deleteByProgram($id);
        
        // Delete program
        $this->programModel->delete($id);
        
        flash('success', 'Program deleted successfully');
        $this->redirect('/admin/programs');
    }
    
    public function uploadImage($id) {
        if (!$this->isPost()) {
            $this->redirect('/admin/programs/' . $id . '/edit');
            return;
        }
        
        csrf_verify();
        
        $program = $this->programModel->find($id);
        
        if (!$program) {
            flash('error', 'Program not found');
            $this->redirect('/admin/programs');
            return;
        }
        
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            flash('error', 'Please select an image to upload');
            $this->redirect('/admin/programs/' . $id . '/edit');
            return;
        }
        
        $upload = upload_file($_FILES['image'], 'programs');
        
        if (!$upload['success']) {
            flash('error', $upload['message']);
            $this->redirect('/admin/programs/' . $id . '/edit');
            return;
        }
        
        $this->imageModel->create([
            'program_id' => $id,
            'filename' => $upload['path'],
            'caption' => sanitize($this->input('caption')),
            'display_order' => $this->input('display_order', 0),
        ]);
        
        flash('success', 'Image uploaded successfully');
        $this->redirect('/admin/programs/' . $id . '/edit');
    }
    
    public function deleteImage($programId, $imageId) {
        csrf_verify();
        
        $image = $this->imageModel->find($imageId);
        
        if (!$image || $image['program_id'] != $programId) {
            flash('error', 'Image not found');
            $this->redirect('/admin/programs/' . $programId . '/edit');
            return;
        }
        
        delete_file($image['filename']);
        $this->imageModel->delete($imageId);
        
        flash('success', 'Image deleted successfully');
        $this->redirect('/admin/programs/' . $programId . '/edit');
    }
}
