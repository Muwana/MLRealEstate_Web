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

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Check if data was received
    if (empty($input)) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "No data received"
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

    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    // Validate input
    if (empty($email) || empty($password)) {
        echo json_encode([
            "success" => false,
            "message" => "Email and password are required"
        ]);
        exit();
    }

    // Check if user exists
    $stmt = $conn->prepare("SELECT user_id, full_name, email, password, user_type, created_at FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode([
            "success" => false,
            "message" => "Invalid email or password"
        ]);
        $stmt->close();
        exit();
    }

    $user = $result->fetch_assoc();
    $stmt->close();

    // Verify password
    if (password_verify($password, $user['password'])) {
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
            'user_id' => $user['user_id'],
            'email' => $user['email'],
            'user_type' => $user['user_type'],
            'timestamp' => time(),
            'expires' => time() + (24 * 60 * 60)
        ];
        
        $payload = base64_encode(json_encode($tokenData));
        $signature = hash_hmac('sha256', $payload, 'ml_realestate_secret_2024');
        $token = $payload . '.' . $signature;

        // Determine dashboard URL based on user type
        $dashboardUrl = getDashboardUrl($user['user_type']);

        echo json_encode([
            "success" => true,
            "message" => "Login successful",
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
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Invalid email or password"
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method. Only POST allowed."
    ]);
}

// Function to get dashboard URL based on user type
function getDashboardUrl($userType) {
    switch (strtolower($userType)) {
        case 'admin':
        case 'agent':
            return 'dashboard/admin_dashboard.php';
        case 'seller':
        case 'owner':
            return 'dashboard/seller_dashboard.php';
        case 'user':
        case 'buyer':
        default:
            return 'dashboard/user_dashboard.php';
    }
}

$conn->close();
?>