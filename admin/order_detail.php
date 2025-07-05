<?php
require_once '../includes/db.php';
$conn = get_db_connection();
include '../includes/auth_admin.php';
include '../includes/functions.php';

$id = intval($_GET['id']);
$q = mysqli_query($conn, "SELECT * FROM pesanan WHERE id=$id");
$row = mysqli_fetch_assoc($q);

if (isset($_POST['update_status'])) {
    $status = $_POST['status'];
    mysqli_query($conn, "UPDATE pesanan SET status_pesanan='$status' WHERE id=$id");
    header("Location: order_detail.php?id=$id");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detail Pesanan</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
<h2>Detail Pesanan #<?= $row['id'] ?></h2>
<p>Pelanggan: <?= $row['nama_pelanggan'] ?></p>
<p>Alamat: <?= $row['alamat'] ?></p>
<p>Telepon: <?= $row['telepon'] ?></p>
<p>Tanggal: <?= $row['tanggal_pesanan'] ?></p>
<p>Status: <?= $row['status_pesanan'] ?></p>
<form method="post">
    <select name="status">
        <option <?= $row['status_pesanan']=='Menunggu Pembayaran'?'selected':'' ?>>Menunggu Pembayaran</option>
        <option <?= $row['status_pesanan']=='Diproses'?'selected':'' ?>>Diproses</option>
        <option <?= $row['status_pesanan']=='Dikirim'?'selected':'' ?>>Dikirim</option>
        <option <?= $row['status_pesanan']=='Selesai'?'selected':'' ?>>Selesai</option>
    </select>
    <button type="submit" name="update_status">Update Status</button>
</form>
<h3>Produk Pesanan</h3>
<table border="1">
    <tr><th>Produk</th><th>Kuantitas</th><th>Harga Satuan</th><th>Subtotal</th></tr>
    <?php
    $q2 = mysqli_query($conn, "SELECT d.*, p.nama FROM detail_pesanan d JOIN produk p ON d.id_produk=p.id WHERE d.id_pesanan=$id");
    while ($r = mysqli_fetch_assoc($q2)) {
        $sub = $r['kuantitas'] * $r['harga_satuan'];
        echo "<tr>
            <td>{$r['nama']}</td>
            <td>{$r['kuantitas']}</td>
            <td>".rupiah($r['harga_satuan'])."</td>
            <td>".rupiah($sub)."</td>
        </tr>";
    }
    ?>
</table>
<a href="orders.php">Kembali</a>
</body>
</html> 