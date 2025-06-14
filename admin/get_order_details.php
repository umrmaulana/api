<?php
include 'koneksimysql.php';

// Debugging - log semua parameter yang diterima
error_log("Received GET parameters: " . print_r($_GET, true));

if (isset($_GET['order_id']) && is_numeric($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    error_log("Processing order ID: " . $order_id);

    // Query untuk mendapatkan detail order
    $stmt = $conn->prepare("
        SELECT od.*, p.*
        FROM order_details od, product p 
        WHERE od.product_id = p.kode AND od.order_id = ?
    ");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table class='table table-bordered'>";
        echo "<thead><tr><th>Image</th><th>Produk</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr></thead>";
        echo "<tbody>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td><img src='" . '../images/product/' . htmlspecialchars($row['foto']) . "' alt='Product Image' style='width: 50px; height: 50px;'></td>
                    <td>" . htmlspecialchars($row['product_name']) . "</td>
                    <td>" . $row['qty'] . "</td>
                    <td>Rp " . number_format($row['price'], 0, ',', '.') . "</td>
                    <td>Rp " . number_format($row['sub_total'], 0, ',', '.') . "</td>
                  </tr>";
        }

        echo "</tbody></table>";
    } else {
        // Tambahkan informasi order ID di pesan error
        echo "<div class='alert alert-warning'>Tidak ada produk untuk pesanan ID: " . $order_id . "</div>";

        // Debug query
        error_log("No products found for order ID: " . $order_id);
        error_log("SQL: SELECT * FROM order_details WHERE order_id = " . $order_id);
    }
} else {
    echo "<div class='alert alert-danger'>Invalid Order ID</div>";
    error_log("Invalid order ID received");
}
?>