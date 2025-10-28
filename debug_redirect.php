<?php
session_start();

// Simulate a successful login response
$test_user = [
    'success' => true,
    'message' => 'Debug redirect test',
    'dashboardUrl' => 'dashboard/user_dashboard.php',
    'user' => [
        'userId' => 'debug_user_123',
        'fullName' => 'Debug User',
        'email' => 'debug@example.com',
        'userType' => 'user'
    ]
];

echo "<h1>Debug Redirect Test</h1>";
echo "<pre>" . json_encode($test_user, JSON_PRETTY_PRINT) . "</pre>";

echo "<h2>Test Links:</h2>";
echo '<button onclick="testRedirect()">Test JavaScript Redirect</button>';
echo '<br><br>';
echo '<a href="dashboard/user_dashboard.php">Direct Dashboard Link</a>';

echo "
<script>
function testRedirect() {
    // Simulate what your JavaScript should do
    const dashboardUrl = 'dashboard/user_dashboard.php';
    console.log('Redirecting to:', dashboardUrl);
    window.location.href = dashboardUrl;
}
</script>
";
?>