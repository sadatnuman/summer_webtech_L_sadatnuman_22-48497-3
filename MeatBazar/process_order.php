<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "User") {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
    exit();
}

// Validate required fields
$required_fields = ['customer_name', 'customer_phone', 'customer_address', 'items', 'subtotal', 'delivery_fee', 'total_amount', 'payment_type', 'payment_method'];

foreach ($required_fields as $field) {
    if (!isset($input[$field]) || empty($input[$field])) {
        echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
        exit();
    }
}

// Validate items array
if (!is_array($input['items']) || empty($input['items'])) {
    echo json_encode(['success' => false, 'message' => 'Cart is empty']);
    exit();
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_phone, customer_address, items, subtotal, delivery_fee, total_amount, payment_type, payment_method, order_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $items_json = json_encode($input['items']);
    $order_status = 'pending';
    
    $stmt->bind_param("ssssdddsss", 
        $input['customer_name'],
        $input['customer_phone'],
        $input['customer_address'],
        $items_json,
        $input['subtotal'],
        $input['delivery_fee'],
        $input['total_amount'],
        $input['payment_type'],
        $input['payment_method'],
        $order_status
    );

    if (!$stmt->execute()) {
        throw new Exception("Error inserting order: " . $stmt->error);
    }

    $order_id = $conn->insert_id;

    // Insert order items
    $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_name, product_price, product_image, quantity, item_total) VALUES (?, ?, ?, ?, ?, ?)");

    foreach ($input['items'] as $item) {
        $item_stmt->bind_param("isdsid",
            $order_id,
            $item['name'],
            $item['price'],
            $item['image'],
            $item['quantity'],
            $item['total']
        );

        if (!$item_stmt->execute()) {
            throw new Exception("Error inserting order item: " . $item_stmt->error);
        }
    }

    // Commit transaction
    $conn->commit();

    echo json_encode([
        'success' => true, 
        'message' => 'Order placed successfully',
        'order_id' => $order_id
    ]);

} catch (Exception $e) {
    // Rollback transaction
    $conn->rollback();
    
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?>