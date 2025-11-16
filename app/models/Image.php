<?php
class Image extends Model {
    protected $table = 'images';
    
    public function getByProgram($programId) {
        return $this->where('program_id = :program_id', ['program_id' => $programId], 'display_order ASC');
    }
    
    public function deleteByProgram($programId) {
        $images = $this->getByProgram($programId);
        foreach ($images as $image) {
            delete_file($image['filename']);
        }
        return $this->db->delete($this->table, 'program_id = :program_id', ['program_id' => $programId]);
    }
}
