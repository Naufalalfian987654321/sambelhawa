<?php
function get_db_connection() {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "sambelhawa";
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }
    return $conn;
}
?> 