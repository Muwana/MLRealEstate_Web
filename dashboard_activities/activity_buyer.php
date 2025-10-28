<?php
$pageTitle = "My Activity";
require_once '../includes/header.php';
?>

<!-- Sidebar -->
<div class="dashboard-sidebar">
    <div class="sidebar-header">
        <h2><i class="bi bi-house"></i> ML RealEstate</h2>
        <p>User Dashboard</p>
    </div>
    <ul class="sidebar-menu">
        <li><a href="../user_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li><a href="#" class="active"><i class="bi bi-activity"></i> My Activity</a></li>
        <li><a href="#"><i class="bi bi-search"></i> Browse</a></li>
        <li><a href="#"><i class="bi bi-heart"></i> Favorites</a></li>
        <li><a href="../logout.php" class="logout-btn"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="dashboard-main">
    <div class="dashboard-header">
        <div>
            <h1>My Activity</h1>
            <p>Track your property search journey and saved items.</p>
        </div>
        <div class="user-info">
            <div class="user-avatar">
                <?php echo strtoupper(substr($user['fullName'], 0, 1)); ?>
            </div>
        </div>
    </div>

    <!-- Activity Summary -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon properties"><i class="bi bi-eye"></i></div>
            <div class="stat-number">45</div>
            <div class="stat-label">Properties Viewed</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon favorites"><i class="bi bi-heart"></i></div>
            <div class="stat-number">12</div>
            <div class="stat-label">Favorites</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon leads"><i class="bi bi-envelope"></i></div>
            <div class="stat-number">8</div>
            <div class="stat-label">Inquiries Sent</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon searches"><i class="bi bi-search"></i></div>
            <div class="stat-number">5</div>
            <div class="stat-label">Saved Searches</div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="dashboard-section">
        <div class="section-header">
            <h3 class="section-title">Recent Activity</h3>
        </div>
        <div class="activity-timeline">
            <div class="activity-item">
                <div class="activity-icon success"><i class="bi bi-heart"></i></div>
                <div class="activity-content">
                    <div class="activity-title">Added to Favorites</div>
                    <div class="activity-desc">You added "Luxury Villa" to your favorites</div>
                    <div class="activity-time">2 hours ago</div>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon info"><i class="bi bi-eye"></i></div>
                <div class="activity-content">
                    <div class="activity-title">Property Viewed</div>
                    <div class="activity-desc">You viewed "Modern Apartment" in Livingstone</div>
                    <div class="activity-time">Yesterday, 4:30 PM</div>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon warning"><i class="bi bi-envelope"></i></div>
                <div class="activity-content">
                    <div class="activity-title">Inquiry Sent</div>
                    <div class="activity-desc">You sent an inquiry about "Family House"</div>
                    <div class="activity-time">Yesterday, 2:15 PM</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Favorite Properties -->
    <div class="dashboard-section">
        <div class="section-header">
            <h3 class="section-title">Recently Viewed Properties</h3>
            <a href="#" class="dashboard-btn dashboard-btn-outline">View All</a>
        </div>
        <div class="properties-grid">
            <div class="property-card">
                <div class="property-img">
                    <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1176&q=80" alt="Luxury Villa">
                </div>
                <div class="property-content">
                    <div class="property-price">K350,000</div>
                    <div class="property-title">Luxury Villa</div>
                    <div class="property-location">Lusaka, Zambia</div>
                    <div class="property-features">
                        <span>4 beds</span>
                        <span>3 baths</span>
                        <span>280 m²</span>
                    </div>
                    <div class="activity-time" style="margin-top: 10px;">Viewed: Today, 10:30 AM</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.activity-timeline {
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