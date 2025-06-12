<?php
header("Content-Type: application/json");
include "koneksimysql.php";

$response = ['success' => false, 'message' => ''];

try {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // PERBAIKAN 1: Gunakan $_POST untuk order_id
    $orderId = isset($_POST['order_id']) ? $_POST['order_id'] : null;

    if (!$orderId) {
      throw new Exception("Order ID is required");
    }

    // Upload proof transfer
    $proofPath = '';
    if (isset($_FILES['proof_transfer'])) {
      $targetDir = "images/proofs/";

      // PERBAIKAN 2: Pastikan folder ada
      if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
      }

      $fileName = uniqid() . '_' . basename($_FILES["proof_transfer"]["name"]);
      $targetFilePath = $targetDir . $fileName;
      $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

      // PERBAIKAN 3: Validasi file
      $check = getimagesize($_FILES["proof_transfer"]["tmp_name"]);
      if ($check === false) {
        throw new Exception("File is not an image");
      }

      if ($_FILES["proof_transfer"]["size"] > 2000000) {
        throw new Exception("File size exceeds 2MB");
      }

      $allowedTypes = ['jpg', 'jpeg', 'png'];
      if (!in_array($fileType, $allowedTypes)) {
        throw new Exception("Only JPG, JPEG, PNG files are allowed");
      }

      // PERBAIKAN 4: Gunakan path absolut
      $absolutePath = __DIR__ . '/' . $targetFilePath;
      if (move_uploaded_file($_FILES["proof_transfer"]["tmp_name"], $absolutePath)) {
        $proofPath = $targetFilePath; // Simpan path relatif
        error_log("File uploaded to: $absolutePath");
      } else {
        throw new Exception("Error uploading file");
      }
    } else {
      throw new Exception("Proof file is required");
    }

    // PERBAIKAN 5: Update database
    $stmt = $conn->prepare("UPDATE orders 
                                SET proof_transfer = ?, 
                                    payment_status = 'pending_verification'
                                WHERE id = ?");
    $stmt->bind_param("si", $proofPath, $orderId);

    if ($stmt->execute()) {
      $response = [
        'success' => true,
        'message' => 'Payment proof uploaded successfully',
        'proof_path' => $proofPath
      ];
    } else {
      throw new Exception("Failed to update order: " . $stmt->error);
    }

    $stmt->close();
  } else {
    $response['message'] = 'Invalid request method';
  }
} catch (Exception $e) {
  $response['message'] = $e->getMessage();
  error_log("Error: " . $e->getMessage());
}

echo json_encode($response);