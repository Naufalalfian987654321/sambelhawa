<?php
require_once '../includes/db.php';
$conn = get_db_connection();
include '../includes/auth_admin.php';
include '../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#ff9800',
                        secondary: '#b71c1c',
                        accent: '#ffb74d'
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar-transition { transition: all 0.3s ease; }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-2px); }
        .btn-hover { transition: all 0.2s ease; }
        .btn-hover:hover { transform: scale(1.05); }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-primary to-accent shadow-xl">
        <div class="flex items-center justify-center h-16 bg-white bg-opacity-10">
            <div class="flex items-center space-x-3">
                <i class="fas fa-pepper-hot text-2xl text-white"></i>
                <h1 class="text-xl font-bold text-white">Sambal Hawa</h1>
            </div>
        </div>
        
        <nav class="mt-8 px-4">
            <div class="space-y-2">
                <a href="dashboard.php" class="flex items-center px-4 py-3 text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-all duration-200 sidebar-transition">
                    <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <a href="products.php" class="flex items-center px-4 py-3 text-white bg-white bg-opacity-20 rounded-lg shadow-lg">
                    <i class="fas fa-box w-5 h-5 mr-3"></i>
                    <span class="font-medium">Produk</span>
                </a>
                
                <a href="orders.php" class="flex items-center px-4 py-3 text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-all duration-200 sidebar-transition">
                    <i class="fas fa-shopping-cart w-5 h-5 mr-3"></i>
                    <span class="font-medium">Pesanan</span>
                </a>
                
                <a href="add_product.php" class="flex items-center px-4 py-3 text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-all duration-200 sidebar-transition">
                    <i class="fas fa-plus-circle w-5 h-5 mr-3"></i>
                    <span class="font-medium">Tambah Produk</span>
                </a>
                
                <div class="pt-4 mt-4 border-t border-white border-opacity-20">
                    <a href="logout.php" class="flex items-center px-4 py-3 text-white hover:bg-red-500 rounded-lg transition-all duration-200 sidebar-transition">
                        <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
                        <span class="font-medium">Logout</span>
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="ml-64 min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="flex items-center justify-between px-6 py-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Manajemen Produk</h2>
                    <p class="text-gray-600">Kelola semua produk Sambal Hawa</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="add_product.php" class="bg-gradient-to-r from-primary to-accent text-white px-6 py-3 rounded-lg shadow-md btn-hover font-medium">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Produk
                    </a>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="p-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100">
                            <i class="fas fa-box text-2xl text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Produk</p>
                            <p class="text-2xl font-bold text-gray-900">
                                <?php
                                $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk");
                                $row = mysqli_fetch_assoc($q);
                                echo $row['total'];
                                ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100">
                            <i class="fas fa-check-circle text-2xl text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Stok Tersedia</p>
                            <p class="text-2xl font-bold text-gray-900">
                                <?php
                                $q = mysqli_query($conn, "SELECT SUM(stok) as total FROM produk");
                                $row = mysqli_fetch_assoc($q);
                                echo $row['total'] ?? 0;
                                ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100">
                            <i class="fas fa-exclamation-triangle text-2xl text-yellow-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Stok Habis</p>
                            <p class="text-2xl font-bold text-gray-900">
                                <?php
                                $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk WHERE stok = 0");
                                $row = mysqli_fetch_assoc($q);
                                echo $row['total'];
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Table -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-list mr-2 text-primary"></i>
                        Daftar Produk
                    </h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            $q = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
                            while ($row = mysqli_fetch_assoc($q)) {
                                echo '<tr>';
                                echo '<td class="px-6 py-4">' . htmlspecialchars($row['nama']) . '</td>';
                                echo '<td class="px-6 py-4">Rp ' . number_format($row['harga'], 0, ',', '.') . '</td>';
                                echo '<td class="px-6 py-4">' . $row['stok'] . '</td>';
                                echo '<td class="px-6 py-4">' . htmlspecialchars($row['kategori']) . '</td>';
                                echo '<td class="px-6 py-4">';
                                echo '<a href="edit_product.php?id=' . $row['id'] . '" class="text-primary hover:text-accent mr-3"><i class="fas fa-edit"></i> Edit</a>';
                                echo '<a href="delete_product.php?id=' . $row['id'] . '" class="text-red-600 hover:text-red-900" onclick="return confirm(\'Yakin ingin menghapus?\')"><i class="fas fa-trash"></i> Hapus</a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Empty State -->
            <?php
            $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk");
            $row = mysqli_fetch_assoc($q);
            if ($row['total'] == 0):
            ?>
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-box text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada produk</h3>
                <p class="text-gray-600 mb-6">Mulai dengan menambahkan produk pertama Anda</p>
                <a href="add_product.php" class="bg-gradient-to-r from-primary to-accent text-white px-6 py-3 rounded-lg shadow-md btn-hover font-medium">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Produk Pertama
                </a>
            </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html> 