<?php
include 'koneksimysql.php';

if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']); // pastikan integer

    $stmt = $conn->prepare("SELECT product_name, qty, price, sub_total FROM order_details WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table class='table table-bordered'>";
        echo "<thead><tr><th>Produk</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr></thead><tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['product_name']}</td>
                    <td>{$row['qty']}</td>
                    <td>Rp " . number_format($row['price'], 0, ',', '.') . "</td>
                    <td>Rp " . number_format($row['sub_total'], 0, ',', '.') . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p class='text-warning'>Tidak ada produk untuk pesanan ini.</p>";
    }
} else {
    echo "<p class='text-danger'>Order ID tidak ditemukan.</p>";
}
?>