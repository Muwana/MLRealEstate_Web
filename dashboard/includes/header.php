<?php
// This file should NOT start session or check authentication
// Those are handled in individual dashboard files

$user = $_SESSION['user'];
$pageTitle = $pageTitle ?? 'Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - ML RealEstate</title>
    
    <!-- Main website CSS -->
    <link rel="stylesheet" href="../style.css">
    
    <!-- Dashboard-specific CSS -->
    <link rel="stylesheet" href="assets/css/dashboard.css">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    <div class="dashboard-container"></div>