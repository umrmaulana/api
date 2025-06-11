<?php
include "koneksimysql.php";
header('Content-Type: application/json');

$kategori = isset($_GET['kategori']) ? mysqli_real_escape_string($conn, $_GET['kategori']) : '';
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$kategori_all = isset($_GET['kategori_all']) ? mysqli_real_escape_string($conn, $_GET['kategori']) : 'all';

if ($kategori == 'all' && $search) {
    $sql = "SELECT * FROM product WHERE merk LIKE '%$search%' OR deskripsi LIKE '%$search%' OR kategori LIKE '%$search%' order by stok desc limit 15";
} elseif ($kategori == 'all') {
    $sql = "SELECT * FROM product order by stok desc limit 15";
} elseif ($kategori) {
    $sql = "SELECT * FROM product WHERE kategori = '$kategori' order by stok desc limit 15";
} elseif ($search) {
    $sql = "SELECT * FROM product WHERE merk LIKE '%$search%' OR deskripsi LIKE '%$search%' OR kategori LIKE '%$search%' order by stok desc limit 15";
} else {
    $sql = "SELECT * FROM product order by stok desc limit 15";
}

$hasil = mysqli_query($conn, $sql);

if (!$hasil) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Query gagal dijalankan: ' . mysqli_error($conn),
    ]);
    exit;
}

$result = [];
while ($data = mysqli_fetch_object($hasil)) {
    array_push($result, [
        'kode' => $data->kode,
        'merk' => $data->merk,
        'kategori' => $data->kategori,
        'satuan' => $data->satuan,
        'hargabeli' => $data->hargabeli,
        'diskonbeli' => $data->diskonbeli,
        'hargapokok' => $data->hargapokok,
        'hargajual' => $data->hargajual,
        'diskonjual' => $data->diskonjual,
        'stok' => $data->stok,
        'weight' => $data->weight,
        'foto' => $data->foto,
        'deskripsi' => $data->deskripsi,
        'view' => $data->view,
    ]);
}

echo json_encode(['result' => $result]);
?>