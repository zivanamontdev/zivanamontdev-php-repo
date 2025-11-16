<?php
class Analytics extends Model {
    protected $table = 'analytics';
    protected $primaryKey = 'id';
    
    public function getTotalVisits($days = 30) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE visited_at >= DATE_SUB(NOW(), INTERVAL :days DAY)";
        $result = $this->db->fetchOne($sql, ['days' => $days]);
        return $result['count'] ?? 0;
    }
    
    public function getVisitsByDate($days = 7) {
        $sql = "SELECT DATE(visited_at) as date, COUNT(*) as count 
                FROM {$this->table} 
                WHERE visited_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY DATE(visited_at)
                ORDER BY date ASC";
        return $this->db->fetchAll($sql, ['days' => $days]);
    }
    
    public function getTopPages($limit = 10) {
        $sql = "SELECT page_url, COUNT(*) as count 
                FROM {$this->table} 
                WHERE visited_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY page_url
                ORDER BY count DESC
                LIMIT :limit";
        return $this->db->fetchAll($sql, ['limit' => $limit]);
    }
    
    public function getDeviceStats() {
        $sql = "SELECT device_type, COUNT(*) as count 
                FROM {$this->table} 
                WHERE visited_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY device_type
                ORDER BY count DESC";
        return $this->db->fetchAll($sql);
    }
}
