<?php
require_once '../includes/db.php';
$conn = get_db_connection();
include 'includes/header.php';
include '../includes/functions.php';

$id = intval($_GET['id']);
$q = mysqli_query($conn, "SELECT * FROM pesanan WHERE id=$id");
$row = mysqli_fetch_assoc($q);
?>

<h2>Terima kasih, pesanan Anda telah diterima!</h2>
<p>No. Pesanan: <?= $row['id'] ?></p>
<p>Total: <?= rupiah($row['total_harga']) ?></p>
<p>Status: <?= $row['status_pesanan'] ?></p>
<a href="products.php">Belanja lagi</a>

<?php include 'includes/footer.php'; ?> 