<?php
echo "<h1>Checking File Locations</h1>";

// Check main directory
echo "<h2>Main MLRealEstate folder:</h2>";
$main_files = scandir(__DIR__);
foreach ($main_files as $file) {
    if ($file != '.' && $file != '..') {
        echo "- $file<br>";
    }
}

// Check properties folder
echo "<h2>Properties folder:</h2>";
$properties_path = __DIR__ . '/properties';
if (file_exists($properties_path)) {
    $properties_files = scandir($properties_path);
    foreach ($properties_files as $file) {
        if ($file != '.' && $file != '..') {
            echo "- $file<br>";
        }
    }
} else {
    echo "Properties folder not found<br>";
}
?>