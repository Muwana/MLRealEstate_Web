<?php
// Get recently viewed properties for buyer
$query = "SELECT DISTINCT p.*, pi.image_url 
          FROM property_views pv
          JOIN properties p ON pv.property_id = p.id
          LEFT JOIN property_images pi ON p.id = pi.property_id AND pi.is_primary = 1
          WHERE pv.user_id = ? AND p.status = 'available'
          ORDER BY pv.viewed_at DESC
          LIMIT 5";

$stmt = $this->conn->prepare($query);
$stmt->execute([$current_user['id']]);
$properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($properties)) {
    echo '<p>No recently viewed properties.</p>';
    echo '<a href="search.php" class="btn-primary action-btn">Start Searching</a>';
} else {
    echo '<div class="properties-grid" style="display: grid; gap: 15px;">';
    foreach ($properties as $property) {
        echo '<div style="border: 1px solid #ddd; border-radius: 8px; padding: 15px; display: flex; gap: 15px;">';
        if ($property['image_url']) {
            echo '<img src="' . htmlspecialchars($property['image_url']) . '" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">';
        }
        echo '<div style="flex: 1;">';
        echo '<h4 style="margin-bottom: 5px;">' . htmlspecialchars($property['title']) . '</h4>';
        echo '<p style="color: #666; margin-bottom: 5px;">' . htmlspecialchars($property['city']) . '</p>';
        echo '<p style="color: var(--primary-color); font-weight: bold; margin-bottom: 10px;">ZMW ' . number_format($property['price']) . '</p>';
        echo '<div style="display: flex; gap: 10px;">';
        echo '<a href="property.php?id=' . $property['id'] . '" class="btn-primary action-btn">View</a>';
        echo '<a href="inquiry.php?property_id=' . $property['id'] . '" class="btn-secondary action-btn">Inquire</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
}
?>