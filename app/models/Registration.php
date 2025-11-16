<?php
class Registration extends Model {
    protected $table = 'registrations';
    
    public function getRecent($limit = 10) {
        return $this->all('created_at DESC')->slice(0, $limit);
    }
    
    public function getByStatus($status) {
        return $this->where('status = :status', ['status' => $status], 'created_at DESC');
    }
}
