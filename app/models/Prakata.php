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
                'title' => 'Sejarah Singkat/Prakata Sekolah',
                'description' => 'TK Zivana Montessori didirikan pada tahun 2021 di bawah naungan Yayasan Zivana Insan Mandiri. TK Zivana Montessori  merupakan salah satu satuan pendidikan non formal yang terletak di daerah perkotaan. Lokasi satuan pendidikan agak jauh dari jalan raya, sehingga satuan pendidikan dan masyarakat sekitar aman dan tidak terganggu oleh hiruk pikuk dan kebisingan lalu lintas di perkotaan. Tokoh yang paling berjasa dalam lahirnya TK Zivana Montessori yakni Ibu Adilah Wina Fitria.',
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
