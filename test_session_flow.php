<?php
session_start();
echo "<h1>Session Flow Test</h1>";

// Test 1: Check current session
echo "<h2>1. Current Session Status:</h2>";
echo "Session ID: " . session_id() . "<br>";
echo "Session User: " . ($_SESSION['user']['email'] ?? 'Not set') . "<br>";

// Test 2: Simulate login
echo "<h2>2. Simulating Login...</h2>";
$_SESSION['user'] = [
    'userId' => 'test_user_123',
    'fullName' => 'Test User',
    'email' => 'test@example.com',
    'userType' => 'user',
    'createdAt' => date('Y-m-d H:i:s')
];

echo "Session set successfully!<br>";

// Test 3: Test redirect
echo "<h2>3. Testing Redirect...</h2>";
echo '<a href="dashboard/user_dashboard.php">Test User Dashboard Access</a><br>';
echo '<a href="test_manual_redirect.php">Test Manual Redirect</a>';

// Store session data for next test
$_SESSION['test_data'] = 'Session is working';
?>