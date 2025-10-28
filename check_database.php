<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "muwana_legacy";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if users table exists
$result = $conn->query("SHOW TABLES LIKE 'users'");
if ($result->num_rows > 0) {
    echo "✅ Users table exists<br>";
    
    // Show table structure
    $columns = $conn->query("DESCRIBE users");
    echo "Table structure:<br>";
    while ($col = $columns->fetch_assoc()) {
        echo "- {$col['Field']} ({$col['Type']})<br>";
    }
    
    // Show sample users
    $users = $conn->query("SELECT user_id, email, user_type FROM users LIMIT 5");
    echo "<br>Sample users:<br>";
    while ($user = $users->fetch_assoc()) {
        echo "- {$user['email']} ({$user['user_type']})<br>";
    }
} else {
    echo "❌ Users table does not exist";
}

$conn->close();
?>