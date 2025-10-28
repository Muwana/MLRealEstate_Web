<?php
session_start();
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
        "message" => "Database connection failed: " . $conn->connect_error
    ]);
    exit();
}

// Get POST data
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Check if data was received
    if (empty($input)) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "No data received in request body"
        ]);
        exit();
    }
    
    // Check if JSON decoding worked
    if ($data === null) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Invalid JSON data received"
        ]);
        exit();
    }

    // Check if all required fields are present
    $requiredFields = ['fullName', 'email', 'password', 'userType'];
    $missingFields = [];
    
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            $missingFields[] = $field;
        }
    }
    
    if (!empty($missingFields)) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Missing required fields: " . implode(', ', $missingFields)
        ]);
        exit();
    }

    $fullName = trim($data['fullName']);
    $email = trim($data['email']);
    $password = $data['password'];
    $userType = trim($data['userType']);
    $confirmPassword = $data['confirmPassword'] ?? '';

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Invalid email format: " . $email
        ]);
        exit();
    }

    // Validate password length
    if (strlen($password) < 6) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Password must be at least 6 characters long"
        ]);
        exit();
    }

    // Validate confirm password
    if (!empty($confirmPassword) && $password !== $confirmPassword) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Password and confirm password do not match"
        ]);
        exit();
    }

    // Validate user type against your database enum values
    $validUserTypes = ['user', 'seller', 'admin'];
    if (!in_array(strtolower($userType), $validUserTypes)) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Invalid user type. Must be one of: " . implode(', ', $validUserTypes)
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
            "message" => "Email already exists. Please use a different email address."
        ]);
        $checkEmail->close();
        exit();
    }
    $checkEmail->close();

    // ✅ PASSWORD HASHING - This is already correct in your code
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Generate unique user ID
    $userId = "user_" . uniqid() . "_" . time();

    // Insert user into database with hashed password
    $stmt = $conn->prepare("INSERT INTO users (user_id, full_name, email, password, user_type, status) VALUES (?, ?, ?, ?, ?, 'active')");
    $stmt->bind_param("sssss", $userId, $fullName, $email, $hashedPassword, $userType);

    if ($stmt->execute()) {
        // Get the inserted user data
        $getUser = $conn->prepare("SELECT user_id, full_name, email, user_type, created_at FROM users WHERE user_id = ?");
        $getUser->bind_param("s", $userId);
        $getUser->execute();
        $result = $getUser->get_result();
        $user = $result->fetch_assoc();
        
        // Store user data in session
        $_SESSION['user'] = [
            "userId" => $user['user_id'],
            "fullName" => $user['full_name'],
            "email" => $user['email'],
            "userType" => $user['user_type'],
            "createdAt" => $user['created_at']
        ];

        // Generate token
        $tokenData = [
            'user_id' => $userId,
            'email' => $email,
            'user_type' => $userType,
            'timestamp' => time(),
            'expires' => time() + (24 * 60 * 60)
        ];
        
        $payload = base64_encode(json_encode($tokenData));
        $signature = hash_hmac('sha256', $payload, 'ml_realestate_secret_2024');
        $token = $payload . '.' . $signature;

        // Determine dashboard URL based on user type
        $dashboardUrl = getDashboardUrl($userType);

        http_response_code(201);
        echo json_encode([
            "success" => true,
            "message" => "Registration successful",
            "token" => $token,
            "tokenExpires" => $tokenData['expires'],
            "dashboardUrl" => $dashboardUrl,
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
            "message" => "Registration failed: " . $stmt->error
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

// Function to get dashboard URL based on user type
function getDashboardUrl($userType) {
    switch (strtolower($userType)) {
        case 'admin':
            return 'dashboard/admin_dashboard.php';
        case 'seller':
            return 'dashboard/seller_dashboard.php';
        case 'user':
        default:
            return 'MLRealEstate/dashboard/user_dashboard.php';
    }
}

$conn->close();
?>