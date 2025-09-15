<?php
session_start();
header('Content-Type: application/json');

// Check if user is admin or distributor
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== "Admin" && $_SESSION['role'] !== "Distributor")) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Database connection
include 'db.php';

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['order_id']) || !isset($input['status'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit();
}

$order_id = intval($input['order_id']);
$new_status = $input['status'];

// Validate status
$valid_statuses = ['pending', 'confirmed', 'processing', 'delivered', 'cancelled'];
if (!in_array($new_status, $valid_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status']);
    exit();
}

// Validate distributor assignment
if (isset($input['distributor']) && $_SESSION['role'] === 'Admin') {
    // Check if distributor exists
    $stmt = $conn->prepare("SELECT user_name FROM distributor WHERE user_name = ?");
    $stmt->bind_param("s", $input['distributor']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid distributor']);
        exit();
    }
    $stmt->close();
}

// Validate distributor permissions
if ($_SESSION['role'] === 'Distributor') {
    // Check if order is assigned to this distributor
    $stmt = $conn->prepare("SELECT id FROM orders WHERE id = ? AND assigned_distributor = ?");
    $stmt->bind_param("is", $order_id, $_SESSION['user_name']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Order not assigned to you']);
        exit();
    }
    $stmt->close();
}

try {
    // Check if this is a distributor assignment
    if (isset($input['distributor']) && $_SESSION['role'] === 'Admin') {
        // Assign order to distributor and update status
        $stmt = $conn->prepare("UPDATE orders SET 
            order_status = ?, 
            assigned_distributor = ?,
            assigned_at = CURRENT_TIMESTAMP,
            last_status_update = CURRENT_TIMESTAMP,
            updated_at = CURRENT_TIMESTAMP 
            WHERE id = ?");
        $stmt->bind_param("ssi", $new_status, $input['distributor'], $order_id);
    } 
    // Check if this is a distributor updating their assigned order
    else if ($_SESSION['role'] === 'Distributor') {
        // Only allow distributors to update their own assigned orders
        $stmt = $conn->prepare("UPDATE orders SET 
            order_status = ?, 
            last_status_update = CURRENT_TIMESTAMP,
            updated_at = CURRENT_TIMESTAMP 
            WHERE id = ? AND assigned_distributor = ?");
        $stmt->bind_param("sis", $new_status, $order_id, $_SESSION['user_name']);
    }
    // Admin updating status without distributor assignment
    else {
        $stmt = $conn->prepare("UPDATE orders SET 
            order_status = ?, 
            last_status_update = CURRENT_TIMESTAMP,
            updated_at = CURRENT_TIMESTAMP 
            WHERE id = ?");
        $stmt->bind_param("si", $new_status, $order_id);
    }
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Order updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Order not found or no changes made']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    }
    
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

$conn->close();
?>