<?php
class SocialMedia extends Model {
    protected $table = 'social_media';
    
    public function getActive() {
        return $this->where('is_active = 1', [], 'display_order ASC');
    }
}
