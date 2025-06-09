<?php
include "koneksimysql.php";

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$response = array();

$user_id = $_POST['user_id'] ?? 0;
$product_id = $_POST['product_id'] ?? '';
$quantity = $_POST['quantity'] ?? 1;

try {
  // Cek apakah produk ada
  $stmt = $conn->prepare("SELECT stok FROM product WHERE kode = ?");
  $stmt->bind_param("s", $product_id);  // 's' = string
  $stmt->execute();
  $result = $stmt->get_result();
  $product = $result->fetch_assoc();

  if (!$product) {
    throw new Exception("Produk tidak ditemukan");
  }

  // Cek apakah produk sudah di keranjang
  $stmt = $conn->prepare("SELECT * FROM carts WHERE user_id = ? AND product_id = ?");
  $stmt->bind_param("is", $user_id, $product_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $existing_item = $result->fetch_assoc();

  if ($existing_item) {
    $new_qty = $existing_item['quantity'] + $quantity;
    if ($new_qty > $product['stok']) {
      throw new Exception("Stok tidak mencukupi");
    }

    $stmt = $conn->prepare("UPDATE carts SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("iis", $new_qty, $user_id, $product_id);
    $stmt->execute();
  } else {
    if ($quantity > $product['stok']) {
      throw new Exception("Stok tidak mencukupi");
    }

    $stmt = $conn->prepare("INSERT INTO carts (user_id, product_id, quantity, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("isi", $user_id, $product_id, $quantity);
    $stmt->execute();
  }

  $response = [
    'status' => 'success',
    'message' => 'Item berhasil ditambahkan ke keranjang'
  ];
} catch (Exception $e) {
  $response = [
    'status' => 'error',
    'message' => $e->getMessage()
  ];
}

echo json_encode($response);
