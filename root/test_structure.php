<?php
echo "<h2>Current File Structure:</h2>";
echo "Root Directory: " . __DIR__ . "<br><br>";

$paths_to_check = [
    '/dashboard/admin_dashboard.php',
    '/dashboard/includes/header.php', 
    '/api/root/admin_dashboard.php',
    '/admin_dashboard.php'
];

foreach ($paths_to_check as $path) {
    $full_path = __DIR__ . $path;
    if (file_exists($full_path)) {
        echo "✅ FOUND: $path<br>";
    } else {
        echo "❌ MISSING: $path<br>";
    }
}
?>