<?php
class Program extends Model {
    protected $table = 'programs';
    
    public function getActive() {
        return $this->where('is_active = 1', [], 'display_order ASC, created_at DESC');
    }
    
    public function getWithImages($id) {
        $program = $this->find($id);
        if ($program) {
            $imageModel = new Image();
            $program['images'] = $imageModel->getByProgram($id);
        }
        return $program;
    }
    
    public function getAllWithImages() {
        $programs = $this->getActive();
        $imageModel = new Image();
        
        foreach ($programs as &$program) {
            $program['images'] = $imageModel->getByProgram($program['id']);
        }
        
        return $programs;
    }
}
