<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "muwana_legacy";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed: " . $conn->connect_error,
        "error_code" => "DB_CONNECTION_FAILED"
    ]);
    exit();
}

// Get POST data
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Log the received data for debugging
error_log("Received registration data: " . print_r($data, true));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if all required fields are present
    if (!isset($data['fullName']) || !isset($data['email']) || !isset($data['password']) || !isset($data['userType'])) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "All fields are required: fullName, email, password, userType",
            "error_code" => "MISSING_FIELDS",
            "received_data" => $data
        ]);
        exit();
    }

    $fullName = trim($data['fullName']);
    $email = trim($data['email']);
    $password = $data['password'];
    $userType = trim($data['userType']);

    // Validate input
    if (empty($fullName) || empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "All fields are required and cannot be empty"
        ]);
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Invalid email format"
        ]);
        exit();
    }

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        http_response_code(409);
        echo json_encode([
            "success" => false,
            "message" => "Email already exists. Please use a different email address.",
            "error_code" => "EMAIL_EXISTS"
        ]);
        $checkEmail->close();
        exit();
    }
    $checkEmail->close();

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Generate unique user ID
    $userId = "user_" . uniqid() . "_" . time();

    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (user_id, full_name, email, password, user_type) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $userId, $fullName, $email, $hashedPassword, $userType);

    if ($stmt->execute()) {
        // Get the inserted user data
        $getUser = $conn->prepare("SELECT user_id, full_name, email, user_type, created_at FROM users WHERE user_id = ?");
        $getUser->bind_param("s", $userId);
        $getUser->execute();
        $result = $getUser->get_result();
        $user = $result->fetch_assoc();
        
        // Generate a simple token
        $tokenData = [
            'user_id' => $userId,
            'email' => $email,
            'timestamp' => time()
        ];
        $token = base64_encode(json_encode($tokenData));

        http_response_code(201);
        echo json_encode([
            "success" => true,
            "message" => "Registration successful",
            "token" => $token,
            "user" => [
                "userId" => $user['user_id'],
                "fullName" => $user['full_name'],
                "email" => $user['email'],
                "userType" => $user['user_type'],
                "createdAt" => $user['created_at']
            ]
        ]);
        
        $getUser->close();
    } else {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Registration failed: " . $stmt->error,
            "error_code" => "INSERT_FAILED",
            "mysql_error" => $stmt->error
        ]);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Method not allowed. Only POST requests are accepted."
    ]);
}

$conn->close();
?>