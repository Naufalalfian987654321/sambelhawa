<?php
require_once '../includes/db.php';
$conn = get_db_connection();

include '../includes/auth_admin.php';

$error = '';
$success = '';

if (isset($_POST['add'])) {
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $harga = intval($_POST['harga']);
    $stok = intval($_POST['stok']);
    $kategori = trim($_POST['kategori']);
    $gambar = '';

    // Validasi field
    if ($nama == '' || $deskripsi == '' || $harga <= 0 || $stok < 0 || empty($_FILES['gambar']['name'])) {
        $error = 'Semua field wajib diisi dan valid!';
    } else {
        // Upload gambar
        $target_dir = '../public/assets/images/';
        $file_name = basename($_FILES['gambar']['name']);
        $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        // Tambahkan timestamp jika file sudah ada
        $final_file_name = $file_name;
        if (file_exists($target_dir . $final_file_name)) {
            $final_file_name = pathinfo($file_name, PATHINFO_FILENAME) . '_' . time() . '.' . $imageFileType;
        }
        $target_file = $target_dir . $final_file_name;
        $check = getimagesize($_FILES['gambar']['tmp_name']);
        if($check === false) {
            $error = 'File yang diupload bukan gambar.';
        } elseif ($_FILES['gambar']['size'] > 2*1024*1024) {
            $error = 'Ukuran gambar maksimal 2MB.';
        } elseif (!in_array($imageFileType, ['jpg','jpeg','png','gif'])) {
            $error = 'Format gambar harus JPG, JPEG, PNG, atau GIF.';
        } else {
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                $gambar = $final_file_name;
                // Insert ke database
                $stmt = $conn->prepare("INSERT INTO produk (nama, deskripsi, harga, stok, gambar, kategori) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssdiis", $nama, $deskripsi, $harga, $stok, $gambar, $kategori);
                if ($stmt->execute()) {
                    $success = 'Produk berhasil ditambahkan!';
                    header('Location: products.php?success=1');
                    exit;
                } else {
                    $error = 'Gagal menambah produk!';
                }
            } else {
                $error = 'Gagal upload gambar.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Admin Dashboard</title>
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
        .file-input-wrapper {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }
        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }
        .file-input-label {
            display: inline-block;
            padding: 12px 20px;
            background: linear-gradient(135deg, #ff9800, #ffb74d);
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .file-input-label:hover {
            background: linear-gradient(135deg, #ffb74d, #ff9800);
            transform: translateY(-2px);
        }
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
                
                <a href="orders.php" class="flex items-center px-4 py-3 text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-all duration-200 sidebar-transition">
                    <i class="fas fa-shopping-cart w-5 h-5 mr-3"></i>
                    <span class="font-medium">Pesanan</span>
                </a>
                
                <a href="add_product.php" class="flex items-center px-4 py-3 text-white bg-white bg-opacity-20 rounded-lg shadow-lg">
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
                    <h2 class="text-2xl font-bold text-gray-800">Tambah Produk Baru</h2>
                    <p class="text-gray-600">Tambahkan produk baru ke katalog Sambal Hawa</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="products.php" class="bg-gray-500 text-white px-6 py-3 rounded-lg shadow-md btn-hover font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="p-6">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-plus-circle mr-2 text-primary"></i>
                            Form Tambah Produk
                        </h3>
                    </div>
                    
                    <?php if ($error): ?>
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?= $error ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <?= $success ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="post" enctype="multipart/form-data" class="p-6 space-y-6">
                        <!-- Product Image Upload -->
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                            <div class="mb-4">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                            </div>
                            <h4 class="text-lg font-medium text-gray-700 mb-2">Upload Gambar Produk</h4>
                            <p class="text-gray-500 mb-4">Pilih gambar produk dengan format JPG, PNG, atau GIF (Max: 2MB)</p>
                            <div class="file-input-wrapper">
                                <input type="file" name="gambar" id="gambar" accept="image/*" required onchange="previewImage(this)">
                                <label for="gambar" class="file-input-label">
                                    <i class="fas fa-upload mr-2"></i>
                                    Pilih Gambar
                                </label>
                            </div>
                            <div id="imagePreview" class="mt-4 hidden">
                                <img id="preview" src="" alt="Preview" class="mx-auto max-h-48 rounded-lg shadow-md">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Product Name -->
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-tag mr-2 text-primary"></i>
                                    Nama Produk
                                </label>
                                <input type="text" id="nama" name="nama" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                       placeholder="Contoh: Sambal Terasi Pedas">
                            </div>

                            <!-- Product Price -->
                            <div>
                                <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-dollar-sign mr-2 text-primary"></i>
                                    Harga (Rp)
                                </label>
                                <input type="number" id="harga" name="harga" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                       placeholder="15000">
                            </div>

                            <!-- Product Stock -->
                            <div>
                                <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-boxes mr-2 text-primary"></i>
                                    Stok
                                </label>
                                <input type="number" id="stok" name="stok" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                       placeholder="100">
                            </div>

                            <!-- Product Category (Optional) -->
                            <div>
                                <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-list mr-2 text-primary"></i>
                                    Kategori
                                </label>
                                <input type="text" id="kategori" name="kategori" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                       placeholder="Kategori">
                            </div>
                        </div>

                        <!-- Product Description -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-align-left mr-2 text-primary"></i>
                                Deskripsi Produk
                            </label>
                            <textarea id="deskripsi" name="deskripsi" rows="4" required 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors resize-none"
                                      placeholder="Deskripsikan produk Anda secara detail..."></textarea>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="products.php" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                            <button type="submit" name="add" class="px-6 py-3 bg-gradient-to-r from-primary to-accent text-white rounded-lg shadow-md btn-hover font-medium">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Produk
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tips Section -->
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-blue-800 mb-3">
                        <i class="fas fa-lightbulb mr-2"></i>
                        Tips Menambah Produk
                    </h4>
                    <ul class="space-y-2 text-blue-700">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mr-2 mt-1 text-green-500"></i>
                            <span>Gunakan gambar berkualitas tinggi dengan rasio 1:1</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mr-2 mt-1 text-green-500"></i>
                            <span>Beri nama produk yang jelas dan menarik</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mr-2 mt-1 text-green-500"></i>
                            <span>Deskripsi harus informatif dan menggugah selera</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mr-2 mt-1 text-green-500"></i>
                            <span>Pastikan harga dan stok akurat</span>
                        </li>
                    </ul>
                </div>
            </div>
        </main>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview');
            const previewDiv = document.getElementById('imagePreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewDiv.classList.remove('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Auto-resize textarea
        const textarea = document.getElementById('deskripsi');
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const nama = document.getElementById('nama').value.trim();
            const harga = document.getElementById('harga').value;
            const stok = document.getElementById('stok').value;
            const deskripsi = document.getElementById('deskripsi').value.trim();
            const gambar = document.getElementById('gambar').files[0];

            if (!nama || !harga || !stok || !deskripsi || !gambar) {
                e.preventDefault();
                alert('Mohon lengkapi semua field yang diperlukan!');
                return false;
            }

            if (harga <= 0 || stok < 0) {
                e.preventDefault();
                alert('Harga dan stok harus lebih dari 0!');
                return false;
            }
        });
    </script>
</body>
</html> 