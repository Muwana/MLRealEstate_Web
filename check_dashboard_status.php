<?php
echo "<h1>Dashboard Status Check</h1>";

$dashboard_files = [
    'dashboard/admin_dashboard.php',
    'dashboard/seller_dashboard.php', 
    'dashboard/user_dashboard.php'
];

foreach ($dashboard_files as $file) {
    echo "<h3>Checking: $file</h3>";
    
    if (!file_exists($file)) {
        echo "❌ File not found<br>";
        continue;
    }
    
    // Check file content
    $content = file_get_contents($file);
    
    // Check for session start
    if (strpos($content, 'session_start()') === false) {
        echo "❌ Missing session_start()<br>";
    } else {
        echo "✅ Has session_start()<br>";
    }
    
    // Check for user validation
    if (strpos($content, '$_SESSION[\'user\']') === false) {
        echo "❌ Missing user session check<br>";
    } else {
        echo "✅ Has user session check<br>";
    }
    
    // Check if it's test version
    if (strpos($content, 'TEST MODE') !== false) {
        echo "❌ Still in TEST MODE<br>";
    } else {
        echo "✅ Not in test mode<br>";
    }
    
    echo "<hr>";
}
?>