<?php
class FormField extends Model {
    protected $table = 'form_fields';
    
    public function getActive() {
        return $this->where('is_active = 1', [], 'display_order ASC');
    }
}
