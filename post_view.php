<?php
include "koneksimysql.php";
header('Content-Type: application/json');

$kode = $_POST['kode'];
$view = $_POST['view'];

$sql = "UPDATE product SET view = '$view' WHERE kode = '$kode'";
$hasil = mysqli_query($conn, $sql);
if ($hasil) {
    $getstatus = 1;
    $message = "Data berhasil diupdate";
} else {
    $message = "Data gagal diupdate";
}

echo json_encode(array('result' => $getstatus, 'message' => $message));
?>