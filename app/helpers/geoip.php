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
        // Merge while holding the lock so parallel visits do not erase each other's metadata.
        $handle = fopen(self::$cacheFile, 'c+');
        if (!$handle) return;
        if (flock($handle, LOCK_EX)) {
            $stored = json_decode(stream_get_contents($handle), true) ?: [];
            $merged = array_replace($stored, self::$cache);
            ftruncate($handle, 0);
            rewind($handle);
            fwrite($handle, json_encode($merged));
            fflush($handle);
            flock($handle, LOCK_UN);
        }
        fclose($handle);
    }
    
    /**
     * Get location from IP address
     * 
     * @param string $ip IP address
     * @return string Location (City name) or 'Unknown'
     */
    public static function getLocation($ip) {
        $details = self::getDetails($ip);
        return $details['city'] ?: ($details['regionName'] ?: ($details['country'] ?: 'Unknown'));
    }

    /** Country and hosting metadata are kept in the existing cache, not guessed from city names. */
    public static function getDetails($ip, $lookup = true) {
        $unknown = ['city' => '', 'regionName' => '', 'country' => '', 'countryCode' => '', 'hosting' => null];
        if (self::isPrivateIP($ip)) return $unknown;
        self::initCache();
        $cached = self::$cache[$ip] ?? null;
        // Old cache entries are strings and cannot establish the country or hosting status.
        if (is_array($cached) && isset($cached['countryCode'], $cached['hosting'])) return array_merge($unknown, $cached);
        if (!$lookup) return $unknown;
        $details = self::fetchFromAPI($ip);
        if ($details !== null) {
            self::$cache[$ip] = $details;
            self::saveCache();
            return array_merge($unknown, $details);
        }
        return $unknown;
    }

    public static function isIndonesianVisitor($details, $userAgent) {
        return ($details['countryCode'] ?? '') === 'ID'
            && ($details['hosting'] ?? null) === false
            && trim($details['city'] ?? '') !== ''
            && !self::isAutomatedVisitor($userAgent);
    }

    public static function isAutomatedVisitor($userAgent) {
        return trim($userAgent) === '' || (bool)preg_match(
            '/bot|crawler|spider|slurp|preview|headless|lighthouse|pagespeed|pingdom|uptime|monitor|curl|wget|python|scrapy|httpclient|go-http-client|facebookexternalhit/i',
            $userAgent
        );
    }

    private static function fetchFromAPI($ip) {
        // Share the provider rate-limit window across requests and the cache warm-up command.
        $retryAfter = self::$cache['_retry_after'] ?? 0;
        if ($retryAfter > time()) return null;
        $url = "http://ip-api.com/json/" . rawurlencode($ip) . "?fields=status,city,regionName,country,countryCode,hosting";
        $context = stream_context_create(['http' => ['timeout' => 2, 'ignore_errors' => true]]);
        $response = @file_get_contents($url, false, $context);
        $headers = $http_response_header ?? [];
        $remaining = null;
        $ttl = 60;
        foreach ($headers as $header) {
            if (preg_match('/^X-Rl:\s*(\d+)/i', $header, $match)) $remaining = (int)$match[1];
            if (preg_match('/^X-Ttl:\s*(\d+)/i', $header, $match)) $ttl = (int)$match[1];
        }
        if ($remaining === 0 || preg_match('/\s429\s/', $headers[0] ?? '')) {
            self::$cache['_retry_after'] = time() + max(1, $ttl);
            self::saveCache();
        }
        $data = $response === false ? null : json_decode($response, true);
        if (!is_array($data) || ($data['status'] ?? '') !== 'success'
            || !isset($data['countryCode']) || !is_bool($data['hosting'] ?? null)) return null;
        return $data;
    }

    private static function isPrivateIP($ip) {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
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
