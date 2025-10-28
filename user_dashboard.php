<?php
session_start();
// Check if user is logged in and is a user/buyer
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['userType'], ['user', 'buyer'])) {
    header('Location: ../login.php');
    exit();
}

$user = $_SESSION['user'];
$pageTitle = "User Dashboard";
require_once 'MLRealEstate/includes/header.php';

require_once 'includes/DashboardStats.php';

try {
    $db = new PDO("mysql:host=localhost;dbname=muwana_legacy", "root", "");
    $statsGenerator = new DashboardStats($db);
    $stats = $statsGenerator->getUserStats($user['userId'], $user['userType']);
} catch (Exception $e) {
    $stats = [];
}
?>

<!-- Sidebar -->
<div class="dashboard-sidebar">
    <div class="sidebar-header">
        <h2><i class="bi bi-house"></i> ML RealEstate</h2>
        <p>User Dashboard</p>
    </div>
    <ul class="sidebar-menu">
    <li><a href="#" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
    <li><a href="#"><i class="bi bi-search"></i> Browse Properties</a></li>
    <li><a href="#"><i class="bi bi-heart"></i> Favorites</a></li>
    <li><a href="#"><i class="bi bi-eye"></i> Recently Viewed</a></li>
    <li><a href="#"><i class="bi bi-bell"></i> Notifications</a></li>
    <li><a href="#"><i class="bi bi-person"></i> Profile</a></li>
    <li><a href="#"><i class="bi bi-gear"></i> Settings</a></li>
    <!-- LOGOUT LINK -->
    <li><a href="../logout.php" class="logout-btn"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
</ul>

<!-- Main Content -->
<div class="dashboard-main">
    <div class="dashboard-header">
        <h1>Welcome, <?php echo htmlspecialchars($user['fullName']); ?>!</h1>
        <p>User dashboard is working!</p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>