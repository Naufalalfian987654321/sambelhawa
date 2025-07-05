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
    <title>Manajemen Pesanan - Admin Dashboard</title>
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
                
                <a href="products.php" class="flex items-center px-4 py-3 text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-all duration-200 sidebar-transition">
                    <i class="fas fa-box w-5 h-5 mr-3"></i>
                    <span class="font-medium">Produk</span>
                </a>
                
                <a href="orders.php" class="flex items-center px-4 py-3 text-white bg-white bg-opacity-20 rounded-lg shadow-lg">
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
                    <h2 class="text-2xl font-bold text-gray-800">Manajemen Pesanan</h2>
                    <p class="text-gray-600">Kelola semua pesanan pelanggan</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-filter text-gray-400"></i>
                        <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="processing">Diproses</option>
                            <option value="shipped">Dikirim</option>
                            <option value="delivered">Terkirim</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="p-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100">
                            <i class="fas fa-shopping-cart text-2xl text-blue-600"></i>
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
                            <p class="text-sm font-medium text-gray-600">Pending</p>
                            <p class="text-2xl font-bold text-gray-900">
                                <?php
                                $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM pesanan WHERE status_pesanan = 'pending' OR status_pesanan IS NULL");
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
                            <p class="text-sm font-medium text-gray-600">Selesai</p>
                            <p class="text-2xl font-bold text-gray-900">
                                <?php
                                $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM pesanan WHERE status_pesanan = 'delivered'");
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
                                $q = mysqli_query($conn, "SELECT SUM(total_harga) as total FROM pesanan WHERE status_pesanan = 'delivered'");
                                $row = mysqli_fetch_assoc($q);
                                echo 'Rp ' . number_format($row['total'] ?? 0, 0, ',', '.');
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-list mr-2 text-primary"></i>
                        Daftar Pesanan
                    </h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Pesanan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            $q = mysqli_query($conn, "SELECT * FROM pesanan ORDER BY id DESC");
                            while ($row = mysqli_fetch_assoc($q)) {
                                $status = $row['status_pesanan'] ?? 'pending';
                                $status_config = [
                                    'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fas fa-clock', 'text' => 'Pending'],
                                    'processing' => ['class' => 'bg-blue-100 text-blue-800', 'icon' => 'fas fa-cog', 'text' => 'Diproses'],
                                    'shipped' => ['class' => 'bg-purple-100 text-purple-800', 'icon' => 'fas fa-shipping-fast', 'text' => 'Dikirim'],
                                    'delivered' => ['class' => 'bg-green-100 text-green-800', 'icon' => 'fas fa-check-circle', 'text' => 'Terkirim'],
                                    'cancelled' => ['class' => 'bg-red-100 text-red-800', 'icon' => 'fas fa-times-circle', 'text' => 'Dibatalkan']
                                ];
                                $current_status = isset($status_config[$status]) ? $status_config[$status] : $status_config['pending'];
                                
                                echo '<tr class="hover:bg-gray-50 transition-colors">';
                                echo '<td class="px-6 py-4 whitespace-nowrap">';
                                echo '<div class="text-sm font-medium text-gray-900">#' . str_pad($row['id'], 4, '0', STR_PAD_LEFT) . '</div>';
                                echo '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">';
                                echo '<div class="flex items-center">';
                                echo '<div class="flex-shrink-0 h-10 w-10">';
                                echo '<div class="h-10 w-10 rounded-full bg-gradient-to-r from-primary to-accent flex items-center justify-center">';
                                echo '<i class="fas fa-user text-white"></i>';
                                echo '</div>';
                                echo '</div>';
                                echo '<div class="ml-4">';
                                echo '<div class="text-sm font-medium text-gray-900">' . $row['nama_pelanggan'] . '</div>';
                                echo '<div class="text-sm text-gray-500">' . $row['telepon'] . '</div>';
                                echo '</div>';
                                echo '</div>';
                                echo '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">';
                                echo '<div class="text-sm text-gray-900">' . date('d/m/Y', strtotime($row['tanggal_pesanan'])) . '</div>';
                                echo '<div class="text-sm text-gray-500">' . date('H:i', strtotime($row['tanggal_pesanan'])) . '</div>';
                                echo '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">';
                                echo '<div class="text-sm font-semibold text-gray-900">Rp ' . number_format($row['total_harga'], 0, ',', '.') . '</div>';
                                echo '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">';
                                echo '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' . $current_status['class'] . '">';
                                echo '<i class="' . $current_status['icon'] . ' mr-1"></i>';
                                echo $current_status['text'];
                                echo '</span>';
                                echo '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">';
                                echo '<div class="flex space-x-2">';
                                echo '<a href="order_detail.php?id=' . $row['id'] . '" class="text-primary hover:text-accent transition-colors">';
                                echo '<i class="fas fa-eye mr-1"></i>Detail';
                                echo '</a>';
                                echo '<button onclick="updateStatus(' . $row['id'] . ')" class="text-green-600 hover:text-green-900 transition-colors">';
                                echo '<i class="fas fa-edit mr-1"></i>Update';
                                echo '</button>';
                                echo '</div>';
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
            $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM pesanan");
            $row = mysqli_fetch_assoc($q);
            if ($row['total'] == 0):
            ?>
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-shopping-cart text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada pesanan</h3>
                <p class="text-gray-600">Pesanan akan muncul di sini setelah pelanggan melakukan checkout</p>
            </div>
            <?php endif; ?>
        </main>
    </div>

    <script>
        function updateStatus(orderId) {
            const statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
            const statusNames = ['Pending', 'Diproses', 'Dikirim', 'Terkirim', 'Dibatalkan'];
            
            const currentStatus = prompt('Pilih status baru:\n1. Pending\n2. Diproses\n3. Dikirim\n4. Terkirim\n5. Dibatalkan\n\nMasukkan nomor (1-5):');
            
            if (currentStatus && currentStatus >= 1 && currentStatus <= 5) {
                const newStatus = statuses[currentStatus - 1];
                if (confirm(`Ubah status pesanan #${orderId.toString().padStart(4, '0')} menjadi "${statusNames[currentStatus - 1]}"?`)) {
                    // Here you would typically make an AJAX call to update the status
                    // For now, we'll just show an alert
                    alert(`Status pesanan #${orderId.toString().padStart(4, '0')} berhasil diubah menjadi "${statusNames[currentStatus - 1]}"`);
                    location.reload();
                }
            }
        }

        // Filter functionality
        document.querySelector('select').addEventListener('change', function() {
            const status = this.value;
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                if (!status || row.querySelector('td:nth-child(5) span').textContent.toLowerCase().includes(status)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html> 