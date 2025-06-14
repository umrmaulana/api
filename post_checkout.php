<?php
header("Content-Type: application/json");
include "koneksimysql.php";

// Fungsi untuk generate order number
function generateOrderNumber()
{
  return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
}

$response = ['success' => false, 'message' => ''];

try {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data JSON
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Debugging: Log data yang diterima
    error_log("Received data: " . print_r($data, true));

    // Validasi data yang diperlukan
    $requiredFields = [
      'user_id',
      'ship_address_id',
      'sub_total',
      'shipping_cost',
      'final_price',
      'courier',
      'courier_service',
      'estimated_day',
      'total_weight',
      'payment_method',
      'payment_status',
      'order_status',
      'products'
    ];

    // foreach ($requiredFields as $field) {
    //   if (!isset($data[$field]) || empty($data[$field])) {
    //     $missing[] = $field;
    //   }
    // }

    if (!empty($missing)) {
      throw new Exception("Missing fields: " . implode(', ', $missing));
    }

    // Mulai transaksi
    $conn->begin_transaction();

    // Generate order number
    $orderNumber = generateOrderNumber();
    $proofTransfer = '';

    // PERBAIKAN: Gunakan tipe data yang sesuai
    $stmt = $conn->prepare("INSERT INTO orders (
            order_number, user_id, ship_address_id, sub_total, shipping_cost, final_price,
            courier, courier_service, estimated_day, total_weight, payment_method,
            payment_status, order_status, proof_transfer, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    // PERBAIKAN: Gunakan tipe data yang sesuai dalam bind_param
    $stmt->bind_param(
      "siiiddssssssss", // Perhatikan perubahan tipe data di sini
      $orderNumber,
      $data['user_id'],
      $data['ship_address_id'],
      $data['sub_total'],
      $data['shipping_cost'],
      $data['final_price'],
      $data['courier'],
      $data['courier_service'],
      $data['estimated_day'],
      $data['total_weight'],
      $data['payment_method'],
      $data['payment_status'],
      $data['order_status'],
      $proofTransfer
    );

    if (!$stmt->execute()) {
      throw new Exception("Failed to create order: " . $stmt->error);
    }

    $orderId = $stmt->insert_id;
    $stmt->close();

    // Insert produk ke order_details
    foreach ($data['products'] as $product) {
      $stmt = $conn->prepare("INSERT INTO order_details (
                order_id, product_id, product_name, qty, price, sub_total, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, NOW())");

      $subTotal = $product['price'] * $product['qty'];

      $stmt->bind_param(
        "issidd",
        $orderId,
        $product['product_id'],
        $product['product_name'],
        $product['qty'],
        $product['price'],
        $subTotal
      );

      if (!$stmt->execute()) {
        throw new Exception("Failed to add product to order: " . $stmt->error);
      }

      $stmt->close();

      // Update stok produk
      $updateStokStmt = $conn->prepare("UPDATE product SET stok = stok - ? WHERE kode = ?");
      $updateStokStmt->bind_param("is", $product['qty'], $product['product_id']);

      if (!$updateStokStmt->execute()) {
        throw new Exception("Failed to update product stock: " . $updateStokStmt->error);
      }

      $updateStokStmt->close();
    }

    // Jika metode pembayaran adalah COD, langsung update status menjadi paid
    if ($data['payment_method'] === 'cod') {
      $updateStmt = $conn->prepare("UPDATE orders SET payment_status = 'Paid', order_status = 'Processing' WHERE id = ?");
      $updateStmt->bind_param("i", $orderId);
      $updateStmt->execute();
      $updateStmt->close();
    }

    // Commit transaksi jika semua berhasil
    $conn->commit();

    $response = [
      'success' => true,
      'message' => 'Order created successfully',
      'order_id' => $orderId,
      'order_number' => $orderNumber
    ];
  } else {
    $response['message'] = 'Invalid request method';
  }
} catch (Exception $e) {
  // Rollback transaksi jika ada error
  if (isset($conn) && $conn->in_transaction) {
    $conn->rollback();
  }

  $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>