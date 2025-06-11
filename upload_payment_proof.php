<?php
header("Content-Type: application/json");
include "koneksimysql.php";

$response = ['success' => false, 'message' => ''];

try {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi input
    if (!isset($_POST['order_id'])) {
      throw new Exception("Order ID is required");
    }
    $orderId = $_POST['order_id'];

    // Upload file proof transfer
    if (!isset($_FILES['proof_transfer'])) {
      throw new Exception("Proof transfer file is required");
    }

    $uploadResult = uploadProof($_FILES['proof_transfer']);
    if (!$uploadResult['success']) {
      throw new Exception($uploadResult['message']);
    }

    $proofPath = $uploadResult['path'];

    // Update order in database
    $stmt = $conn->prepare("UPDATE orders 
                              SET proof_transfer = ?, 
                                  payment_status = 'pending_verification',
                                  order_status = 'processing'
                              WHERE id = ?");
    $stmt->bind_param("si", $proofPath, $orderId);

    if (!$stmt->execute()) {
      throw new Exception("Failed to update order: " . $stmt->error);
    }

    $stmt->close();

    $response = [
      'success' => true,
      'message' => 'Payment proof uploaded successfully'
    ];
  } else {
    $response['message'] = 'Invalid request method';
  }
} catch (Exception $e) {
  $response['message'] = $e->getMessage();
}

echo json_encode($response);
function uploadProof($file)
{
  $targetDir = "uploads/";
  $targetFile = $targetDir . basename($file["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

  // Check if file is an image
  $check = getimagesize($file["tmp_name"]);
  if ($check === false) {
    return ['success' => false, 'message' => 'File is not an image'];
  }

  // Check file size (max 5MB)
  if ($file["size"] > 5000000) {
    return ['success' => false, 'message' => 'File is too large'];
  }

  // Allow certain file formats
  if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
    return ['success' => false, 'message' => 'Only JPG, JPEG, PNG & GIF files are allowed'];
  }

  // Try to upload file
  if (move_uploaded_file($file["tmp_name"], $targetFile)) {
    return ['success' => true, 'path' => $targetFile];
  } else {
    return ['success' => false, 'message' => 'Error uploading file'];
  }
}

?>