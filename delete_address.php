<?php
include "koneksimysql.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
  parse_str(file_get_contents("php://input"), $input);
  $id = isset($input['id']) ? intval($input['id']) : 0;

  if ($id === 0) {
    http_response_code(400);
    echo json_encode(["message" => "ID tidak valid"]);
    exit();
  }

  // Hapus data dari database
  $stmt = $conn->prepare("DELETE FROM ship_address WHERE id = ?");
  $stmt->bind_param("i", $id);

  if ($stmt->execute()) {
    echo json_encode(["message" => "Alamat berhasil dihapus"]);
  } else {
    http_response_code(500);
    echo json_encode(["message" => "Gagal menghapus alamat"]);
  }

  $stmt->close();
} else {
  http_response_code(405);
  echo json_encode(["message" => "Metode tidak diizinkan"]);
}

$conn->close();
?>