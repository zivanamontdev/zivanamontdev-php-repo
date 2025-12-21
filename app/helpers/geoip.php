<?php

/**
 * GeoIP Helper Class
 * Get location from IP address using free API (ip-api.com)
 */
class GeoIP {
    private static $cache = [];
    private static $cacheFile = null;
    
    /**
     * Initialize cache file path
     */
    private static function initCache() {
        if (self::$cacheFile === null) {
            self::$cacheFile = STORAGE_PATH . '/cache/geoip_cache.json';
            
            // Create cache directory if not exists
            $cacheDir = dirname(self::$cacheFile);
            if (!is_dir($cacheDir)) {
                mkdir($cacheDir, 0755, true);
            }
            
            // Load cache from file
            if (file_exists(self::$cacheFile)) {
                $content = file_get_contents(self::$cacheFile);
                self::$cache = json_decode($content, true) ?? [];
            }
        }
    }
    
    /**
     * Save cache to file
     */
    private static function saveCache() {
        self::initCache();
        file_put_contents(self::$cacheFile, json_encode(self::$cache));
    }
    
    /**
     * Get location from IP address
     * 
     * @param string $ip IP address
     * @return string Location (City name) or 'Unknown'
     */
    public static function getLocation($ip) {
        // Skip localhost/private IPs
        if (self::isPrivateIP($ip)) {
            return 'Local';
        }
        
        self::initCache();
        
        // Check cache first
        if (isset(self::$cache[$ip])) {
            return self::$cache[$ip];
        }
        
        // Try to get location from API
        $location = self::fetchFromAPI($ip);
        
        // Cache the result
        self::$cache[$ip] = $location;
        self::saveCache();
        
        return $location;
    }
    
    /**
     * Fetch location from ip-api.com
     * 
     * @param string $ip IP address
     * @return string Location or 'Unknown'
     */
    private static function fetchFromAPI($ip) {
        try {
            // Use ip-api.com (free, no API key required)
            // Limit: 45 requests per minute
            $url = "http://ip-api.com/json/{$ip}?fields=status,city,regionName,country";
            
            // Set timeout to avoid blocking
            $context = stream_context_create([
                'http' => [
                    'timeout' => 2, // 2 seconds timeout
                    'ignore_errors' => true
                ]
            ]);
            
            $response = @file_get_contents($url, false, $context);
            
            if ($response === false) {
                return 'Unknown';
            }
            
            $data = json_decode($response, true);
            
            if (!$data || $data['status'] !== 'success') {
                return 'Unknown';
            }
            
            // Return city if available, otherwise region, otherwise country
            if (!empty($data['city'])) {
                return $data['city'];
            } elseif (!empty($data['regionName'])) {
                return $data['regionName'];
            } elseif (!empty($data['country'])) {
                return $data['country'];
            }
            
            return 'Unknown';
            
        } catch (Exception $e) {
            error_log("GeoIP Error: " . $e->getMessage());
            return 'Unknown';
        }
    }
    
    /**
     * Check if IP is private/local
     * 
     * @param string $ip IP address
     * @return bool
     */
    private static function isPrivateIP($ip) {
        // Localhost
        if ($ip === '127.0.0.1' || $ip === '::1' || $ip === 'localhost') {
            return true;
        }
        
        // Private IP ranges
        $privateRanges = [
            '10.0.0.0' => '10.255.255.255',
            '172.16.0.0' => '172.31.255.255',
            '192.168.0.0' => '192.168.255.255',
        ];
        
        $ipLong = ip2long($ip);
        if ($ipLong === false) {
            return true; // Invalid IP
        }
        
        foreach ($privateRanges as $start => $end) {
            if ($ipLong >= ip2long($start) && $ipLong <= ip2long($end)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get client IP address (considering proxies)
     * 
     * @return string IP address
     */
    public static function getClientIP() {
        $ip = '';
        
        // Check for shared internet/ISP IP
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        // Check for IPs passing through proxies
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Can contain multiple IPs, get the first one
            $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = trim($ipList[0]);
        }
        // Check for remote address
        elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        // Validate IP
        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            $ip = '0.0.0.0';
        }
        
        return $ip;
    }
    
    /**
     * Clear cache (for maintenance)
     */
    public static function clearCache() {
        self::initCache();
        self::$cache = [];
        if (file_exists(self::$cacheFile)) {
            unlink(self::$cacheFile);
        }
    }
}
