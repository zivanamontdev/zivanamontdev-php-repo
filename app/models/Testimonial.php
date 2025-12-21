<?php
/**
 * Testimonial Model
 * Handles testimonial data operations
 */
class Testimonial extends Model {
    protected $table = 'testimonials';
    
    /**
     * Get all testimonials ordered by display_order
     */
    public function getAll() {
        $query = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY display_order ASC, id ASC LIMIT 3";
        return $this->db->fetchAll($query);
    }
    
    /**
     * Get testimonial by ID
     */
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        return $this->db->fetchOne($query, ['id' => $id]);
    }
    
    /**
     * Create new testimonial
     */
    public function create($data) {
        // Get max display order
        $query = "SELECT COALESCE(MAX(display_order), 0) + 1 as next_order FROM {$this->table}";
        $result = $this->db->fetchOne($query);
        $nextOrder = $result['next_order'];
        
        $insertData = [
            'parent_name' => $data['parent_name'],
            'child_name' => $data['child_name'],
            'testimonial_text' => $data['testimonial_text'],
            'highlight_text' => $data['highlight_text'],
            'display_order' => $nextOrder
        ];
        
        if (!empty($data['image'])) {
            $insertData['image'] = $data['image'];
        }
        
        $query = "INSERT INTO {$this->table} (parent_name, child_name, testimonial_text, highlight_text, image, display_order) 
                  VALUES (:parent_name, :child_name, :testimonial_text, :highlight_text, :image, :display_order)";
        $this->db->query($query, $insertData);
        
        return true;
    }
    
    /**
     * Update testimonial
     */
    public function update($id, $data) {
        $updateData = [
            'id' => $id,
            'parent_name' => $data['parent_name'],
            'child_name' => $data['child_name'],
            'testimonial_text' => $data['testimonial_text'],
            'highlight_text' => $data['highlight_text']
        ];
        
        $query = "UPDATE {$this->table} 
                  SET parent_name = :parent_name, 
                      child_name = :child_name, 
                      testimonial_text = :testimonial_text, 
                      highlight_text = :highlight_text";
        
        // Add image to update if provided
        if (isset($data['image'])) {
            $query .= ", image = :image";
            $updateData['image'] = $data['image'];
        }
        
        $query .= ", updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        
        $this->db->query($query, $updateData);
        return true;
    }
    
    /**
     * Delete testimonial
     */
    public function delete($id) {
        return $this->db->delete($this->table, 'id = :id', ['id' => $id]) > 0;
    }
    
    /**
     * Update testimonial order
     */
    public function updateOrder($orders) {
        try {
            $conn = $this->db->getConnection();
            $conn->beginTransaction();
            
            foreach ($orders as $id => $order) {
                $query = "UPDATE {$this->table} SET display_order = :order WHERE id = :id";
                $this->db->query($query, [
                    'id' => $id,
                    'order' => $order
                ]);
            }
            
            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollBack();
            return false;
        }
    }
}
