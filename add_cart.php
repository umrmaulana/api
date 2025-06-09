<?php
include "koneksimysql.php";
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$response = array();

// Ambil data dari form-urlencoded, bukan dari JSON
$user_id = $_POST['user_id'] ?? 0;
$product_id = $_POST['product_id'] ?? '';
$quantity = $_POST['quantity'] ?? 1;

try {
  // 1. Cek stok produk
  $stmt = $conn->prepare("SELECT stok FROM products WHERE id = ?");
  $stmt->execute([$product_id]);
  $product = $stmt->fetch();

  if (!$product) {
    throw new Exception("Produk tidak ditemukan");
  }

  // 2. Cek apakah produk sudah ada di keranjang
  $stmt = $conn->prepare("SELECT * FROM carts WHERE user_id = ? AND product_id = ?");
  $stmt->execute([$user_id, $product_id]);
  $existing_item = $stmt->fetch();

  if ($existing_item) {
    // Hitung jumlah baru
    $new_qty = $existing_item['quantity'] + $quantity;
    if ($new_qty > $product['stok']) {
      throw new Exception("Stok tidak mencukupi");
    }

    // Update jika sudah ada
    $stmt = $conn->prepare("UPDATE carts SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$new_qty, $user_id, $product_id]);
  } else {
    // Tambahkan item baru jika belum ada
    if ($quantity > $product['stok']) {
      throw new Exception("Stok tidak mencukupi");
    }

    $stmt = $conn->prepare("INSERT INTO carts (user_id, product_id, quantity, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$user_id, $product_id, $quantity]);
  }

  $response = [
    'status' => 'success',
    'message' => 'Item berhasil ditambahkan ke keranjang'
  ];
} catch (PDOException $e) {
  $response = [
    'status' => 'error',
    'message' => 'Kesalahan database: ' . $e->getMessage()
  ];
} catch (Exception $e) {
  $response = [
    'status' => 'error',
    'message' => $e->getMessage()
  ];
}

echo json_encode($response);
