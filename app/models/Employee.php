<?php
class Employee extends Model {
    protected $table = 'employees';
    
    public function getActive() {
        return $this->where('is_active = 1', [], 'display_order ASC, created_at ASC');
    }
    
    public function getByLevel($level) {
        return $this->where('level = :level AND is_active = 1', ['level' => $level], 'display_order ASC');
    }
    
    public function getAllGrouped() {
        $levels = [
            'leadership' => 'School Leadership',
            'educational_support' => 'Educational Support Staff',
            'teaching_staff' => 'Teaching Staff',
            'operational_staff' => 'Operational Staff'
        ];
        
        $result = [];
        foreach ($levels as $key => $label) {
            $result[$key] = [
                'label' => $label,
                'employees' => $this->getByLevel($key)
            ];
        }
        
        return $result;
    }
}
