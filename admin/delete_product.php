<?php
require_once '../includes/db.php';
$conn = get_db_connection();
include '../includes/auth_admin.php';

$id = intval($_GET['id']);
// Hapus data terkait di detail_pesanan terlebih dahulu
mysqli_query($conn, "DELETE FROM detail_pesanan WHERE id_produk=$id");
// Hapus produk dari tabel produk
mysqli_query($conn, "DELETE FROM produk WHERE id=$id");
header("Location: products.php");
exit;
?> 