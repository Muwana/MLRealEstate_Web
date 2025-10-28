<?php
$pageTitle = "Seller Activity";
require_once '../includes/header.php';
?>

<!-- Sidebar -->
<div class="dashboard-sidebar">
    <div class="sidebar-header">
        <h2><i class="bi bi-house"></i> ML RealEstate</h2>
        <p>Seller Dashboard</p>
    </div>
    <ul class="sidebar-menu">
        <li><a href="../seller_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li><a href="#" class="active"><i class="bi bi-activity"></i> My Activity</a></li>
        <li><a href="#"><i class="bi bi-plus-circle"></i> Add Property</a></li>
        <li><a href="#"><i class="bi bi-house"></i> My Properties</a></li>
        <li><a href="../logout.php" class="logout-btn"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="dashboard-main">
    <div class="dashboard-header">
        <div>
            <h1>My Activity</h1>
            <p>Track your property listings and customer interactions.</p>
        </div>
        <div class="user-info">
            <div class="user-avatar">
                <?php echo strtoupper(substr($user['fullName'], 0, 1)); ?>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon properties"><i class="bi bi-eye"></i></div>
            <div class="stat-number">1.2K</div>
            <div class="stat-label">Total Views</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon leads"><i class="bi bi-telephone"></i></div>
            <div class="stat-number">24</div>
            <div class="stat-label">New Inquiries</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon revenue"><i class="bi bi-chat"></i></div>
            <div class="stat-number">18</div>
            <div class="stat-label">Messages</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon pending"><i class="bi bi-calendar"></i></div>
            <div class="stat-number">5</div>
            <div class="stat-label">Viewings Scheduled</div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="dashboard-section">
        <div class="section-header">
            <h3 class="section-title">Recent Activities</h3>
        </div>
        <div class="activity-list">
            <div class="activity-item">
                <div class="activity-icon success"><i class="bi bi-eye"></i></div>
                <div class="activity-content">
                    <div class="activity-title">Property Viewed</div>
                    <div class="activity-desc">Your "Luxury Villa" received 15 new views</div>
                    <div class="activity-time">Today, 10:30 AM</div>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon info"><i class="bi bi-envelope"></i></div>
                <div class="activity-content">
                    <div class="activity-title">New Inquiry</div>
                    <div class="activity-desc">John Doe inquired about "Modern Apartment"</div>
                    <div class="activity-time">Yesterday, 3:45 PM</div>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon warning"><i class="bi bi-calendar"></i></div>
                <div class="activity-content">
                    <div class="activity-title">Viewing Scheduled</div>
                    <div class="activity-desc">Property viewing scheduled for Friday 2:00 PM</div>
                    <div class="activity-time">Yesterday, 11:20 AM</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Chart -->
    <div class="dashboard-section">
        <div class="section-header">
            <h3 class="section-title">Performance Overview</h3>
        </div>
        <div class="chart-placeholder">
            Property Views & Inquiries Chart (Last 30 Days)
        </div>
    </div>
</div>

<style>
.activity-list {
    display: grid;
    gap: 15px;
}

.activity-item {
    display: flex;
    gap: 15px;
    padding: 15px;
    background: white;
    border: 1px solid #eee;
    border-radius: 8px;
    transition: var(--transition);
}

.activity-item:hover {
    box-shadow: var(--shadow);
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.activity-icon.success { background: #27ae60; }
.activity-icon.info { background: var(--accent); }
.activity-icon.warning { background: #f39c12; }

.activity-content {
    flex: 1;
}

.activity-title {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 5px;
}

.activity-desc {
    color: var(--gray);
    margin-bottom: 5px;
}

.activity-time {
    font-size: 0.8rem;
    color: var(--gray);
}
</style>

<?php require_once '../includes/footer.php'; ?>