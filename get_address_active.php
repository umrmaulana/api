<?php
include "koneksimysql.php";
header('Content-Type: application/json');

if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);

    $query = "SELECT * FROM ship_address WHERE user_id = ? and ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $addresses = [];

    while ($row = $result->fetch_assoc()) {
        $addresses[] = $row;
    }

    echo json_encode($addresses);
} else {
    http_response_code(400);
    echo json_encode(['message' => 'user_id is required']);
}
?>