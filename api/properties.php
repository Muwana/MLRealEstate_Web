<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include database configuration
include_once 'config.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    // Get the action from query parameters
    $action = $_GET['action'] ?? 'getAll';

    switch ($action) {
        case 'getAll':
            getAllProperties($db);
            break;
        case 'sendInquiry':
            sendInquiry($db);
            break;
        case 'addFavorite':
            addFavorite($db);
            break;
        default:
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Invalid action"]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Server error: " . $e->getMessage()
    ]);
}

// Function to get all properties with filters
function getAllProperties($db) {
    // Get query parameters for filtering
    $filters = [
        'property_type' => $_GET['type'] ?? '',
        'listing_type' => $_GET['listing_type'] ?? '',
        'min_price' => $_GET['min_price'] ?? 0,
        'max_price' => $_GET['max_price'] ?? 999999999,
        'bedrooms' => $_GET['bedrooms'] ?? '',
        'location' => $_GET['location'] ?? '',
        'limit' => $_GET['limit'] ?? 20,
        'page' => $_GET['page'] ?? 1
    ];

    $offset = ($filters['page'] - 1) * $filters['limit'];

    // Build base query
    $query = "SELECT p.*, u.full_name as seller_name 
              FROM properties p 
              LEFT JOIN users u ON p.user_id = u.id 
              WHERE p.status = 'available'";

    $params = [];

    // Add filters
    if (!empty($filters['property_type'])) {
        $query .= " AND p.property_type = :type";
        $params[':type'] = $filters['property_type'];
    }

    if (!empty($filters['listing_type'])) {
        $query .= " AND p.listing_type = :listing_type";
        $params[':listing_type'] = $filters['listing_type'];
    }

    if (!empty($filters['min_price'])) {
        $query .= " AND p.price >= :min_price";
        $params[':min_price'] = $filters['min_price'];
    }

    if (!empty($filters['max_price'])) {
        $query .= " AND p.price <= :max_price";
        $params[':max_price'] = $filters['max_price'];
    }

    if (!empty($filters['bedrooms'])) {
        $query .= " AND p.bedrooms >= :bedrooms";
        $params[':bedrooms'] = $filters['bedrooms'];
    }

    if (!empty($filters['location'])) {
        $query .= " AND (p.city LIKE :location OR p.address LIKE :location)";
        $params[':location'] = "%{$filters['location']}%";
    }

    // Add ordering and pagination
    $query .= " ORDER BY p.featured DESC, p.created_at DESC 
                LIMIT :limit OFFSET :offset";
    
    $params[':limit'] = (int)$filters['limit'];
    $params[':offset'] = (int)$offset;

    $stmt = $db->prepare($query);

    // Bind parameters
    foreach ($params as $key => $value) {
        $paramType = (in_array($key, [':limit', ':offset'])) ? PDO::PARAM_INT : PDO::PARAM_STR;
        $stmt->bindValue($key, $value, $paramType);
    }

    $stmt->execute();
    $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get total count
    $countQuery = "SELECT COUNT(*) as total FROM properties WHERE status = 'available'";
    $countStmt = $db->prepare($countQuery);
    $countStmt->execute();
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Format response
    $formattedProperties = [];
    foreach ($properties as $property) {
        $formattedProperties[] = [
            "id" => $property['id'],
            "title" => $property['title'],
            "description" => $property['description'],
            "price" => (float)$property['price'],
            "type" => $property['property_type'],
            "listing_type" => $property['listing_type'],
            "bedrooms" => $property['bedrooms'],
            "bathrooms" => (float)$property['bathrooms'],
            "area" => $property['area_sqft'],
            "location" => $property['city'] . ', ' . $property['state'],
            "address" => $property['address_line1'],
            "city" => $property['city'],
            "state" => $property['state'],
            "image_url" => $property['primary_image'] ?? 'default_property.jpg',
            "status" => $property['status'],
            "featured" => (bool)$property['featured'],
            "seller_name" => $property['seller_name'],
            "created_at" => $property['created_at']
        ];
    }

    echo json_encode([
        "success" => true,
        "properties" => $formattedProperties,
        "pagination" => [
            "total" => (int)$total,
            "page" => (int)$filters['page'],
            "limit" => (int)$filters['limit'],
            "totalPages" => ceil($total / $filters['limit'])
        ]
    ]);
}

// Function to handle property inquiries
function sendInquiry($db) {
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    if (empty($input['propertyId']) || empty($input['userId']) || empty($input['message'])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Missing required fields"]);
        return;
    }

    $stmt = $db->prepare("INSERT INTO inquiries (property_id, user_id, message) VALUES (?, ?, ?)");
    
    if ($stmt->execute([$input['propertyId'], $input['userId'], $input['message']])) {
        echo json_encode([
            "success" => true,
            "message" => "Inquiry sent successfully"
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Failed to send inquiry"
        ]);
    }
}

// Function to add property to favorites
function addFavorite($db) {
    $input = json_decode(file_get_contents('php://input'), true);

    if (empty($input['propertyId']) || empty($input['userId'])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Missing required fields"]);
        return;
    }

    // Check if already favorited
    $checkStmt = $db->prepare("SELECT id FROM favorites WHERE property_id = ? AND user_id = ?");
    $checkStmt->execute([$input['propertyId'], $input['userId']]);
    
    if ($checkStmt->rowCount() > 0) {
        echo json_encode([
            "success" => false,
            "message" => "Property already in favorites"
        ]);
        return;
    }

    $stmt = $db->prepare("INSERT INTO favorites (property_id, user_id) VALUES (?, ?)");
    
    if ($stmt->execute([$input['propertyId'], $input['userId']])) {
        echo json_encode([
            "success" => true,
            "message" => "Added to favorites"
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Failed to add to favorites"
        ]);
    }
}
?>