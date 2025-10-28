<?php
echo "<h1>Dashboard Folder Contents</h1>";
$dashboard_path = __DIR__ . '/dashboard';
if (file_exists($dashboard_path)) {
    $dashboard_files = scandir($dashboard_path);
    foreach ($dashboard_files as $file) {
        if ($file != '.' && $file != '..') {
            $full_path = $dashboard_path . '/' . $file;
            if (is_dir($full_path)) {
                echo "<strong>📁 Folder: $file</strong><br>";
                // Show contents of subfolders
                $sub_files = scandir($full_path);
                foreach ($sub_files as $sub_file) {
                    if ($sub_file != '.' && $sub_file != '..') {
                        echo "&nbsp;&nbsp;- $sub_file<br>";
                    }
                }
            } else {
                echo "📄 File: $file<br>";
            }
        }
    }
} else {
    echo "Dashboard folder not found!<br>";
}
?>