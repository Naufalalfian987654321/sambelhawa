CREATE DATABASE sambelhawa;
USE sambelhawa;

-- Tabel produk
CREATE TABLE produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    harga DECIMAL(10,2) NOT NULL,
    gambar VARCHAR(255),
    stok INT NOT NULL DEFAULT 0
);

-- Tabel pesanan
CREATE TABLE pesanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggan VARCHAR(100) NOT NULL,
    alamat VARCHAR(255) NOT NULL,
    telepon VARCHAR(20) NOT NULL,
    tanggal_pesanan DATETIME DEFAULT CURRENT_TIMESTAMP,
    total_harga DECIMAL(10,2) NOT NULL,
    status_pesanan ENUM('Menunggu Pembayaran','Diproses','Dikirim','Selesai') DEFAULT 'Menunggu Pembayaran'
);

-- Tabel detail pesanan
CREATE TABLE detail_pesanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pesanan INT,
    id_produk INT,
    kuantitas INT,
    harga_satuan DECIMAL(10,2),
    FOREIGN KEY (id_pesanan) REFERENCES pesanan(id),
    FOREIGN KEY (id_produk) REFERENCES produk(id)
);

-- Tabel admin
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Admin default (username: admin, password: admin123)
INSERT INTO admin (username, password) VALUES ('admin', MD5('admin123')); 