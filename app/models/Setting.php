<?php
class Setting extends Model {
    protected $table = 'settings';
    
    public function get($key, $default = null) {
        $setting = $this->whereOne('setting_key = :key', ['key' => $key]);
        return $setting ? $setting['setting_value'] : $default;
    }
    
    public function set($key, $value) {
        $existing = $this->whereOne('setting_key = :key', ['key' => $key]);
        
        if ($existing) {
            return $this->db->update($this->table, 
                ['setting_value' => $value], 
                'setting_key = :key', 
                ['key' => $key]
            );
        } else {
            return $this->create([
                'setting_key' => $key,
                'setting_value' => $value
            ]);
        }
    }
    
    public function getByGroup($group) {
        return $this->where('setting_group = :group', ['group' => $group], 'id ASC');
    }
    
    public function getAllGrouped() {
        $settings = $this->all('setting_group ASC, id ASC');
        $grouped = [];
        
        foreach ($settings as $setting) {
            $group = $setting['setting_group'];
            if (!isset($grouped[$group])) {
                $grouped[$group] = [];
            }
            $grouped[$group][] = $setting;
        }
        
        return $grouped;
    }
}
