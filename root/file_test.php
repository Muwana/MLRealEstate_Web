<?php
echo "<h1>File Structure Test</h1>";
echo "Current directory: " . __DIR__ . "<br><br>";

// Test different possible locations
$test_paths = [
    'dashboard/admin_dashboard.php',
    'dashboard/includes/header.php',
    'admin_dashboard.php', 
    'seller_dashboard.php',
    'user_dashboard.php',
    'login.php',
    'signup.php'
];

foreach ($test_paths as $path) {
    $full_path = __DIR__ . '/' . $path;
    if (file_exists($full_path)) {
        echo "✅ FOUND: $path<br>";
    } else {
        echo "❌ MISSING: $path<br>";
    }
}
?>