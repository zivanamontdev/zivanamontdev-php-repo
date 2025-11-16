<?php
class Schedule extends Model {
    protected $table = 'schedules';
    
    public function getAllOrdered() {
        return $this->all('display_order ASC, time_start ASC');
    }
}
