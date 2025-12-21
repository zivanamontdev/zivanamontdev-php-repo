<?php
/**
 * Password Reset Model
 * Handles password reset tokens
 */
class PasswordReset extends Model {
    protected $table = 'password_resets';
    
    /**
     * Create new password reset token
     */
    public function createToken($email, $userId, $token, $expiresAt) {
        return $this->create([
            'email' => $email,
            'user_id' => $userId,
            'token' => $token,
            'expires_at' => date('Y-m-d H:i:s', $expiresAt),
            'used' => 0
        ]);
    }
    
    /**
     * Find valid token
     */
    public function findValidToken($token) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE token = :token 
                AND used = 0 
                AND expires_at > NOW()
                LIMIT 1";
        
        return $this->db->fetchOne($sql, ['token' => $token]);
    }
    
    /**
     * Mark token as used
     */
    public function markAsUsed($token) {
        $sql = "UPDATE {$this->table} SET used = 1 WHERE token = :token";
        return $this->db->query($sql, ['token' => $token]);
    }
    
    /**
     * Delete expired tokens (cleanup)
     */
    public function deleteExpired() {
        $sql = "DELETE FROM {$this->table} WHERE expires_at < NOW()";
        return $this->db->query($sql);
    }
    
    /**
     * Delete all tokens for user (when password changed)
     */
    public function deleteByUserId($userId) {
        return $this->delete(['user_id' => $userId]);
    }
}
