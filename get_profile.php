<?php
include "koneksimysql.php";
header('Content-Type: application/json');

$username = $_GET['username'];
$datauser = array();
$getstatus = 0;

$sql = "select * from user where username='" . $username . "'";
$hasil = mysqli_query($conn, $sql);
$data = mysqli_fetch_object($hasil);
if ($data) {
    $getstatus = 1;
    $datauser = array(
        'username' => $data->username,
        'nama' => $data->nama,
        'alamat' => $data->alamat,
        'kota' => $data->kota,
        'provinsi' => $data->provinsi,
        'kodepos' => $data->kodepos,
        'telp' => $data->telp,
        'email' => $data->email,
        'foto' => $data->foto,
    );
}

echo json_encode(array('result' => $getstatus, 'data' => $datauser));
?>