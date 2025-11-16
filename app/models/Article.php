<?php
class Article extends Model {
    protected $table = 'articles';
    
    public function getPublished($limit = null) {
        return $this->where('status = :status AND published_at <= NOW()', 
            ['status' => 'published'], 
            'published_at DESC', 
            $limit
        );
    }
    
    public function getFeatured($limit = 3) {
        return $this->where('status = :status AND is_featured = 1 AND published_at <= NOW()', 
            ['status' => 'published'], 
            'published_at DESC', 
            $limit
        );
    }
    
    public function getBySlug($slug) {
        return $this->whereOne('slug = :slug', ['slug' => $slug]);
    }
    
    public function incrementViews($id) {
        $sql = "UPDATE {$this->table} SET views = views + 1 WHERE id = :id";
        return $this->db->query($sql, ['id' => $id]);
    }
    
    public function generateUniqueSlug($title, $id = null) {
        $slug = slugify($title);
        $originalSlug = $slug;
        $counter = 1;
        
        while (true) {
            $condition = 'slug = :slug';
            $params = ['slug' => $slug];
            
            if ($id) {
                $condition .= ' AND id != :id';
                $params['id'] = $id;
            }
            
            if (!$this->exists($condition, $params)) {
                break;
            }
            
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}
