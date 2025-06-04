<?php
include "koneksimysql.php";
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$response = array();
$action = $_GET['action'] ?? '';

try {
  switch ($action) {
    case 'get_cart':
      $user_id = $_GET['user_id'] ?? 0;
      if ($user_id > 0) {
        $stmt = $conn->prepare("
                    SELECT c.*, p.merk, p.hargajual, p.hargapokok, p.diskonjual, p.foto 
                    FROM carts c
                    JOIN product p ON c.product_id = p.kode
                    WHERE c.user_id = ?
                ");

        if ($stmt === false) {
          throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart_items = $result->fetch_all(MYSQLI_ASSOC);

        $response = [
          'status' => 'success',
          'result' => $cart_items
        ];
      } else {
        $response = [
          'status' => 'error',
          'message' => 'User ID is required'
        ];
      }
      break;

    case 'update_cart':
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $user_id = $input['user_id'] ?? 0;
        $product_id = $input['product_id'] ?? '';
        $quantity = $input['quantity'] ?? 1;
      } else {
        // Ambil dari $_GET jika bukan POST
        $user_id = $_GET['user_id'] ?? 0;
        $product_id = $_GET['product_id'] ?? '';
        $quantity = $_GET['quantity'] ?? 1;
      }

      if ($user_id > 0 && !empty($product_id) && $quantity > 0) {
        // Cek stok produk
        $stmt = $conn->prepare("SELECT stok FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();

        if (!$product) {
          throw new Exception("Product not found");
        }

        if ($quantity > $product['stok']) {
          throw new Exception("Stok tidak mencukupi");
        }

        // Update atau tambahkan item ke keranjang
        $stmt = $conn->prepare("INSERT INTO carts (user_id, product_id, quantity, created_at)
                              VALUES (?, ?, ?, NOW())
                              ON DUPLICATE KEY UPDATE quantity = ?");

        $stmt->bind_param("iiis", $user_id, $product_id, $quantity, $quantity);
        $stmt->execute();

        $response = [
          'status' => 'success',
          'message' => 'Cart updated successfully'
        ];
      } else {
        $response = [
          'status' => 'error',
          'message' => 'Invalid parameters'
        ];
      }
      break;

    case 'remove_cart':
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $user_id = $input['user_id'] ?? 0;
        $product_id = $input['product_id'] ?? '';
      } else {
        // Ambil dari $_GET jika bukan POST
        $user_id = $_GET['user_id'] ?? 0;
        $product_id = $_GET['product_id'] ?? '';
      }

      if ($user_id > 0 && !empty($product_id)) {
        $stmt = $conn->prepare("DELETE FROM carts WHERE user_id = ? AND product_id = ?");

        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();

        $response = [
          'status' => 'success',
          'message' => 'Item removed from cart'
        ];
      } else {
        $response = [
          'status' => 'error',
          'message' => 'Invalid parameters'
        ];
      }
      break;

    default:
      $response = [
        'status' => 'error',
        'message' => 'Invalid action'
      ];
  }
} catch (PDOException $e) {
  $response = [
    'status' => 'error',
    'message' => 'Database error: ' . $e->getMessage()
  ];
} catch (Exception $e) {
  $response = [
    'status' => 'error',
    'message' => 'Error: ' . $e->getMessage()
  ];
}

echo json_encode($response);