<?php
include "koneksimysql.php";
header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $address_id = intval($_POST['id']);

    // Cari user_id berdasarkan id alamat
    $getUserIdQuery = "SELECT user_id FROM ship_address WHERE id = ?";
    $stmt = $conn->prepare($getUserIdQuery);
    $stmt->bind_param("i", $address_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $user_id = $row['user_id'];

        // Set semua alamat user jadi nonaktif
        $conn->query("UPDATE ship_address SET is_active = '0' WHERE user_id = $user_id");

        // Aktifkan alamat yang dipilih
        $updateQuery = "UPDATE ship_address SET is_active = '1' WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("i", $address_id);
        if ($stmt->execute()) {
            echo json_encode(['message' => 'Alamat aktif diperbarui']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Gagal memperbarui alamat']);
        }
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Alamat tidak ditemukan']);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'id is required']);
}
?>