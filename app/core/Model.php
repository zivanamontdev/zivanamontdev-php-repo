<?php
/**
 * Base Model Class
 */
class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function all($orderBy = null) {
        $sql = "SELECT * FROM {$this->table}";
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        return $this->db->fetchAll($sql);
    }
    
    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    public function where($conditions, $params = [], $orderBy = null, $limit = null) {
        $sql = "SELECT * FROM {$this->table} WHERE {$conditions}";
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        return $this->db->fetchAll($sql, $params);
    }
    
    public function whereOne($conditions, $params = []) {
        $sql = "SELECT * FROM {$this->table} WHERE {$conditions} LIMIT 1";
        return $this->db->fetchOne($sql, $params);
    }
    
    public function create($data) {
        return $this->db->insert($this->table, $data);
    }
    
    public function update($id, $data) {
        return $this->db->update($this->table, $data, "{$this->primaryKey} = :id", ['id' => $id]);
    }
    
    public function delete($id) {
        return $this->db->delete($this->table, "{$this->primaryKey} = :id", ['id' => $id]);
    }
    
    public function count($conditions = '1=1', $params = []) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE {$conditions}";
        $result = $this->db->fetchOne($sql, $params);
        return $result['count'] ?? 0;
    }
    
    public function exists($conditions, $params = []) {
        return $this->count($conditions, $params) > 0;
    }
    
    public function paginate($page = 1, $perPage = 10, $conditions = '1=1', $params = [], $orderBy = null) {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM {$this->table} WHERE {$conditions}";
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        
        $data = $this->db->fetchAll($sql, $params);
        $total = $this->count($conditions, $params);
        
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ];
    }
}
