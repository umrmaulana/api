<?php
include "koneksimysql.php";

header("Content-Type: application/json");

$user_id = $_GET['user_id'] ?? 0;
if ($user_id <= 0) {
  echo json_encode([
    'status' => 'error',
    'message' => 'User ID is required'
  ]);
  exit;
}
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM carts WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$total_items = $data['total'];
echo json_encode([
  'status' => 'success',
  'message' => 'Total items retrieved successfully',
  'result' => [
    [
      'user_id' => $user_id,
      'total_items' => $total_items
    ]
  ]
]);

$stmt->close();
?>