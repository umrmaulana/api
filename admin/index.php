<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
include 'koneksimysql.php';

// Get stats for dashboard
$total_products = $conn->query("SELECT COUNT(*) as total FROM product")->fetch_assoc()['total'];
$total_users = $conn->query("SELECT COUNT(*) as total FROM user")->fetch_assoc()['total'];
$total_orders = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];
$revenue = $conn->query("SELECT SUM(final_price) as total FROM orders WHERE order_status = 'completed'")->fetch_assoc()['total'] ?? 0;

// Get recent orders
$recent_orders = $conn->query("SELECT o.*, u.nama as customer_name 
                              FROM orders o 
                              JOIN user u ON o.user_id = u.id 
                              ORDER BY o.created_at DESC LIMIT 5");

// Get product categories
$categories = $conn->query("SELECT kategori, COUNT(*) as count FROM product GROUP BY kategori");
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
            <li class="nav-item active">
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
                                <span class="mr-2 d-none d-lg-inline text-white medium">
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
                        <h1 class="h3 mb-0 text-white">Dashboard Overview</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Products Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body stat-card">
                                    <div class="stat-value"><?php echo $total_products; ?></div>
                                    <div class="stat-label">TOTAL PRODUCTS</div>
                                    <i class="fas fa-boxes fa-2x mt-3" style="color: rgba(255,255,255,0.2)"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Users Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body stat-card">
                                    <div class="stat-value"><?php echo $total_users; ?></div>
                                    <div class="stat-label">TOTAL USERS</div>
                                    <i class="fas fa-users fa-2x mt-3" style="color: rgba(255,255,255,0.2)"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Orders Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body stat-card">
                                    <div class="stat-value"><?php echo $total_orders; ?></div>
                                    <div class="stat-label">TOTAL ORDERS</div>
                                    <i class="fas fa-shopping-cart fa-2x mt-3" style="color: rgba(255,255,255,0.2)"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Revenue Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body stat-card">
                                    <div class="stat-value">Rp<?php echo number_format($revenue, 0, ',', '.'); ?></div>
                                    <div class="stat-label">TOTAL REVENUE</div>
                                    <i class="fas fa-dollar-sign fa-2x mt-3" style="color: rgba(255,255,255,0.2)"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-white">
                                        Monthly Sales Overview
                                    </h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Options:</div>
                                            <a class="dropdown-item" href="#">View Details</a>
                                            <a class="dropdown-item" href="#">Export Data</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-white">
                                        Product Categories
                                    </h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Options:</div>
                                            <a class="dropdown-item" href="#">View Details</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <?php while ($category = $categories->fetch_assoc()): ?>
                                            <span class="mr-2">
                                                <i class="fas fa-circle"
                                                    style="color: <?php echo sprintf("#%06X", mt_rand(0, 0xFFFFFF)); ?>"></i>
                                                <?php echo $category['kategori']; ?>
                                            </span>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow mb-4">
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-white">Recent Orders</h6>
                                    <a href="order.php" class="btn btn-sm btn-primary">View All</a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <thead>
                                                <tr>
                                                    <th>Order #</th>
                                                    <th>Customer</th>
                                                    <th>Date</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($order = $recent_orders->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?php echo $order['order_number']; ?></td>
                                                        <td><?php echo $order['customer_name']; ?></td>
                                                        <td><?php echo date('d M Y', strtotime($order['created_at'])); ?>
                                                        </td>
                                                        <td>Rp<?php echo number_format($order['final_price'], 0, ',', '.'); ?>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-<?php
                                                            switch ($order['order_status']) {
                                                                case 'completed':
                                                                    echo 'success';
                                                                    break;
                                                                case 'processing':
                                                                    echo 'primary';
                                                                    break;
                                                                case 'pending':
                                                                    echo 'warning';
                                                                    break;
                                                                case 'cancelled':
                                                                    echo 'danger';
                                                                    break;
                                                                default:
                                                                    echo 'secondary';
                                                            }
                                                            ?>">
                                                                <?php echo ucfirst($order['order_status']); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="order_detail.php?id=<?php echo $order['id']; ?>"
                                                                class="btn btn-sm btn-primary">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>&copy; 2025 Powered By Umar Maulana</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background: rgba(72, 25, 105, 0.9); backdrop-filter: blur(10px);">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body text-white">
                    Pilih "Keluar" untuk mengakhiri sesi ini!.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
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
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
        // Area Chart Example with Real Data
        var ctx = document.getElementById("myAreaChart");
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Revenue",
                    lineTension: 0.3,
                    backgroundColor: "rgba(126, 59, 223, 0.05)",
                    borderColor: "rgba(126, 59, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(126, 59, 223, 1)",
                    pointBorderColor: "rgba(126, 59, 223, 1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(126, 59, 223, 1)",
                    pointHoverBorderColor: "rgba(126, 59, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: [
                        <?php
                        // Get monthly revenue data
                        $monthlyRevenue = array_fill(0, 12, 0); // Initialize array for 12 months
                        
                        $revenueQuery = $conn->query("
                    SELECT 
                        MONTH(created_at) as month, 
                        SUM(final_price) as total 
                    FROM orders 
                    WHERE order_status = 'completed' 
                    AND YEAR(created_at) = YEAR(CURDATE())
                    GROUP BY MONTH(created_at)
                ");

                        while ($row = $revenueQuery->fetch_assoc()) {
                            $monthlyRevenue[$row['month'] - 1] = $row['total']; // Months are 1-12, array is 0-11
                        }

                        echo implode(', ', $monthlyRevenue);
                        ?>
                    ],
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            fontColor: "rgba(255,255,255,0.7)"
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            fontColor: "rgba(255,255,255,0.7)",
                            padding: 10,
                            callback: function (value, index, values) {
                                return 'Rp' + value.toLocaleString();
                            }
                        },
                        gridLines: {
                            color: "rgba(255,255,255,0.05)",
                            zeroLineColor: "rgba(255,255,255,0.1)"
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "rgba(72, 25, 105, 0.9)",
                    bodyFontColor: "#fff",
                    titleMarginBottom: 10,
                    titleFontColor: '#fff',
                    titleFontSize: 14,
                    borderColor: 'rgba(255,255,255,0.1)',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function (tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': Rp' + tooltipItem.yLabel.toLocaleString();
                        }
                    }
                }
            }
        });

        // Pie Chart Example
        var ctx2 = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: [
                    <?php
                    $categories->data_seek(0); // Reset pointer
                    while ($category = $categories->fetch_assoc()):
                        echo "'" . $category['kategori'] . "',";
                    endwhile;
                    ?>
                ],
                datasets: [{
                    data: [
                        <?php
                        $categories->data_seek(0); // Reset pointer
                        while ($category = $categories->fetch_assoc()):
                            echo $category['count'] . ",";
                        endwhile;
                        ?>
                    ],
                    backgroundColor: [
                        <?php
                        $categories->data_seek(0); // Reset pointer
                        while ($category = $categories->fetch_assoc()):
                            echo "'" . sprintf("#%06X", mt_rand(0, 0xFFFFFF)) . "',";
                        endwhile;
                        ?>
                    ],
                    borderColor: "rgba(255,255,255,0.1)",
                    hoverBorderColor: "rgba(255,255,255,0.3)"
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgba(72, 25, 105, 0.9)",
                    bodyFontColor: "#fff",
                    borderColor: 'rgba(255,255,255,0.1)',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 70,
            },
        });
    </script>
</body>

</html>
<?php $conn->close(); ?>