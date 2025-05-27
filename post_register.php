<?php
include "koneksimysql.php";
header('Content-Type: application/json');

$email = $_POST['email'];
$nama = $_POST['nama'];
$username = $_POST['username'];
$password = $_POST['password'];

$getstatus = 0;
$getresult = 0;
$message = "";


$sql = "select * from user where email = '$email'";
$hasil = mysqli_query($conn, $sql);
if ($hasil = mysqli_fetch_object($hasil)) {
    $getstatus = 0;
    $message = "User Sudah Ada";
} else {
    $getstatus = 1;
    $sql = "insert into user(nama,email,username,password) values('$nama','$email','$username',md5('$password'))";
    $hasil = mysqli_query($conn, $sql);
    if ($hasil) {
        $getresult = 1;
        $message = "Simpan Berhasil";
    } else {
        $getresult = 0;
        $message = "Simpan Gagal : " . mysqli_error($conn);
    }
}

echo json_encode(array('status' => $getstatus, 'result' => $getresult, 'message' => $message));
?>