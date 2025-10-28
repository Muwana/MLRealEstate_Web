<?php
session_start();
// Check if user is logged in and is an admin/agent
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['userType'], ['admin', 'agent'])) {
    header('Location: ../login.php');
    exit();
}

$user = $_SESSION['user'];
$pageTitle = "Admin Dashboard";

// Fix the path - use correct relative path
require_once 'includes/header.php';

// Include DashboardStats with correct path
require_once 'includes/DashboardStats.php';

// Create database connection
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
        <p>Admin Dashboard</p>
    </div>
    <ul class="sidebar-menu">
    <li><a href="#" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
    <li><a href="#"><i class="bi bi-people"></i> Users</a></li>
    <li><a href="#"><i class="bi bi-house"></i> Properties</a></li>
    <li><a href="#"><i class="bi bi-bar-chart"></i> Analytics</a></li>
    <li><a href="#"><i class="bi bi-credit-card"></i> Payments</a></li>
    <li><a href="#"><i class="bi bi-gear"></i> Settings</a></li>
    <!-- LOGOUT LINK -->
    <li><a href="../logout.php" class="logout-btn"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
</ul>
   

<!-- Main Content -->
<div class="dashboard-main">
    <div class="dashboard-header">
        <div>
            <h1>Welcome, <?php echo htmlspecialchars($user['fullName']); ?>!</h1>
            <p>System overview and management dashboard.</p>
        </div>
        <div class="user-info">
            <div class="user-avatar">
                <?php echo strtoupper(substr($user['fullName'], 0, 1)); ?>
            </div>
            <div>
                <strong><?php echo htmlspecialchars($user['fullName']); ?></strong>
                <div style="font-size: 0.9rem; color: var(--gray);">Administrator</div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <?php if (!empty($stats)): ?>
            <?php foreach ($stats as $stat): ?>
            <div class="stat-card">
                <div class="stat-icon <?php echo strtolower(str_replace(' ', '-', $stat['label'])); ?>">
                    <i class="<?php echo $stat['icon']; ?>"></i>
                </div>
                <div class="stat-number"><?php echo $stat['value']; ?></div>
                <div class="stat-label"><?php echo $stat['label']; ?></div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="stat-card">
                <div class="stat-icon users">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-number">0</div>
                <div class="stat-label">Loading Stats...</div>
            </div>
        <?php endif; ?>
    </div>

    <div class="dashboard-section">
        <h3>Admin Dashboard is Working!</h3>
        <p>Your admin dashboard is now properly connected.</p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>