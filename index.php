<?php
include "koneksimysql.php";
header('Content-Type: text/html; charset=utf-8');

// Create
if (isset($_POST['tambah'])) {
    $kode = $_POST['kode'];
    $merk = $_POST['merk'];
    $kategori = $_POST['kategori'];
    $satuan = $_POST['satuan'];
    $hargabeli = $_POST['hargabeli'];
    $diskonbeli = $_POST['diskonbeli'] ?: 0;
    $hargapokok = $_POST['hargapokok'];
    $hargajual = $_POST['hargajual'];
    $diskonjual = $_POST['diskonjual'] ?: 0;
    $stok = $_POST['stok'];
    $deskripsi = $_POST['deskripsi'];
    $view = $_POST['view'];

    $filename = '';
    if ($_FILES['foto']['name']) {
        $filename = $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "../images/product/" . $filename);
    }

    $conn->query("INSERT INTO product VALUES (
        '$kode', '$merk', '$kategori', '$satuan',
        $hargabeli, $diskonbeli, $hargapokok,
        $hargajual, $diskonjual, $stok,
        '$filename', '$deskripsi', $view
    )");
    header("Location: index.php");
}

// Update
if (isset($_POST['update'])) {
    $kode = $_POST['kode'];
    $merk = $_POST['merk'];
    $kategori = $_POST['kategori'];
    $satuan = $_POST['satuan'];
    $hargabeli = $_POST['hargabeli'];
    $diskonbeli = $_POST['diskonbeli'] ?: 0;
    $hargapokok = $_POST['hargapokok'];
    $hargajual = $_POST['hargajual'];
    $diskonjual = $_POST['diskonjual'] ?: 0;
    $stok = $_POST['stok'];
    $deskripsi = $_POST['deskripsi'];
    $view = $_POST['view'];

    $foto = $_POST['old_foto'];
    if ($_FILES['foto']['name']) {
        $foto = $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "../images/product/" . $foto);
    }

    $conn->query("UPDATE product SET
        merk='$merk', kategori='$kategori', satuan='$satuan',
        hargabeli=$hargabeli, diskonbeli=$diskonbeli,
        hargapokok=$hargapokok, hargajual=$hargajual,
        diskonjual=$diskonjual, stok=$stok,
        foto='$foto', deskripsi='$deskripsi', view=$view
        WHERE kode='$kode'");
    header("Location: index.php");
}

// Delete
if (isset($_GET['hapus'])) {
    $kode = $_GET['hapus'];
    $conn->query("DELETE FROM product WHERE kode='$kode'");
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>CRUD Produk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="container py-4">

    <h2>Data Produk</h2>

    <!-- Form Tambah -->
    <form method="POST" enctype="multipart/form-data" class="mb-4">
        <div class="row g-2">
            <div class="col"><input class="form-control" name="kode" placeholder="Kode" required></div>
            <div class="col"><input class="form-control" name="merk" placeholder="Merk" required></div>
            <div class="col"><input class="form-control" name="kategori" placeholder="Kategori" required></div>
            <div class="col"><input class="form-control" name="satuan" placeholder="Satuan" required></div>
        </div>
        <div class="row g-2 mt-2">
            <div class="col"><input class="form-control" type="number" step="0.01" name="hargabeli"
                    placeholder="Harga Beli" required></div>
            <div class="col"><input class="form-control" type="number" step="0.01" name="diskonbeli"
                    placeholder="Diskon Beli"></div>
            <div class="col"><input class="form-control" type="number" step="0.01" name="hargapokok"
                    placeholder="Harga Pokok" required></div>
        </div>
        <div class="row g-2 mt-2">
            <div class="col"><input class="form-control" type="number" step="0.01" name="hargajual"
                    placeholder="Harga Jual" required></div>
            <div class="col"><input class="form-control" type="number" step="0.01" name="diskonjual"
                    placeholder="Diskon Jual"></div>
            <div class="col"><input class="form-control" type="number" name="stok" placeholder="Stok" required></div>
        </div>
        <div class="mt-2">
            <input class="form-control" type="file" name="foto">
            <textarea class="form-control mt-2" name="deskripsi" placeholder="Deskripsi"></textarea>
            <input class="form-control mt-2" type="number" name="view" placeholder="View" required>
        </div>
        <button class="btn btn-primary mt-3" name="tambah">Tambah Produk</button>
    </form>

    <!-- Tabel Produk -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Merk</th>
                <th>Kategori</th>
                <th>Satuan</th>
                <th>Harga Beli</th>
                <th>Diskon Beli</th>
                <th>Harga Pokok</th>
                <th>Harga Jual</th>
                <th>Diskon Jual</th>
                <th>Stok</th>
                <th>Foto</th>
                <th>Deskripsi</th>
                <th>View</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $produk = $conn->query("SELECT * FROM product");
            while ($row = $produk->fetch_assoc()):
                ?>
                <tr>
                    <?php foreach ($row as $k => $v): ?>
                        <?php if ($k == 'foto'): ?>
                            <td><img src="../images/product/<?= $v ?>" width="60"></td>
                        <?php else: ?>
                            <td><?= htmlspecialchars($v) ?></td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                            data-bs-target="#editModal<?= $row['kode'] ?>">Edit</button>
                        <a class="btn btn-sm btn-danger" href="?hapus=<?= $row['kode'] ?>"
                            onclick="return confirm('Yakin?')">Hapus</a>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="editModal<?= $row['kode'] ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Produk</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <?php foreach ($row as $key => $val): ?>
                                        <?php if ($key == 'foto'): ?>
                                            <input type="hidden" name="old_foto" value="<?= $val ?>">
                                            <label>Foto Baru</label><input type="file" name="foto" class="form-control mb-2">
                                            <img src="images/product/<?= $val ?>" width="80"><br><br>
                                        <?php elseif ($key == 'deskripsi'): ?>
                                            <textarea class="form-control mb-2" name="<?= $key ?>"><?= $val ?></textarea>
                                        <?php elseif ($key == 'kode'): ?>
                                            <input type="hidden" name="kode" value="<?= $val ?>">
                                        <?php else: ?>
                                            <input class="form-control mb-2" name="<?= $key ?>" value="<?= $val ?>">
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" name="update">Simpan</button>
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            <?php endwhile; ?>
        </tbody>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>