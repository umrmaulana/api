<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
include 'koneksimysql.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Admin - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet" />

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet" />

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" />

    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon" />

    <!-- alert library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-card-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon">
                    <img src="images/logo.png" alt="" style="width:30px">
                </div>
                <div class="sidebar-brand-text mx-2">Kla Computer</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0" />

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider" />

            <!-- Heading -->
            <div class="sidebar-heading">Product</div>
            <li class="nav-item">
                <a class="nav-link" href="product.php">
                    <i class="fas fa-solid fa-list-ol"></i>
                    <span>Product</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider" />

            <!-- Heading -->
            <div class="sidebar-heading">Order</div>

            <li class="nav-item">
                <a class="nav-link" href="order.php">
                    <i class="fas fa-solid fa-newspaper"></i>
                    <span>Order</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider" />

            <!-- Heading -->
            <div class="sidebar-heading">User Management</div>
            <li class="nav-item active">
                <a class="nav-link" href="user.php">
                    <i class="fas fa-fw fa-user"></i>
                    <span>User</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block" />

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow">
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php echo $_SESSION['nama'] ?> </span>
                                <img class="img-profile rounded-circle"
                                    src="../images/avatar/<?php echo $_SESSION['foto'] ?>" />
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="logout.php" data-toggle="modal"
                                    data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-white">Daftar User</h1>
                    </div>
                    <!-- table -->
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="text-right py-2">
                                <a href="#" class="add-btn"><button class="btn btn-primary text-align-end">Tambah
                                        Baru</button>
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table id="dataTable" class="table table-bordered table-hover text-center">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Email</th>
                                            <th>Username</th>
                                            <th>Nama</th>
                                            <th>Kota</th>
                                            <th>Telepon</th>
                                            <th>Foto</th>
                                            <th>Role</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $hasil = "SELECT * FROM user";
                                        $no = 1;
                                        foreach ($conn->query($hasil) as $row)
                                        : ?>
                                            <tr>
                                                <td><?php echo $no++; ?></td>
                                                <td><?php echo $row['email']; ?></td>
                                                <td><?php echo $row['username']; ?></td>
                                                <td><?php echo $row['nama']; ?></td>
                                                <td><?php echo $row['kota']; ?></td>
                                                <td><?php echo $row['telp']; ?></td>
                                                <td>
                                                    <img src="../images/avatar/<?php echo $row['foto']; ?>"
                                                        style="width: 60px;">
                                                </td>
                                                <td><?php echo $row['role']; ?></td>
                                                <td>
                                                    <a href="#" class="edit-btn" data-id="<?php echo $row['id']; ?>"
                                                        data-nama="<?php echo $row['nama']; ?>"
                                                        data-username="<?php echo $row['username']; ?>"
                                                        data-email="<?php echo $row['email']; ?>"
                                                        data-kota="<?php echo $row['kota']; ?>"
                                                        data-telp="<?php echo $row['telp']; ?>"
                                                        data-foto="<?php echo $row['foto']; ?>"
                                                        data-role="<?php echo $row['role']; ?>">
                                                        <li class="fa fa-solid fa-pen"></li>
                                                        <br>
                                                        <span>edit</span>
                                                    </a>
                                                    <hr>
                                                    <a href="#" style="color: #e74a3b" class="delete-link"
                                                        data-id="<?php echo $row['id']; ?>">
                                                        <li class="fa fa-solid fa-trash"></li>
                                                        <br>
                                                        <span>Hapus</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- end table -->
                    <!-- End of Main Content -->

                    <!-- Add Modal -->
                    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-white" id="addModalLabel">Tambah User Baru</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">x</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="addForm" action="" method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="nama">Nama</label>
                                            <input type="text" class="form-control" id="nama" name="nama" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <input type="text" class="form-control" id="username" name="username"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="kota">Kota</label>
                                            <input type="text" class="form-control" id="kota" name="kota">
                                        </div>
                                        <div class="form-group">
                                            <label for="telp">Telepon</label>
                                            <input type="text" class="form-control" id="telp" name="telp">
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="role">Role</label>
                                            <select class="form-control" id="role" name="role" required>
                                                <option value="user">User</option>
                                                <option value="admin">Admin</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="foto">Foto</label>
                                            <input type="file" class="form-control" id="foto" name="foto">
                                            <img id="preview-foto-upload" src="../images/avatar/default.jpg" width="80"
                                                class="mt-2 mb-3"><br>
                                        </div>
                                        <button type="submit" class="btn btn-primary" name="add">Tambah</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Add Modal -->

                    <!-- Update Modal -->
                    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog"
                        aria-labelledby="updateModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-white" id="updateModalLabel">Update User</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">x</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="updateForm" action="" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="id" id="update-id">
                                        <div class="form-group">
                                            <label for="update-nama">Nama</label>
                                            <input type="text" class="form-control" id="update-nama" name="nama"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="update-username">Username</label>
                                            <input type="text" class="form-control" id="update-username" name="username"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="update-email">Email</label>
                                            <input type="email" class="form-control" id="update-email" name="email"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="update-kota">Kota</label>
                                            <input type="text" class="form-control" id="update-kota" name="kota">
                                        </div>
                                        <div class="form-group">
                                            <label for="update-telp">Telepon</label>
                                            <input type="text" class="form-control" id="update-telp" name="telp">
                                        </div>
                                        <div class="form-group">
                                            <label for="update-role">Role</label>
                                            <select class="form-control" id="update-role" name="role" required>
                                                <option value="user">User</option>
                                                <option value="admin">Admin</option>
                                            </select>
                                        </div>
                                        <img id="preview-foto" src="../images/avatar/default.jpg" width="80"
                                            class="mt-2 mb-3"><br>
                                        <div class="form-group">
                                            <input type="file" class="form-control" id="update-foto" name="foto">
                                        </div>
                                        <button type="submit" class="btn btn-primary" name="update">Update</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Update Modal -->

                    <?php
                    // handle to add
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
                        $nama = $_POST['nama'];
                        $username = $_POST['username'];
                        $email = $_POST['email'];
                        $kota = $_POST['kota'];
                        $telp = $_POST['telp'];
                        $password = md5($_POST['password']);
                        $role = $_POST['role'];

                        // Check if username or email already exists
                        $check_sql = "SELECT * FROM user WHERE username = ? OR email = ?";
                        $check_stmt = $conn->prepare($check_sql);
                        $check_stmt->bind_param("ss", $username, $email);
                        $check_stmt->execute();
                        $result = $check_stmt->get_result();

                        if ($result->num_rows > 0) {
                            $error_message = "";
                            while ($row = $result->fetch_assoc()) {
                                if ($row['username'] == $username) {
                                    $error_message = "Username sudah digunakan!";
                                }
                                if ($row['email'] == $email) {
                                    $error_message .= ($error_message ? " dan " : "") . "Email sudah digunakan!";
                                }
                            }

                            echo "<script>
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: '$error_message',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                </script>";
                        } else {
                            // Upload foto
                            $foto = $_FILES['foto']['name'];
                            $tmp = $_FILES['foto']['tmp_name'];
                            $path = "../images/avatar/" . $foto;

                            // Default foto if not uploaded
                            if (empty($foto)) {
                                $foto = "default.jpg";
                            } else {
                                move_uploaded_file($tmp, $path);
                            }

                            $sql = "INSERT INTO user (nama, username, email, kota, telp, password, foto, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("ssssssss", $nama, $username, $email, $kota, $telp, $password, $foto, $role);

                            if ($stmt->execute()) {
                                echo "<script>
                                        Swal.fire({
                                            title: 'Berhasil!',
                                            text: 'User berhasil ditambahkan.',
                                            icon: 'success',
                                            confirmButtonText: 'OK'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location.href = 'user.php';
                                            }
                                        });
                                    </script>";
                            } else {
                                echo "<script>
                                        Swal.fire({
                                            title: 'Gagal!',
                                            text: 'Gagal menambahkan user.',
                                            icon: 'error',
                                            confirmButtonText: 'OK'
                                        });
                                    </script>";
                            }
                        }
                    }

                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
                        $id = $_POST['id'];
                        $nama = $_POST['nama'];
                        $username = $_POST['username'];
                        $email = $_POST['email'];
                        $kota = $_POST['kota'];
                        $telp = $_POST['telp'];
                        $role = $_POST['role'];

                        // Check if username or email already exists (excluding current user)
                        $check_sql = "SELECT * FROM user WHERE (username = ? OR email = ?) AND id != ?";
                        $check_stmt = $conn->prepare($check_sql);
                        $check_stmt->bind_param("ssi", $username, $email, $id);
                        $check_stmt->execute();
                        $result = $check_stmt->get_result();

                        if ($result->num_rows > 0) {
                            $error_message = "";
                            while ($row = $result->fetch_assoc()) {
                                if ($row['username'] == $username) {
                                    $error_message = "Username sudah digunakan!";
                                }
                                if ($row['email'] == $email) {
                                    $error_message .= ($error_message ? " dan " : "") . "Email sudah digunakan!";
                                }
                            }

                            echo "<script>
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: '$error_message',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                </script>";
                        } else {
                            // Upload foto
                            $foto = $_FILES['foto']['name'];
                            $tmp = $_FILES['foto']['tmp_name'];
                            $path = "../images/avatar/" . $foto;

                            if (!empty($foto)) {
                                move_uploaded_file($tmp, $path);
                                $sql = "UPDATE user SET nama=?, username=?, email=?, kota=?, telp=?, foto=?, role=? WHERE id=?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("sssssssi", $nama, $username, $email, $kota, $telp, $foto, $role, $id);
                            } else {
                                $sql = "UPDATE user SET nama=?, username=?, email=?, kota=?, telp=?, role=? WHERE id=?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("ssssssi", $nama, $username, $email, $kota, $telp, $role, $id);
                            }

                            if ($stmt->execute()) {
                                echo "<script>
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: 'User berhasil diupdate.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = 'user.php';
                                        }
                                    });
                                </script>";
                            } else {
                                echo "<script>
                                        Swal.fire({
                                            title: 'Gagal!',
                                            text: 'Gagal mengupdate user.',
                                            icon: 'error',
                                            confirmButtonText: 'OK'
                                        });
                                    </script>";
                            }
                        }
                    }

                    // handle to delete
                    if (isset($_GET['delete_id'])) {
                        $id = $_GET['delete_id'];

                        $sql = "DELETE FROM user WHERE id = ?";
                        $stmt = $conn->prepare($sql);

                        if ($stmt) {
                            $stmt->bind_param("i", $id);

                            if ($stmt->execute()) {
                                echo "<script>
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: 'User berhasil dihapus.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = 'user.php';
                                        }
                                    });
                                </script>";
                            } else {
                                echo "<script>
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: 'User gagal dihapus.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                </script>";
                            }
                        } else {
                            echo "<script>
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: 'Gagal mempersiapkan statement.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                        }
                    }
                    ?>

                    <!-- Footer -->
                    <footer class="sticky-footer">
                        <div class="container my-auto">
                            <div class="copyright text-center my-auto">
                                <span>&copy; 2025 Powered By Umar Maulana</span>
                            </div>
                        </div>
                    </footer>
                    <!-- End of Footer -->
                    <!-- End of Content Wrapper -->
                </div>
            </div>
        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
    </div>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                </div>
                <div class="modal-body">
                    Pilih "Keluar" untuk mengakhiri sesi ini!.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">
                        Cancel
                    </button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

    <!-- script modal -->
    <script>
        if ($.fn.DataTable.isDataTable('#dataTable')) {
            $('#dataTable').DataTable().destroy();
        }
        $(document).ready(function () {
            function bindEditButtons() {
                $(".edit-btn").on("click", function () {
                    var id = $(this).data("id");
                    var nama = $(this).data("nama");
                    var username = $(this).data("username");
                    var email = $(this).data("email");
                    var kota = $(this).data("kota");
                    var telp = $(this).data("telp");
                    var foto = $(this).data("foto");
                    var role = $(this).data("role");

                    $("#preview-foto").attr("src", "../images/avatar/" + foto);
                    $("#update-id").val(id);
                    $("#update-nama").val(nama);
                    $("#update-username").val(username);
                    $("#update-email").val(email);
                    $("#update-kota").val(kota);
                    $("#update-telp").val(telp);
                    $("#update-role").val(role);

                    $("#update-foto").on("change", function () {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $("#preview-foto").attr("src", e.target.result);
                        };
                        reader.readAsDataURL(this.files[0]);
                    });

                    $("#updateModal").modal("show");
                });
            }
            function bindAddButtons() {
                $(".add-btn").on("click", function () {
                    $("#addModal").modal("show");
                });
            }
            function bindDelButtons() {
                $(".delete-link").on("click", function (event) {
                    event.preventDefault();
                    var id = $(this).data("id");

                    Swal.fire({
                        title: "Apakah Anda yakin?",
                        text: "User akan dihapus permanen.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, hapus!",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "?delete_id=" + id;
                        }
                    });
                });
            }
            function previewImage(input, previewElement) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $(previewElement).attr("src", e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#foto").on("change", function () {
                previewImage(this, "#preview-foto-upload");
            });
            $("#update-foto").on("change", function () {
                previewImage(this, "#preview-foto");
            });

            bindAddButtons();
            bindEditButtons();
            bindDelButtons();
            table.on('draw', function () {
                bindEditButtons();
                bindDelButtons();
            });
        });
    </script>

</body>

</html>