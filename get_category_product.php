<?php
include "koneksimysql.php";
header('Content-Type: application/json');

$kategori = $_GET['kategori'];

if ($kategori == 'all') {
    $sql = "SELECT * FROM product order by view desc";
} else {
    $sql = "SELECT * FROM product WHERE kategori = '$kategori' order by view desc";
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