<?php
class Analytics extends Model {
    protected $table = 'page_views';
    protected $primaryKey = 'id';
    
    /**
     * Get date condition based on period
     */
    private function getDateCondition($period) {
        switch ($period) {
            case 'day':
                return "DATE(NOW())";
            case 'week':
                return "DATE_SUB(NOW(), INTERVAL 7 DAY)";
            case 'month':
                return "DATE_SUB(NOW(), INTERVAL 1 MONTH)";
            case 'year':
                return "DATE_SUB(NOW(), INTERVAL 1 YEAR)";
            default:
                return "DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        }
    }
    
    /**
     * Track page view
     */
    public function trackPageView($pageUrl, $ipAddress, $userAgent, $location) {
        $db = Database::getInstance();
        
        try {
            $db->query("
                INSERT INTO page_views (page_url, ip_address, user_agent, location) 
                VALUES (?, ?, ?, ?)
            ", [$pageUrl, $ipAddress, $userAgent, $location]);
            return true;
        } catch (Exception $e) {
            error_log("Analytics tracking error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get popular pages
     */
    public function getPopularPages($period = 'month', $limit = 5) {
        $db = Database::getInstance();
        $dateCondition = $this->getDateCondition($period);
        
        $result = $db->query("
            SELECT 
                page_url as page,
                COUNT(*) as views
            FROM page_views
            WHERE visited_at >= {$dateCondition}
            GROUP BY page_url
            ORDER BY views DESC
            LIMIT ?
        ", [$limit]);
        
        return $result->fetchAll();
    }
    
    /**
     * Get top locations
     */
    public function getTopLocations($period = 'month', $limit = 5) {
        $db = Database::getInstance();
        $dateCondition = $this->getDateCondition($period);
        
        $result = $db->query("
            SELECT 
                location,
                COUNT(*) as views
            FROM page_views
            WHERE visited_at >= {$dateCondition}
                AND location IS NOT NULL
                AND location != 'Unknown'
                AND location != ''
            GROUP BY location
            ORDER BY views DESC
            LIMIT ?
        ", [$limit]);
        
        return $result->fetchAll();
    }
    
    /**
     * Get hourly views (grouped by 2-hour intervals)
     */
    public function getHourlyViews($period = 'day') {
        $db = Database::getInstance();
        $dateCondition = $this->getDateCondition($period);
        
        $query = "
            SELECT 
                CASE 
                    WHEN HOUR(visited_at) BETWEEN 0 AND 1 THEN '00-02'
                    WHEN HOUR(visited_at) BETWEEN 2 AND 3 THEN '02-04'
                    WHEN HOUR(visited_at) BETWEEN 4 AND 5 THEN '04-06'
                    WHEN HOUR(visited_at) BETWEEN 6 AND 7 THEN '06-08'
                    WHEN HOUR(visited_at) BETWEEN 8 AND 9 THEN '08-10'
                    WHEN HOUR(visited_at) BETWEEN 10 AND 11 THEN '10-12'
                    WHEN HOUR(visited_at) BETWEEN 12 AND 13 THEN '12-14'
                    WHEN HOUR(visited_at) BETWEEN 14 AND 15 THEN '14-16'
                    WHEN HOUR(visited_at) BETWEEN 16 AND 17 THEN '16-18'
                    WHEN HOUR(visited_at) BETWEEN 18 AND 19 THEN '18-20'
                    WHEN HOUR(visited_at) BETWEEN 20 AND 21 THEN '20-22'
                    ELSE '22-24'
                END as hour,
                COUNT(*) as views
            FROM page_views
            WHERE visited_at >= {$dateCondition}
            GROUP BY hour
            ORDER BY 
                CASE hour
                    WHEN '00-02' THEN 1
                    WHEN '02-04' THEN 2
                    WHEN '04-06' THEN 3
                    WHEN '06-08' THEN 4
                    WHEN '08-10' THEN 5
                    WHEN '10-12' THEN 6
                    WHEN '12-14' THEN 7
                    WHEN '14-16' THEN 8
                    WHEN '16-18' THEN 9
                    WHEN '18-20' THEN 10
                    WHEN '20-22' THEN 11
                    WHEN '22-24' THEN 12
                END
        ";
        
        $result = $db->query($query)->fetchAll();
        
        // Ensure all 12 time slots exist (fill missing with 0)
        $allSlots = ['00-02', '02-04', '04-06', '06-08', '08-10', '10-12', '12-14', '14-16', '16-18', '18-20', '20-22', '22-24'];
        $hourlyData = [];
        
        foreach ($allSlots as $slot) {
            $found = false;
            foreach ($result as $row) {
                if ($row['hour'] === $slot) {
                    $hourlyData[] = $row;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $hourlyData[] = ['hour' => $slot, 'views' => 0];
            }
        }
        
        return $hourlyData;
    }
    
    /**
     * Get total views for period
     */
    public function getTotalViews($period = 'month') {
        $db = Database::getInstance();
        $dateCondition = $this->getDateCondition($period);
        
        $result = $db->query("
            SELECT COUNT(*) as total
            FROM page_views
            WHERE visited_at >= {$dateCondition}
        ")->fetch();
        
        return $result['total'] ?? 0;
    }
    
    /**
     * Get unique visitors for period
     */
    public function getUniqueVisitors($period = 'month') {
        $db = Database::getInstance();
        $dateCondition = $this->getDateCondition($period);
        
        $result = $db->query("
            SELECT COUNT(DISTINCT ip_address) as total
            FROM page_views
            WHERE visited_at >= {$dateCondition}
        ")->fetch();
        
        return $result['total'] ?? 0;
    }
}
