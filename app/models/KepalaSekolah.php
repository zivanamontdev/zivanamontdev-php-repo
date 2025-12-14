<?php
/**
 * KepalaSekolah Model
 * Handles database operations for school principal
 */
class KepalaSekolah extends Model {
    protected $table = 'kepala_sekolah';
    
    /**
     * Get the principal record
     * Since there should only be one principal, we get the first record
     */
    public function get() {
        $allRecords = $this->all('id ASC');
        $principal = !empty($allRecords) ? $allRecords[0] : null;
        
        // If no principal exists, return default data
        if (!$principal) {
            return [
                'id' => null,
                'name' => 'Belum ada data',
                'photo' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        
        return $principal;
    }
    
    /**
     * Update principal data
     * If no record exists, creates a new one
     */
    public function updatePrincipal($data) {
        $allRecords = $this->all('id ASC');
        $existing = !empty($allRecords) ? $allRecords[0] : null;
        
        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            return $this->create($data);
        }
    }
}
