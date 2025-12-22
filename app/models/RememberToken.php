<?php
/**
 * Remember Token Model
 * Handles "Remember Me" authentication tokens
 */
class RememberToken extends Model {
    protected $table = 'remember_tokens';
    
    /**
     * Create new remember token
     */
    public function createToken($userId, $token, $expiresAt) {
        return $this->create([
            'user_id' => $userId,
            'token' => $token,
            'expires_at' => date('Y-m-d H:i:s', $expiresAt)
        ]);
    }
    
    /**
     * Find valid token
     */
    public function findValidToken($token) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE token = :token 
                AND expires_at > NOW()
                LIMIT 1";
        
        return $this->db->fetchOne($sql, ['token' => $token]);
    }
    
    /**
     * Delete token
     */
    public function deleteToken($token) {
        $sql = "DELETE FROM {$this->table} WHERE token = :token";
        return $this->db->query($sql, ['token' => $token]);
    }
    
    /**
     * Delete all tokens for user
     */
    public function deleteByUserId($userId) {
        return $this->delete(['user_id' => $userId]);
    }
    
    /**
     * Delete expired tokens (cleanup)
     */
    public function deleteExpired() {
        $sql = "DELETE FROM {$this->table} WHERE expires_at < NOW()";
        return $this->db->query($sql);
    }
}
