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
                    SELECT c.*, p.merk, p.harga_jual, p.harga_pokok, p.diskon_jual, p.foto 
                    FROM carts c
                    JOIN product p ON c.product_id = p.kode
                    WHERE c.user_id = ?
                ");

        if ($stmt === false) {
          throw new Exception("Prepare failed: " . print_r($conn->errorInfo(), true));
        }

        $stmt->execute([$user_id]);
        $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

    // ... rest of your switch cases ...

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