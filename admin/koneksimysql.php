<?php
define('host', 'localhost');
// define('user', 'android');
// define('password', 'Maulana1');
// define('database', 'android');
define('user', 'root');
define('password', '');
define('database', 'androiduts');

$conn = mysqli_connect(host, user, password, database);
if (!$conn) {
    echo "Koneksi Gagal : " . mysqli_connect_error();
    exit();
}
?>