<?php
include "koneksimysql.php";
header('Content-Type: application/json');

$email = $_POST['email'];
$password = md5($_POST['password']);
$datauser = array();
$getstatus = 0;

$sql = "SELECT * FROM user WHERE email='" . $email . "' AND password='" . $password . "'";
$hasil = mysqli_query($conn, $sql);
$data = mysqli_fetch_object($hasil);

if (!$data) {
    $getstatus = 0;
} else {
    $getstatus = 1;
    $datauser = array(
        'username' => $data->username,
        'nama' => $data->nama,
        'password' => $data->password,
        'email' => $data->email,
        'foto' => $data->foto,
    );
}

echo json_encode(array('result' => $getstatus, 'data' => $datauser));
?>