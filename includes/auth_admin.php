<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
// Jika ada field role di tabel admin, bisa tambahkan pengecekan role di sini
?> 