<?php
class Registration extends Model {
    protected $table = 'registrations';
    
    public function getRecent($limit = 10) {
        return $this->where('1=1', [], 'created_at DESC', $limit);
    }
    
    public function getByStatus($status) {
        return $this->where('status = :status', ['status' => $status], 'created_at DESC');
    }
    
    public function getPaginated($page = 1, $perPage = 10, $search = '') {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM {$this->table}";
        
        $params = [];
        
        if (!empty($search)) {
            $sql .= " WHERE child_name LIKE :search OR parent_name LIKE :search2 OR whatsapp LIKE :search3";
            $params['search'] = "%{$search}%";
            $params['search2'] = "%{$search}%";
            $params['search3'] = "%{$search}%";
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function countRegistrations($search = '') {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " WHERE child_name LIKE :search OR parent_name LIKE :search2 OR whatsapp LIKE :search3";
            $params['search'] = "%{$search}%";
            $params['search2'] = "%{$search}%";
            $params['search3'] = "%{$search}%";
        }
        
        $result = $this->db->fetchOne($sql, $params);
        return $result ? $result['total'] : 0;
    }
    
    public function findRegistration($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    public function deleteRegistration($id) {
        return $this->db->delete($this->table, 'id = :id', ['id' => $id]);
    }
}
