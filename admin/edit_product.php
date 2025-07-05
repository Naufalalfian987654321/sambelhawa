<?php
require_once '../includes/db.php';
$conn = get_db_connection();
include '../includes/auth_admin.php';

$id = intval($_GET['id']);
$q = mysqli_query($conn, "SELECT * FROM produk WHERE id=$id");
$row = mysqli_fetch_assoc($q);

if (!$row) {
    header("Location: products.php");
    exit;
}

$error = '';
$success = '';

if (isset($_POST['edit'])) {
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $harga = intval($_POST['harga']);
    $stok = intval($_POST['stok']);
    $kategori = trim($_POST['kategori']);
    $gambar = $row['gambar'];

    if ($nama == '' || $deskripsi == '' || $harga <= 0 || $stok < 0) {
        $error = 'Semua field wajib diisi dan valid!';
    } else {
        // Jika upload gambar baru
        if (!empty($_FILES['gambar']['name'])) {
            $target_dir = '../public/assets/images/';
            $file_name = basename($_FILES['gambar']['name']);
            $target_file = $target_dir . $file_name;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $check = getimagesize($_FILES['gambar']['tmp_name']);
            if($check === false) {
                $error = 'File yang diupload bukan gambar.';
            } elseif ($_FILES['gambar']['size'] > 2*1024*1024) {
                $error = 'Ukuran gambar maksimal 2MB.';
            } elseif (!in_array($imageFileType, ['jpg','jpeg','png','gif'])) {
                $error = 'Format gambar harus JPG, JPEG, PNG, atau GIF.';
            } elseif (file_exists($target_file)) {
                $error = 'Nama file gambar sudah ada, ganti nama file dan upload ulang.';
            } else {
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                    $gambar = $file_name;
                } else {
                    $error = 'Gagal upload gambar.';
                }
            }
        }
        if ($error == '') {
            $stmt = $conn->prepare("UPDATE produk SET nama=?, deskripsi=?, harga=?, stok=?, gambar=?, kategori=? WHERE id=?");
            $stmt->bind_param("ssdiisi", $nama, $deskripsi, $harga, $stok, $gambar, $kategori, $id);
            if ($stmt->execute()) {
                $success = 'Produk berhasil diupdate!';
                header("Location: products.php?success=1");
                exit;
            } else {
                $error = 'Gagal update produk!';
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
    <title>Edit Produk - Admin Dashboard</title>
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
                    <h2 class="text-2xl font-bold text-gray-800">Edit Produk</h2>
                    <p class="text-gray-600">Edit informasi produk: <?php echo htmlspecialchars($row['nama']); ?></p>
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
                            <i class="fas fa-edit mr-2 text-primary"></i>
                            Form Edit Produk
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
                        <!-- Current Product Image -->
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                            <div class="mb-4">
                                <img src="../public/assets/images/<?php echo htmlspecialchars($row['gambar']); ?>" 
                                     alt="<?php echo htmlspecialchars($row['nama']); ?>" 
                                     class="mx-auto max-h-48 rounded-lg shadow-md">
                            </div>
                            <h4 class="text-lg font-medium text-gray-700 mb-2">Gambar Saat Ini</h4>
                            <p class="text-gray-500 mb-4">Upload gambar baru untuk mengganti gambar yang ada</p>
                            <div class="file-input-wrapper">
                                <input type="file" name="gambar" id="gambar" accept="image/*" onchange="previewImage(this)">
                                <label for="gambar" class="file-input-label">
                                    <i class="fas fa-upload mr-2"></i>
                                    Pilih Gambar Baru
                                </label>
                            </div>
                            <div id="imagePreview" class="mt-4 hidden">
                                <h5 class="text-sm font-medium text-gray-700 mb-2">Preview Gambar Baru:</h5>
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
                                <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($row['nama']); ?>" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                       placeholder="Contoh: Sambal Terasi Pedas">
                            </div>

                            <!-- Product Price -->
                            <div>
                                <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-dollar-sign mr-2 text-primary"></i>
                                    Harga (Rp)
                                </label>
                                <input type="number" id="harga" name="harga" value="<?php echo $row['harga']; ?>" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                       placeholder="15000">
                            </div>

                            <!-- Product Stock -->
                            <div>
                                <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-boxes mr-2 text-primary"></i>
                                    Stok
                                </label>
                                <input type="number" id="stok" name="stok" value="<?php echo $row['stok']; ?>" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                       placeholder="100">
                            </div>

                            <!-- Product Category (Optional) -->
                            <div>
                                <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-list mr-2 text-primary"></i>
                                    Kategori
                                </label>
                                <input type="text" id="kategori" name="kategori" value="<?php echo htmlspecialchars($row['kategori']); ?>"
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
                                      placeholder="Deskripsikan produk Anda secara detail..."><?php echo htmlspecialchars($row['deskripsi']); ?></textarea>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="products.php" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                            <button type="submit" name="edit" class="px-6 py-3 bg-gradient-to-r from-primary to-accent text-white rounded-lg shadow-md btn-hover font-medium">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
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
    </script>
</body>
</html> 