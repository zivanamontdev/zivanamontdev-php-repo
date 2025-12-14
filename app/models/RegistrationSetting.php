<?php

class RegistrationSetting extends Model
{
    protected $table = 'registration_settings';
    
    /**
     * Get setting by key
     */
    public function get($key)
    {
        $sql = "SELECT setting_value FROM {$this->table} WHERE setting_key = :key LIMIT 1";
        $result = $this->db->fetchOne($sql, ['key' => $key]);
        return $result ? $result['setting_value'] : null;
    }
    
    /**
     * Set or update setting
     */
    public function set($key, $value)
    {
        $sql = "INSERT INTO {$this->table} (setting_key, setting_value) 
                VALUES (:key, :value) 
                ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)";
        
        $stmt = $this->db->query($sql, [
            'key' => $key,
            'value' => $value
        ]);
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Get all settings as key-value array
     */
    public function getAll()
    {
        $sql = "SELECT setting_key, setting_value FROM {$this->table}";
        $results = $this->db->fetchAll($sql);
        
        $settings = [];
        foreach ($results as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }
}
