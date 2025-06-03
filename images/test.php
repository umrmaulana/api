<?php
header("Content-Type: application/json");
include 'koneksimysql.php';

$response = [
    "result" => 0,
    "message" => "Gagal mengunggah foto"
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_FILES['foto'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $foto = $_FILES['foto'];

    $uploadDir = "images/avatar/";
    $ext = pathinfo($foto['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . "_" . date("YmdHis") . "." . strtolower($ext);
    $targetFile = $uploadDir . $filename;

    // Pastikan direktori upload ada
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (move_uploaded_file($foto['tmp_name'], $targetFile)) {
        $sql = "UPDATE user SET foto = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $filename, $email);
        if ($stmt->execute()) {
            $response['result'] = 1;
            $response['message'] = "Foto berhasil diunggah";
            $response['url'] = "https://android.umrmaulana.my.id/api/" . $targetFile;
        } else {
            $response['message'] = "Gagal memperbarui database";
        }
        $stmt->close();
    } else {
        $response['message'] = "Gagal memindahkan file";
    }
} else {
    $response['message'] = "Parameter tidak lengkap";
}

echo json_encode($response);
$conn->close();
