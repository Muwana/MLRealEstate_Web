<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "muwana_legacy";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Reset password for john.doe@example.com
$new_password = password_hash('123456', PASSWORD_DEFAULT);
$email = 'john.doe@example.com';

$stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
$stmt->bind_param("ss", $new_password, $email);

if ($stmt->execute()) {
    echo "✅ Password reset for $email to '123456'";
} else {
    echo "❌ Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>