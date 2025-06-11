<?php
header("Content-Type: application/json");
include "koneksimysql.php";

$response = ['success' => false, 'message' => ''];

try {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'];

    // Query untuk mendapatkan detail order
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $order = $result->fetch_assoc();

      // Query untuk mendapatkan produk dalam order
      $productStmt = $conn->prepare("SELECT * FROM order_details WHERE order_id = ?");
      $productStmt->bind_param("i", $orderId);
      $productStmt->execute();
      $productResult = $productStmt->get_result();

      $products = [];
      while ($row = $productResult->fetch_assoc()) {
        $products[] = $row;
      }

      $response = [
        'success' => true,
        'order' => $order,
        'products' => $products
      ];
    } else {
      $response['message'] = 'Order not found';
    }
  } else {
    $response['message'] = 'Invalid request method';
  }
} catch (Exception $e) {
  $response['message'] = $e->getMessage();
}

echo json_encode($response);