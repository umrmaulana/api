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

      // Query produk dalam order + gambar dari product
      $productStmt = $conn->prepare("SELECT 
                                        od.product_id,
                                        od.product_name,
                                        od.qty,
                                        od.price,
                                        od.sub_total,
                                        p.foto AS image_url
                                    FROM order_details od
                                    JOIN product p ON od.product_id = p.kode
                                    WHERE od.order_id = ?");
      $productStmt->bind_param("i", $orderId);
      $productStmt->execute();
      $productResult = $productStmt->get_result();

      $products = [];
      while ($row = $productResult->fetch_assoc()) {
        // Tambahkan URL lengkap jika perlu
        $row['image_url'] = 'https://android.umrmaulana.my.id/api/images/product/' . $row['image_url'];
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
