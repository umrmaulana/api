<?php
include "koneksimysql.php";
header('Content-Type: application/json');

$email = $_POST['email'];
$nama = $_POST['nama'];
$alamat = $_POST['alamat'];
$kota = $_POST['kota'];
$provinsi = $_POST['provinsi'];
$kodepos = $_POST['kodepos'];
$telp = $_POST['telp'];
$username = $_POST['username'];

$getstatus = 0;
$message = "";

$sql = "update user set email='" . $email . "',  nama='" . $nama . "', alamat='" . $alamat . "', kota='" . $kota . "', provinsi='" . $provinsi . "', kodepos='" . $kodepos . "', telp='" . $telp . "' where username='" . $username . "'";
$hasil = mysqli_query($conn, $sql);
if ($hasil) {
    $getstatus = 1;
    $message = "Data berhasil diupdate";
} else {
    $message = "Data gagal diupdate";
}

echo json_encode(array('result' => $getstatus, 'message' => $message));
?>