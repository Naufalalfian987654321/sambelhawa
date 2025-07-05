<?php
require_once '../includes/db.php';
$conn = get_db_connection();
include '../includes/auth_admin.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Sambal Hawa</title>
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
        .card-hover:hover { transform: translateY(-5px); }
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
                <a href="dashboard.php" class="flex items-center px-4 py-3 text-white bg-white bg-opacity-20 rounded-lg shadow-lg">
                    <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <a href="products.php" class="flex items-center px-4 py-3 text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-all duration-200 sidebar-transition">
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
                    <h2 class="text-2xl font-bold text-gray-800">Dashboard Admin</h2>
                    <p class="text-gray-600">Selamat datang di panel admin Sambal Hawa</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-user-circle text-2xl text-primary"></i>
                        <span class="font-medium text-gray-700">Admin</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="p-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
                            <i class="fas fa-shopping-cart text-2xl text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Pesanan</p>
                            <p class="text-2xl font-bold text-gray-900">
                                <?php
                                $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM pesanan");
                                $row = mysqli_fetch_assoc($q);
                                echo $row['total'];
                                ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100">
                            <i class="fas fa-clock text-2xl text-yellow-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pesanan Baru</p>
                            <p class="text-2xl font-bold text-gray-900">
                                <?php
                                $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM pesanan WHERE status_pesanan = 'pending'");
                                $row = mysqli_fetch_assoc($q);
                                echo $row['total'];
                                ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100">
                            <i class="fas fa-dollar-sign text-2xl text-red-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pendapatan</p>
                            <p class="text-2xl font-bold text-gray-900">
                                <?php
                                $q = mysqli_query($conn, "SELECT SUM(total_harga) as total FROM pesanan WHERE status_pesanan = 'completed'");
                                $row = mysqli_fetch_assoc($q);
                                echo 'Rp ' . number_format($row['total'] ?? 0, 0, ',', '.');
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="add_product.php" class="flex items-center p-4 bg-gradient-to-r from-primary to-accent text-white rounded-lg shadow-md btn-hover">
                            <i class="fas fa-plus-circle text-xl mr-3"></i>
                            <span class="font-medium">Tambah Produk</span>
                        </a>
                        <a href="orders.php" class="flex items-center p-4 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg shadow-md btn-hover">
                            <i class="fas fa-list text-xl mr-3"></i>
                            <span class="font-medium">Lihat Pesanan</span>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Pesanan Terbaru</h3>
                    <div class="space-y-3">
                        <?php
                        $q = mysqli_query($conn, "SELECT * FROM pesanan ORDER BY id DESC LIMIT 5");
                        while ($row = mysqli_fetch_assoc($q)) {
                            echo '<div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">';
                            echo '<div>';
                            echo '<p class="font-medium text-gray-800">' . $row['nama_pelanggan'] . '</p>';
                            echo '<p class="text-sm text-gray-600">Rp ' . number_format($row['total_harga'], 0, ',', '.') . '</p>';
                            echo '</div>';
                            echo '<span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Baru</span>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Recent Products -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Produk Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            $q = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC LIMIT 5");
                            while ($row = mysqli_fetch_assoc($q)) {
                                echo '<tr class="hover:bg-gray-50">';
                                echo '<td class="px-6 py-4 whitespace-nowrap">';
                                echo '<div class="flex items-center">';
                                echo '<div class="flex-shrink-0 h-10 w-10">';
                                echo '<img class="h-10 w-10 rounded-full object-cover" src="../public/assets/images/' . $row['gambar'] . '" alt="' . $row['nama'] . '" onerror="this.onerror=null;this.src=\'../public/assets/images/logo.png\';">';
                                echo '</div>';
                                echo '<div class="ml-4">';
                                echo '<div class="text-sm font-medium text-gray-900">' . $row['nama'] . '</div>';
                                echo '</div>';
                                echo '</div>';
                                echo '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp ' . number_format($row['harga'], 0, ',', '.') . '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . $row['stok'] . '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">';
                                echo '<a href="edit_product.php?id=' . $row['id'] . '" class="text-primary hover:text-accent mr-3">Edit</a>';
                                echo '<a href="delete_product.php?id=' . $row['id'] . '" class="text-red-600 hover:text-red-900" onclick="return confirm(\'Yakin ingin menghapus?\')">Hapus</a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html> 