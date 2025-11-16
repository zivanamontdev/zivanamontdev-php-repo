<?php
/**
 * Admin Employee Controller
 */
class EmployeeController extends Controller {
    private $employeeModel;
    
    public function __construct() {
        parent::__construct();
        $this->middleware(AuthMiddleware::class);
        $this->employeeModel = new Employee();
    }
    
    public function index() {
        $data = [
            'employees' => $this->employeeModel->all('display_order ASC, created_at ASC'),
        ];
        
        $this->view('admin/employees/index', $data);
    }
    
    public function create() {
        $this->view('admin/employees/create');
    }
    
    public function store() {
        if (!$this->isPost()) {
            $this->redirect('/admin/employees');
            return;
        }
        
        csrf_verify();
        
        $fullName = sanitize($this->input('full_name'));
        $position = sanitize($this->input('position'));
        $level = $this->input('level');
        $bio = $this->input('bio');
        $displayOrder = $this->input('display_order', 0);
        $isActive = $this->input('is_active', 1);
        
        if (empty($fullName) || empty($position) || empty($level)) {
            flash('error', 'Full name, position, and level are required');
            set_old($_POST);
            $this->redirect('/admin/employees/create');
            return;
        }
        
        // Handle photo upload
        $photo = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $upload = upload_file($_FILES['photo'], 'employees');
            if ($upload['success']) {
                $photo = $upload['path'];
            }
        }
        
        $data = [
            'full_name' => $fullName,
            'position' => $position,
            'level' => $level,
            'photo' => $photo,
            'bio' => $bio,
            'display_order' => $displayOrder,
            'is_active' => $isActive,
        ];
        
        $this->employeeModel->create($data);
        
        flash('success', 'Employee created successfully');
        $this->redirect('/admin/employees');
    }
    
    public function edit($id) {
        $employee = $this->employeeModel->find($id);
        
        if (!$employee) {
            flash('error', 'Employee not found');
            $this->redirect('/admin/employees');
            return;
        }
        
        $data = [
            'employee' => $employee,
        ];
        
        $this->view('admin/employees/edit', $data);
    }
    
    public function update($id) {
        if (!$this->isPost()) {
            $this->redirect('/admin/employees/' . $id . '/edit');
            return;
        }
        
        csrf_verify();
        
        $employee = $this->employeeModel->find($id);
        
        if (!$employee) {
            flash('error', 'Employee not found');
            $this->redirect('/admin/employees');
            return;
        }
        
        $fullName = sanitize($this->input('full_name'));
        $position = sanitize($this->input('position'));
        $level = $this->input('level');
        $bio = $this->input('bio');
        $displayOrder = $this->input('display_order', 0);
        $isActive = $this->input('is_active', 1);
        
        if (empty($fullName) || empty($position) || empty($level)) {
            flash('error', 'Full name, position, and level are required');
            set_old($_POST);
            $this->redirect('/admin/employees/' . $id . '/edit');
            return;
        }
        
        // Handle photo upload
        $photo = $employee['photo'];
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $upload = upload_file($_FILES['photo'], 'employees');
            if ($upload['success']) {
                // Delete old photo
                if ($photo) {
                    delete_file($photo);
                }
                $photo = $upload['path'];
            }
        }
        
        $data = [
            'full_name' => $fullName,
            'position' => $position,
            'level' => $level,
            'photo' => $photo,
            'bio' => $bio,
            'display_order' => $displayOrder,
            'is_active' => $isActive,
        ];
        
        $this->employeeModel->update($id, $data);
        
        flash('success', 'Employee updated successfully');
        $this->redirect('/admin/employees/' . $id . '/edit');
    }
    
    public function delete($id) {
        csrf_verify();
        
        $employee = $this->employeeModel->find($id);
        
        if (!$employee) {
            flash('error', 'Employee not found');
            $this->redirect('/admin/employees');
            return;
        }
        
        // Delete photo
        if ($employee['photo']) {
            delete_file($employee['photo']);
        }
        
        $this->employeeModel->delete($id);
        
        flash('success', 'Employee deleted successfully');
        $this->redirect('/admin/employees');
    }
}
