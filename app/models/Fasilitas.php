<?php
/**
 * Fasilitas Model
 * Handles database operations for fasilitas table
 */

class Fasilitas extends Model {
    protected $table = 'fasilitas';
    
    /**
     * Get all fasilitas with gallery count
     */
    public function getAllWithGalleryCount() {
        $sql = "SELECT f.*, COUNT(fg.id) as gallery_count 
                FROM {$this->table} f
                LEFT JOIN fasilitas_gallery fg ON f.id = fg.fasilitas_id
                GROUP BY f.id
                ORDER BY f.created_at DESC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get fasilitas by ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    /**
     * Create new fasilitas
     */
    public function createFasilitas($data) {
        return $this->create($data);
    }
    
    /**
     * Update fasilitas
     */
    public function updateFasilitas($id, $data) {
        return $this->update($id, $data);
    }
    
    /**
     * Delete fasilitas
     */
    public function deleteFasilitas($id) {
        return $this->delete($id);
    }
    
    /**
     * Get gallery images for a fasilitas
     */
    public function getGalleryImages($fasilitasId) {
        $sql = "SELECT * FROM fasilitas_gallery WHERE fasilitas_id = :fasilitas_id ORDER BY created_at ASC";
        return $this->db->fetchAll($sql, ['fasilitas_id' => $fasilitasId]);
    }
    
    /**
     * Add gallery image with description and is_cover
     */
    public function addGalleryImage($fasilitasId, $imagePath, $description = '', $isCover = 0) {
        $sql = "INSERT INTO fasilitas_gallery (fasilitas_id, image_path, description, is_cover) 
                VALUES (:fasilitas_id, :image_path, :description, :is_cover)";
        return $this->db->query($sql, [
            'fasilitas_id' => $fasilitasId,
            'image_path' => $imagePath,
            'description' => $description,
            'is_cover' => $isCover
        ]);
    }
    
    /**
     * Update gallery image
     */
    public function updateGalleryImage($imageId, $data) {
        $fields = [];
        $params = ['id' => $imageId];
        
        if (isset($data['image_path'])) {
            $fields[] = 'image_path = :image_path';
            $params['image_path'] = $data['image_path'];
        }
        if (isset($data['description'])) {
            $fields[] = 'description = :description';
            $params['description'] = $data['description'];
        }
        if (isset($data['is_cover'])) {
            $fields[] = 'is_cover = :is_cover';
            $params['is_cover'] = $data['is_cover'];
        }
        
        if (empty($fields)) {
            return true;
        }
        
        $sql = "UPDATE fasilitas_gallery SET " . implode(', ', $fields) . " WHERE id = :id";
        return $this->db->query($sql, $params);
    }
    
    /**
     * Clear all is_cover flags for a fasilitas
     */
    public function clearCoverFlags($fasilitasId) {
        $sql = "UPDATE fasilitas_gallery SET is_cover = 0 WHERE fasilitas_id = :fasilitas_id";
        return $this->db->query($sql, ['fasilitas_id' => $fasilitasId]);
    }
    
    /**
     * Get gallery image by ID
     */
    public function getGalleryImageById($imageId) {
        $sql = "SELECT * FROM fasilitas_gallery WHERE id = :id";
        return $this->db->fetchOne($sql, ['id' => $imageId]);
    }
    
    /**
     * Delete gallery image
     */
    public function deleteGalleryImage($imageId) {
        $sql = "DELETE FROM fasilitas_gallery WHERE id = :id";
        return $this->db->query($sql, ['id' => $imageId]);
    }
}

