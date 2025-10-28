<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $email;
    public $password_hash;
    public $full_name;
    public $phone_number;
    public $user_type;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Check if email exists
    public function emailExists() {
        $query = "SELECT id, full_name, password_hash, user_type 
                  FROM " . $this->table_name . " 
                  WHERE email = ? 
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->full_name = $row['full_name'];
            $this->password_hash = $row['password_hash'];
            $this->user_type = $row['user_type'];
            return true;
        }
        return false;
    }

    // Create new user
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET email=:email, password_hash=:password_hash, 
                    full_name=:full_name, phone_number=:phone_number, 
                    user_type=:user_type";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->phone_number = htmlspecialchars(strip_tags($this->phone_number));
        $this->user_type = htmlspecialchars(strip_tags($this->user_type));

        // Bind parameters
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password_hash", $this->password_hash);
        $stmt->bindParam(":full_name", $this->full_name);
        $stmt->bindParam(":phone_number", $this->phone_number);
        $stmt->bindParam(":user_type", $this->user_type);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            
            // Create user profile
            $profile_query = "INSERT INTO user_profiles (user_id) VALUES (?)";
            $profile_stmt = $this->conn->prepare($profile_query);
            $profile_stmt->execute([$this->id]);
            
            return true;
        }
        return false;
    }

    // Get user by ID
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $this->email = $row['email'];
            $this->full_name = $row['full_name'];
            $this->phone_number = $row['phone_number'];
            $this->user_type = $row['user_type'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }

    // Update user profile
    public function updateProfile($full_name, $phone_number) {
        $query = "UPDATE " . $this->table_name . " 
                  SET full_name = :full_name, phone_number = :phone_number 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize inputs
        $full_name = htmlspecialchars(strip_tags($full_name));
        $phone_number = htmlspecialchars(strip_tags($phone_number));
        
        // Bind parameters
        $stmt->bindParam(":full_name", $full_name);
        $stmt->bindParam(":phone_number", $phone_number);
        $stmt->bindParam(":id", $this->id);
        
        return $stmt->execute();
    }

    // Change password
    public function changePassword($new_password_hash) {
        $query = "UPDATE " . $this->table_name . " 
                  SET password_hash = :password_hash 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":password_hash", $new_password_hash);
        $stmt->bindParam(":id", $this->id);
        
        return $stmt->execute();
    }

    // Get all users (for admin)
    public function readAll() {
        $query = "SELECT id, email, full_name, phone_number, user_type, created_at 
                  FROM " . $this->table_name . " 
                  ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // Search users (for admin)
    public function search($keywords) {
        $query = "SELECT id, email, full_name, phone_number, user_type, created_at 
                  FROM " . $this->table_name . " 
                  WHERE full_name LIKE ? OR email LIKE ? 
                  ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        
        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
        
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->execute();
        
        return $stmt;
    }

    // Delete user (for admin)
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        return $stmt->execute();
    }

    // Check if user is admin
    public function isAdmin() {
        return $this->user_type === 'admin';
    }

    // Check if user is seller or agent
    public function canListProperties() {
        return in_array($this->user_type, ['seller', 'agent', 'admin']);
    }
}
?>