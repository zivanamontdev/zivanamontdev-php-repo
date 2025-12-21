<?php
/**
 * Karyawan Model
 * Handles database operations for school employees
 */
class Karyawan extends Model {
    protected $table = 'karyawan';
    
    /**
     * Get all employees ordered by sort_order
     */
    public function getAllOrdered() {
        return $this->all('sort_order ASC, id ASC');
    }
    
    /**
     * Get employee by ID
     */
    public function getById($id) {
        return $this->find($id);
    }
    
    /**
     * Create new employee
     */
    public function createEmployee($data) {
        // Get max sort_order and increment
        $sql = "SELECT MAX(sort_order) as max_order FROM {$this->table}";
        $result = $this->db->fetchOne($sql);
        $data['sort_order'] = ($result['max_order'] ?? 0) + 1;
        
        return $this->create($data);
    }
    
    /**
     * Update employee
     */
    public function updateEmployee($id, $data) {
        return $this->update($id, $data);
    }
    
    /**
     * Delete employee
     */
    public function deleteEmployee($id) {
        return $this->delete($id);
    }
    
    /**
     * Update sort order for multiple employees
     */
    public function updateSortOrder($orderData) {
        foreach ($orderData as $id => $order) {
            $this->update($id, ['sort_order' => $order]);
        }
        return true;
    }
    
    /**
     * Alias for updateSortOrder
     */
    public function updateOrder($orderData) {
        return $this->updateSortOrder($orderData);
    }
}
