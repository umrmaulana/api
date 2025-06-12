<?php
header("Content-Type: application/json");
include "koneksimysql.php";

$response = ['success' => false, 'message' => ''];

try {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get order ID
    $orderId = $_POST['order_id'];

    // Upload proof transfer
    $proofPath = '';
    if (isset($_FILES['proof_transfer'])) {
      $targetDir = "images/proofs/";
      $fileName = uniqid() . '_' . basename($_FILES["proof_transfer"]["name"]);
      $targetFilePath = $targetDir . $fileName;
      $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

      // Check if image file
      $check = getimagesize($_FILES["proof_transfer"]["tmp_name"]);
      if ($check === false) {
        throw new Exception("File is not an image");
      }

      // Check file size (max 2MB)
      if ($_FILES["proof_transfer"]["size"] > 2000000) {
        throw new Exception("File size exceeds 2MB");
      }

      // Allow certain file formats
      $allowedTypes = ['jpg', 'jpeg', 'png'];
      if (!in_array($fileType, $allowedTypes)) {
        throw new Exception("Only JPG, JPEG, PNG files are allowed");
      }

      // Upload file
      if (move_uploaded_file($_FILES["proof_transfer"]["tmp_name"], $targetFilePath)) {
        $proofPath = $targetFilePath;
      } else {
        throw new Exception("Error uploading file");
      }
    }

    // Update order in database
    $stmt = $conn->prepare("UPDATE orders 
                              SET proof_transfer = ?, 
                                  payment_status = 'pending_verification',
                                  order_status = 'processing',
                              WHERE id = ?");
    $stmt->bind_param("si", $proofPath, $orderId);

    if ($stmt->execute()) {
      $response = [
        'success' => true,
        'message' => 'Payment proof uploaded successfully'
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
}

echo json_encode($response);