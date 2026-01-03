<?php
/**
 * Prakata Model
 * Handles database operations for school introduction/foreword
 */
class Prakata extends Model {
    protected $table = 'prakata';
    
    /**
     * Get the single prakata record
     * Since there should only be one prakata, we get the first record
     */
    public function get() {
        $allRecords = $this->all('id ASC');
        $prakata = !empty($allRecords) ? $allRecords[0] : null;
        
        // If no prakata exists, return default data
        if (!$prakata) {
            return [
                'id' => null,
                'image' => '',
                'title' => 'Profil Singkat',
                'description' => "Sejak 2021, Zivana Montessori hadir sebagai sekolah inklusi yang memadukan metode Montessori Islami, Deep Learning, dan pendekatan Neurosensory. Anak reguler dan berkebutuhan khusus belajar bersama untuk mengasah fokus, emosi, dan kemandirian.\n\nDengan 6 area belajar dan motto CHAMPION (Cerdas, Berakhlak, Mandiri, Peduli, Berorientasi Islam), kami berkomitmen mencetak generasi yang percaya diri, cerdas, dan peduli sesama sesuai fitrah dan kecepatan belajarnya masing-masing.",
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        
        return $prakata;
    }
    
    /**
     * Update prakata data
     * If no record exists, creates a new one
     */
    public function updatePrakata($data) {
        $allRecords = $this->all('id ASC');
        $existing = !empty($allRecords) ? $allRecords[0] : null;
        
        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            return $this->create($data);
        }
    }
}
