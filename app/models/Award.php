<?php
class Award extends Model {
    protected $table = 'awards';
    
    public function getAllOrdered() {
        return $this->all('display_order ASC, year_received DESC');
    }
}
