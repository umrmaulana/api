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
      $input = json_decode(file_get_contents('php://input'), true);
      $user_id = $input['user_id'] ?? 0;
      $product_id = $input['product_id'] ?? '';
      $quantity = $input['quantity'] ?? 0;

      if ($user_id > 0 && !empty($product_id) && $quantity > 0) {
        // Cek apakah item sudah ada di cart
        $stmt = $db->prepare("SELECT * FROM carts WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $existing_item = $stmt->fetch();

        if ($existing_item) {
          // Update quantity jika sudah ada
          $stmt = $db->prepare("UPDATE carts SET quantity = ? WHERE user_id = ? AND product_id = ?");
          $stmt->execute([$quantity, $user_id, $product_id]);
        } else {
          // Tambahkan baru jika belum ada
          $stmt = $db->prepare("INSERT INTO carts (user_id, product_id, quantity, created_at) VALUES (?, ?, ?, NOW())");
          $stmt->execute([$user_id, $product_id, $quantity]);
        }

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
      $input = json_decode(file_get_contents('php://input'), true);
      $user_id = $input['user_id'] ?? 0;
      $product_id = $input['product_id'] ?? '';

      if ($user_id > 0 && !empty($product_id)) {
        $stmt = $db->prepare("DELETE FROM carts WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);

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