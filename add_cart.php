<?php
include "koneksimysql.php";
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");


$response = array();
$input = json_decode(file_get_contents('php://input'), true);

$user_id = $input['user_id'] ?? 0;
$product_id = $input['product_id'] ?? '';
$quantity = $input['quantity'] ?? 1;

try {
  // 1. Check product stock first
  $stmt = $conn->prepare("SELECT stok FROM products WHERE id = ?");
  $stmt->execute([$product_id]);
  $product = $stmt->fetch();

  if (!$product) {
    throw new Exception("Product not found");
  }

  // 2. Check if item already exists in cart
  $stmt = $conn->prepare("SELECT * FROM carts WHERE user_id = ? AND product_id = ?");
  $stmt->execute([$user_id, $product_id]);
  $existing_item = $stmt->fetch();

  if ($existing_item) {
    // Check if new quantity exceeds stock
    $new_qty = $existing_item['quantity'] + $quantity;
    if ($new_qty > $product['stok']) {
      throw new Exception("Stok tidak mencukupi");
    }

    // Update quantity if exists
    $stmt = $conn->prepare("UPDATE carts SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$new_qty, $user_id, $product_id]);
  } else {
    // Add new item if not exists
    if ($quantity > $product['stok']) {
      throw new Exception("Stok tidak mencukupi");
    }

    $stmt = $conn->prepare("INSERT INTO carts (user_id, product_id, quantity, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$user_id, $product_id, $quantity]);
  }

  $response = [
    'status' => 'success',
    'message' => 'Item added to cart'
  ];
} catch (PDOException $e) {
  $response = [
    'status' => 'error',
    'message' => 'Database error: ' . $e->getMessage()
  ];
} catch (Exception $e) {
  $response = [
    'status' => 'error',
    'message' => $e->getMessage()
  ];
}

echo json_encode($response);