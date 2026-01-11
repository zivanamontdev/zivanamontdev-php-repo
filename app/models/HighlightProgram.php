<?php
/**
 * HighlightProgram Model
 * Handles highlight program data operations (linking to programs_tahun)
 */
class HighlightProgram extends Model {
    protected $table = 'highlight_programs';
    
    /**
     * Get all highlight programs with program details (max 3)
     * If no highlights found, return first 3 active programs_tahun
     */
    public function getAll() {
        // First, try to get highlight programs
        $query = "SELECT hp.id, hp.program_tahun_id, hp.display_order,
                         pt.name, pt.description, pt.image
                  FROM {$this->table} hp
                  INNER JOIN programs_tahun pt ON hp.program_tahun_id = pt.id
                  WHERE pt.is_active = 1
                  ORDER BY hp.display_order ASC, hp.id ASC
                  LIMIT 3";
        $results = $this->db->fetchAll($query);
        
        // If no highlights found, get first 3 active programs_tahun as fallback
        if (empty($results)) {
            $query = "SELECT id, name, description, image
                      FROM programs_tahun
                      WHERE is_active = 1
                      ORDER BY created_at DESC
                      LIMIT 3";
            $results = $this->db->fetchAll($query);
        }
        
        return $results;
    }
    
    /**
     * Get highlight program by ID
     */
    public function getById($id) {
        $query = "SELECT hp.id, hp.program_tahun_id, hp.display_order,
                         pt.name, pt.description, pt.image
                  FROM {$this->table} hp
                  INNER JOIN programs_tahun pt ON hp.program_tahun_id = pt.id
                  WHERE hp.id = :id";
        return $this->db->fetchOne($query, ['id' => $id]);
    }
    
    /**
     * Check if program is already highlighted
     */
    public function isProgramHighlighted($programTahunId) {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE program_tahun_id = :program_id";
        $result = $this->db->fetchOne($query, ['program_id' => $programTahunId]);
        return $result['count'] > 0;
    }
    
    /**
     * Get count of highlight programs
     */
    public function getCount() {
        $query = "SELECT COUNT(*) as count FROM {$this->table}";
        $result = $this->db->fetchOne($query);
        return $result['count'];
    }
    
    /**
     * Add program to highlights
     */
    public function addHighlight($programTahunId) {
        // Check if already at max (3)
        if ($this->getCount() >= 3) {
            return ['success' => false, 'message' => 'Maksimal 3 program highlight'];
        }
        
        // Check if program already highlighted
        if ($this->isProgramHighlighted($programTahunId)) {
            return ['success' => false, 'message' => 'Program sudah di-highlight'];
        }
        
        // Get next display order
        $query = "SELECT COALESCE(MAX(display_order), 0) + 1 as next_order FROM {$this->table}";
        $result = $this->db->fetchOne($query);
        $nextOrder = $result['next_order'];
        
        $query = "INSERT INTO {$this->table} (program_tahun_id, display_order) 
                  VALUES (:program_id, :display_order)";
        $this->db->query($query, [
            'program_id' => $programTahunId,
            'display_order' => $nextOrder
        ]);
        
        return ['success' => true, 'message' => 'Program berhasil di-highlight'];
    }
    
    /**
     * Replace highlight program
     */
    public function replaceHighlight($highlightId, $newProgramTahunId) {
        // Check if new program already highlighted
        if ($this->isProgramHighlighted($newProgramTahunId)) {
            return ['success' => false, 'message' => 'Program sudah di-highlight'];
        }
        
        $query = "UPDATE {$this->table} 
                  SET program_tahun_id = :program_id,
                      updated_at = CURRENT_TIMESTAMP 
                  WHERE id = :id";
        $this->db->query($query, [
            'id' => $highlightId,
            'program_id' => $newProgramTahunId
        ]);
        
        return ['success' => true, 'message' => 'Highlight program berhasil diganti'];
    }
    
    /**
     * Remove highlight
     */
    public function removeHighlight($highlightId) {
        return $this->db->delete($this->table, 'id = :id', ['id' => $highlightId]) > 0;
    }
    
    /**
     * Update highlight order
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
