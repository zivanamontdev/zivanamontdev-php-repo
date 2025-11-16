<?php
class Registration extends Model {
    protected $table = 'registrations';
    
    public function getRecent($limit = 10) {
        return $this->where('1=1', [], 'created_at DESC', $limit);
    }
    
    public function getByStatus($status) {
        return $this->where('status = :status', ['status' => $status], 'created_at DESC');
    }
}
