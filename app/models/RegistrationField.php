<?php

class RegistrationField extends Model
{
    protected $table = 'registration_fields';
    
    /**
     * Get all active fields ordered by order_index
     */
    public function getActiveFields()
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY order_index ASC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get field by ID
     */
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    /**
     * Create new field
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (field_name, field_label, field_type, placeholder_text, is_required, order_index) 
                VALUES (:field_name, :field_label, :field_type, :placeholder_text, :is_required, :order_index)";
        
        $stmt = $this->db->query($sql, [
            'field_name' => $data['field_name'],
            'field_label' => $data['field_label'],
            'field_type' => $data['field_type'],
            'placeholder_text' => $data['placeholder_text'] ?? '',
            'is_required' => $data['is_required'] ?? 0,
            'order_index' => $data['order_index'] ?? 0
        ]);
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Update field
     */
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET 
                field_name = :field_name,
                field_label = :field_label,
                field_type = :field_type,
                placeholder_text = :placeholder_text,
                is_required = :is_required,
                order_index = :order_index
                WHERE id = :id";
        
        $stmt = $this->db->query($sql, [
            'id' => $id,
            'field_name' => $data['field_name'],
            'field_label' => $data['field_label'],
            'field_type' => $data['field_type'],
            'placeholder_text' => $data['placeholder_text'] ?? '',
            'is_required' => $data['is_required'] ?? 0,
            'order_index' => $data['order_index'] ?? 0
        ]);
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Delete field (soft delete by setting is_active = 0)
     */
    public function delete($id)
    {
        $sql = "UPDATE {$this->table} SET is_active = 0 WHERE id = :id";
        $stmt = $this->db->query($sql, ['id' => $id]);
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Update order of multiple fields
     */
    public function updateOrder($orders)
    {
        try {
            $this->db->beginTransaction();
            
            $sql = "UPDATE {$this->table} SET order_index = :order_index WHERE id = :id";
            
            foreach ($orders as $id => $order) {
                $this->db->query($sql, [
                    'id' => $id,
                    'order_index' => $order
                ]);
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    /**
     * Get next order index
     */
    public function getNextOrderIndex()
    {
        $sql = "SELECT MAX(order_index) as max_order FROM {$this->table}";
        $result = $this->db->fetchOne($sql);
        return ($result['max_order'] ?? 0) + 1;
    }
}
