<?php
session_start();
// Check if user is logged in and is an admin/agent
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['userType'], ['admin', 'agent'])) {
    header('Location: ../login.php');
    exit();
}

$user = $_SESSION['user'];
$pageTitle = "Admin Dashboard";
require_once 'includes/header.php';

// Include DashboardStats
require_once 'includes/DashboardStats.php';
$db = new PDO("mysql:host=localhost;dbname=muwana_legacy", "root", "");
$statsGenerator = new DashboardStats($db);
$stats = $statsGenerator->getUserStats($user['userId'], $user['userType']);
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
        <li><a href="../logout.php" class="logout-btn"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
    </ul>
</div>

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
        <?php foreach ($stats as $stat): ?>
        <div class="stat-card">
            <div class="stat-icon <?php echo strtolower(str_replace(' ', '-', $stat['label'])); ?>">
                <i class="<?php echo $stat['icon']; ?>"></i>
            </div>
            <div class="stat-number"><?php echo $stat['value']; ?></div>
            <div class="stat-label"><?php echo $stat['label']; ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Recent Users & Properties -->
    <div class="charts-grid">
        <div class="dashboard-section">
            <div class="section-header">
                <h3 class="section-title">Recent Users</h3>
                <a href="#" class="dashboard-btn dashboard-btn-outline">View All</a>
            </div>
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>John Smith<br><small>john@email.com</small></td>
                        <td>Buyer</td>
                        <td><span class="status active">Active</span></td>
                        <td><a href="#" class="dashboard-btn dashboard-btn-outline">Edit</a></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="dashboard-section">
            <div class="section-header">
                <h3 class="section-title">Recent Properties</h3>
                <a href="#" class="dashboard-btn dashboard-btn-outline">View All</a>
            </div>
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Luxury Villa<br><small>Lusaka</small></td>
                        <td>K350,000</td>
                        <td><span class="status active">Active</span></td>
                        <td>
                            <a href="#" class="dashboard-btn dashboard-btn-outline">Edit</a>
                            <a href="#" class="dashboard-btn dashboard-btn-danger">Delete</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>