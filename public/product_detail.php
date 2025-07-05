<?php
include '../includes/db.php';
include 'includes/header.php';
include '../includes/functions.php';

$id = intval($_GET['id']);
$q = mysqli_query($conn, "SELECT * FROM produk WHERE id=$id");
$row = mysqli_fetch_assoc($q);
?>

<h2><?= $row['nama'] ?></h2>
<img src="assets/images/<?= $row['gambar'] ?>" width="300">
<p><?= $row['deskripsi'] ?></p>
<p>Harga: <?= rupiah($row['harga']) ?></p>
<p>Stok: <?= $row['stok'] ?></p>

<form method="post" action="cart.php">
    <input type="hidden" name="id_produk" value="<?= $row['id'] ?>">
    <input type="number" name="kuantitas" value="1" min="1" max="<?= $row['stok'] ?>">
    <button type="submit" name="add_to_cart">Tambahkan ke Keranjang</button>
</form>

<?php include 'includes/footer.php'; ?> 