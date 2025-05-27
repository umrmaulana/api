<?php
include "koneksimysql.php";
header('Content-Type: application/json');

$sql = "SELECT DISTINCT kategori FROM product ORDER BY kategori ASC";
$hasil = mysqli_query($conn, $sql);

$result = [];
while ($row = mysqli_fetch_assoc($hasil)) {
    $result[] = $row['kategori'];
}

echo json_encode(['categories' => $result]);
?>