<?php
header("Content-Type: application/json");
include "koneksimysql.php";

$response = ['success' => false, 'message' => ''];

try {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $addressId = $_POST['address_id'];

    $stmt = $conn->prepare("SELECT * FROM ship_address WHERE id = ?");
    $stmt->bind_param("i", $addressId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $response['success'] = true;
      $response['address'] = $result->fetch_assoc();
    } else {
      $response['message'] = 'Address not found';
    }
  } else {
    $response['message'] = 'Invalid request method';
  }
} catch (Exception $e) {
  $response['message'] = $e->getMessage();
}

echo json_encode($response);