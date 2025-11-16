<?php
class User extends Model {
    protected $table = 'users';
    
    public function authenticate($username, $password) {
        $user = $this->whereOne('username = :username OR email = :email', [
            'username' => $username,
            'email' => $username
        ]);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
    
    public function updateLastLogin($id) {
        return $this->update($id, ['last_login' => date('Y-m-d H:i:s')]);
    }
}
