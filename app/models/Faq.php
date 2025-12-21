<?php
/**
 * FAQ Model
 * Handles FAQ data operations
 */
class Faq extends Model {
    protected $table = 'faqs';
    
    /**
     * Get all FAQs ordered by display_order
     */
    public function getAll() {
        $query = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY display_order ASC, id ASC";
        return $this->db->fetchAll($query);
    }
    
    /**
     * Get FAQ by ID
     */
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        return $this->db->fetchOne($query, ['id' => $id]);
    }
    
    /**
     * Create new FAQ
     */
    public function create($data) {
        // Get max display order
        $query = "SELECT COALESCE(MAX(display_order), 0) + 1 as next_order FROM {$this->table}";
        $result = $this->db->fetchOne($query);
        $nextOrder = $result['next_order'];
        
        $query = "INSERT INTO {$this->table} (question, answer, display_order) 
                  VALUES (:question, :answer, :display_order)";
        $this->db->query($query, [
            'question' => $data['question'],
            'answer' => $data['answer'],
            'display_order' => $nextOrder
        ]);
        
        return true;
    }
    
    /**
     * Update FAQ
     */
    public function update($id, $data) {
        $query = "UPDATE {$this->table} 
                  SET question = :question, 
                      answer = :answer, 
                      updated_at = CURRENT_TIMESTAMP 
                  WHERE id = :id";
        $stmt = $this->db->query($query, [
            'id' => $id,
            'question' => $data['question'],
            'answer' => $data['answer']
        ]);
        
        return true;
    }
    
    /**
     * Delete FAQ (soft delete)
     */
    public function delete($id) {
        return $this->db->delete($this->table, 'id = :id', ['id' => $id]) > 0;
    }
    
    /**
     * Update FAQ order
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
