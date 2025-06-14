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

            <li class="nav-item active">
                <a class="nav-link" href="order.php">
                    <i class="fas fa-solid fa-newspaper"></i>
                    <span>Order</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider" />

            <!-- Heading -->
            <div class="sidebar-heading">User Management</div>
            <li class="nav-item">
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
                        <h1 class="h3 mb-0 text-white">Order in Kla Computer</h1>
                    </div>
                    <!-- table -->
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="dataTable" class="table table-bordered table-hover text-center">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Order Number</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>Final Price</th>
                                            <th>Payment Info</th>
                                            <th>Order Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT orders.*, user.email, ship_address.no_tlp, ship_address.address 
                                                FROM orders 
                                                INNER JOIN user ON orders.user_id = user.id
                                                INNER JOIN ship_address ON orders.ship_address_id = ship_address.id
                                                ORDER BY orders.id DESC";
                                        $no = 1;
                                        foreach ($conn->query($sql) as $row):
                                            ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= htmlspecialchars($row['order_number']) ?></td>
                                                <td><?= htmlspecialchars($row['email']) ?></td>
                                                <td><?= htmlspecialchars($row['no_tlp']) ?></td>
                                                <td><?= htmlspecialchars($row['address']) ?></td>
                                                <td>Rp<?= number_format($row['final_price'], 0, ',', '.') ?></td>
                                                <td>
                                                    <?php
                                                    $paymentStatus = $row['payment_status'];
                                                    $colorClass = '';
                                                    if ($paymentStatus == 'Paid') {
                                                        $colorClass = 'badge badge-success';
                                                    } elseif ($paymentStatus == 'Unpaid') {
                                                        $colorClass = 'badge badge-danger';
                                                    } else {
                                                        $colorClass = 'badge badge-secondary';
                                                    }
                                                    ?>
                                                    <span
                                                        class="<?= $colorClass ?>"><?= htmlspecialchars($paymentStatus) ?></span>
                                                    <a href="#" class="show-payment icon btn-icon-split"
                                                        data-id="<?= htmlspecialchars($row['order_number']) ?>"
                                                        data-payment_method="<?= htmlspecialchars($row['payment_method']) ?>"
                                                        data-payment_status="<?= htmlspecialchars($row['payment_status']) ?>"
                                                        data-proof_transfer="<?= htmlspecialchars($row['proof_transfer']) ?>"
                                                        data-toggle="tooltip"
                                                        title="<?= htmlspecialchars($row['payment_method']) ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?php
                                                    $status = $row['order_status'];
                                                    $colorClass = '';
                                                    if ($status == 'Shipped' || $status == 'Delivered' || $status == 'Completed') {
                                                        $colorClass = 'badge badge-success';
                                                    } elseif ($status == 'Pending') {
                                                        $colorClass = 'badge badge-warning';
                                                    } elseif ($status == 'Cancelled' || $status == 'Failed') {
                                                        $colorClass = 'badge badge-danger';
                                                    } elseif ($status == 'Processing') {
                                                        $colorClass = 'badge badge-info';
                                                    } else {
                                                        $colorClass = 'badge badge-secondary';
                                                    }
                                                    ?>
                                                    <span class="<?= $colorClass ?>"><?= htmlspecialchars($status) ?></span>
                                                    <a href="#" class="edit-btn"
                                                        data-id="<?= htmlspecialchars($row['order_number']) ?>"
                                                        data-order_status="<?= htmlspecialchars($row['order_status']) ?>">
                                                        <i class="fa fa-pen"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="#" class="show-detail btn btn-primary btn-icon-split"
                                                        data-toggle="modal" data-target="#detailModal"
                                                        data-order-id="<?= $row['id'] ?>">
                                                        <span class="icon text-white-50">
                                                            <i class="fas fa-eye"></i>
                                                        </span>
                                                        <span class="text">Detail</span>
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

                    <!-- Update Modal -->
                    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog"
                        aria-labelledby="updateModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form id="updateForm" method="POST">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-white text-white">Update Order Status</h5>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">x</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id" id="update-id">
                                        <div class="form-group">
                                            <label for="update-status">Order Status</label>
                                            <select class="form-control" id="update-status" name="order_status"
                                                required>
                                                <option value="">-- Pilih Status --</option>
                                                <option value="Pending">Pending</option>
                                                <option value="Processing">Processing</option>
                                                <option value="Shipped">Shipped</option>
                                                <option value="Delivered">Delivered</option>
                                                <option value="Cancelled">Cancelled</option>
                                                <option value="Completed">Completed</option>
                                                <option value="Failed">Failed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Update Status</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- End of Update Modal -->

                    <!-- Show Payment Modal -->
                    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog"
                        aria-labelledby="detailModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="detailModalLabel">Order Details</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="order-detail-content">
                                    <!-- Content will be loaded via AJAX -->
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Show Payment Modal -->

                    <!-- Handle form submission for updating order status -->
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['order_status'])) {
                        $id = $_POST['id'];
                        $order_status = $_POST['order_status'];

                        $sql = "UPDATE orders SET order_status = ? WHERE order_number = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ss", $order_status, $id);

                        if ($stmt->execute()) {
                            echo "<script>
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: 'Status order berhasil diperbarui.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        window.location.href = 'order.php';
                                    });
                                </script>";
                        } else {
                            echo "<script>
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: 'Status order gagal diperbarui.',
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

        <!-- End Content Wrapper -->
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

    <script>
        $(document).ready(function () {
            if ($.fn.DataTable.isDataTable('#dataTable')) {
                $('#dataTable').DataTable().destroy();
            }
            // Initialize DataTable
            var table = $('#dataTable').DataTable({
                columnDefs: [
                    { width: '5%', targets: 0 },
                    { width: '10%', targets: 1 },
                    { width: '10%', targets: 2 },
                    { width: '10%', targets: 3 },
                    { width: '15%', targets: 4 },
                    { width: '10%', targets: 5 },
                    { width: '5%', targets: 6 },
                    { width: '5%', targets: 7 },
                    { width: '5%', targets: 8 }
                ],
                responsive: true
            });

            // Edit button handler
            $(".edit-btn").on("click", function () {
                var id = $(this).data("id");
                var status = $(this).data("order_status");

                $("#update-id").val(id);
                $("#update-status").val(status);
                $("#updateModal").modal("show");
            });

            // Payment info handler
            $(".show-payment").on("click", function (e) {
                e.preventDefault();
                var orderNumber = $(this).data("id");
                var paymentMethod = $(this).data("payment_method");
                var paymentStatus = $(this).data("payment_status");
                var proofTransfer = $(this).data("proof_transfer");

                Swal.fire({
                    title: 'Payment Information',
                    html: `
                <div class="text-left">
                    <p><strong>Order Number:</strong> ${orderNumber}</p>
                    <p><strong>Payment Method:</strong> ${paymentMethod}</p>
                    <p><strong>Payment Status:</strong> ${paymentStatus}</p>
                    ${proofTransfer ? `<img src="../images/proofs/${proofTransfer}" alt="Proof of Transfer" class="img-fluid mt-3">` : '<p>No proof of transfer available</p>'}
                </div>
            `,
                    showCloseButton: true,
                    showCancelButton: false,
                    focusConfirm: false,
                    confirmButtonText: 'Close',
                    width: '800px'
                });
            });

            // Order detail handler
            $('.show-detail').on('click', function (e) {
                e.preventDefault();
                var orderId = $(this).data('order-id');
                console.log("Fetching details for order ID:", orderId);

                $('#order-detail-content').html('<div class="text-center p-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading order details...</p></div>');

                $.ajax({
                    url: 'get_order_details.php',
                    type: 'GET',
                    data: { order_id: orderId },
                    dataType: 'html',
                    success: function (response) {
                        console.log("Response received:", response);
                        $('#order-detail-content').html(response);
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        $('#order-detail-content').html('<div class="alert alert-danger">Failed to load order details. Please try again.</div>');
                    }
                });
            });
        });
    </script>

</body>

</html>