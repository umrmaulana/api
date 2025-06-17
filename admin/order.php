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

<?php include 'includes/header.php'; ?>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <?php $page = 'order';
        include 'includes/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <?php include 'includes/topbar.php'; ?>

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
                    <?php include 'includes/footer.php'; ?>
                    <!-- End of Content Wrapper -->
                </div>
                <!-- End of Page Wrapper -->
            </div>
        </div>
        <!-- End Content Wrapper -->

        <!-- Scroll to Top Button-->
        <?php include 'includes/scroll-top.php'; ?>
    </div>

    <!-- Logout Modal-->
    <?php include 'includes/logout.php'; ?>

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
            function bindEditButtons() {
                $(".edit-btn").on("click", function () {
                    var id = $(this).data("id");
                    var status = $(this).data("order_status");

                    $("#update-id").val(id);
                    $("#update-status").val(status);
                    $("#updateModal").modal("show");
                });
            }

            // Payment info handler
            function bindPaymentInfoButtons() {
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
                                ${proofTransfer ? `<img src="${proofTransfer}" alt="Proof of Transfer" class="img-fluid mt-3">` : '<p>No proof of transfer available</p>'}
                            </div>
                        `,
                        showCloseButton: true,
                        showCancelButton: false,
                        focusConfirm: false,
                        confirmButtonText: 'Close',
                        width: '800px'
                    });
                });
            }

            // Order detail handler
            function bindOrderDetailButtons() {
                $(".show-detail").on("click", function (e) {
                    e.preventDefault();
                    var orderId = $(this).data("order-id");
                    console.log("Fetching details for order ID:", orderId);

                    $("#order-detail-content").html('<div class="text-center p-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading order details...</p></div>');

                    $.ajax({
                        url: 'get_order_details.php',
                        type: 'GET',
                        data: { order_id: orderId },
                        dataType: 'html',
                        success: function (response) {
                            console.log("Response received:", response);
                            $("#order-detail-content").html(response);
                        },
                        error: function (xhr, status, error) {
                            console.error("AJAX Error:", status, error);
                            $("#order-detail-content").html('<div class="alert alert-danger">Failed to load order details. Please try again.</div>');
                        }
                    });
                });
            }
            // Bind buttons after DataTable initialization
            bindEditButtons();
            bindPaymentInfoButtons();
            bindOrderDetailButtons();
            // Rebind buttons after table redraw
            table.on('draw', function () {
                bindEditButtons();
                bindPaymentInfoButtons();
                bindOrderDetailButtons();
            });
        });
    </script>

</body>

</html>