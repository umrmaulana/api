<?php
include "koneksimysql.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

  $stmt = $conn->prepare("SELECT * FROM ship_address WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  echo json_encode($result->fetch_assoc());

  $stmt->close();
}
$conn->close();
?>