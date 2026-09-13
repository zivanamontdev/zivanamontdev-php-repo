<?php
/** Small resumable batches; at most one external lookup per HTTP request. */
class LocationCacheWarmer {
    private $db;
    private $lookup;
    private $retryAfter;

    public function __construct($db, $lookup = null, $retryAfter = null) {
        $this->db = $db;
        $this->lookup = $lookup ?? [GeoIP::class, 'getDetails'];
        $this->retryAfter = $retryAfter ?? [GeoIP::class, 'getLookupRetryAfter'];
    }

    public function batch($cursor = '') {
        $total = (int)$this->db->fetchOne('SELECT COUNT(DISTINCT ip_address) AS total FROM page_views')['total'];
        $rows = $this->db->fetchAll(
            'SELECT DISTINCT ip_address FROM page_views WHERE ip_address > :cursor ORDER BY ip_address LIMIT 25',
            ['cursor' => $cursor]
        );
        $result = ['cursor' => $cursor, 'total' => $total, 'checked' => 0, 'resolved' => 0,
            'unresolved' => 0, 'done' => false, 'retry_after' => 0];
        foreach ($rows as $row) {
            $ip = $row['ip_address'];
            $queried = false;
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                $details = call_user_func($this->lookup, $ip, false);
                if (empty($details['countryCode'])) {
                    $queried = true;
                    $details = call_user_func($this->lookup, $ip, true);
                    $wait = (int)call_user_func($this->retryAfter);
                    if (empty($details['countryCode']) && $wait > 0) {
                        $result['retry_after'] = max(1, $wait);
                        return $result; // Keep the cursor before this IP so it is retried.
                    }
                }
                $result[empty($details['countryCode']) ? 'unresolved' : 'resolved']++;
            }
            $result['cursor'] = $ip;
            $result['checked']++;
            if ($queried) {
                $result['retry_after'] = 2;
                return $result;
            }
        }
        $result['done'] = count($rows) < 25;
        return $result;
    }
}
