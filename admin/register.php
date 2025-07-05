<?php
include '../includes/db.php';

$error = '';
$success = '';

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    
    if ($username == '' || $password == '' || $confirm == '') {
        $error = 'Semua field wajib diisi!';
    } elseif ($password !== $confirm) {
        $error = 'Konfirmasi password tidak cocok!';
    } else {
        $cek = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username'");
        if (mysqli_num_rows($cek) > 0) {
            $error = 'Username sudah terdaftar!';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($conn, "INSERT INTO admin (username, password) VALUES ('$username', '$hash')");
            $success = 'Registrasi berhasil! Silakan login.';
            header('refresh:2;url=login.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin - Sambal Hawa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-orange-50 to-red-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-8">
                <div class="mx-auto w-20 h-20 bg-gradient-to-br from-orange-400 to-orange-200 rounded-full flex items-center justify-center mb-4 shadow-lg">
                    <i class="fas fa-user-shield text-3xl text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Register Admin</h1>
                <p class="text-gray-600">Buat akun admin baru</p>
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
            <form method="post" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-primary"></i>
                        Username
                    </label>
                    <input type="text" id="username" name="username" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 transition-all" placeholder="Username admin">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-primary"></i>
                        Password
                    </label>
                    <input type="password" id="password" name="password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 transition-all" placeholder="Password">
                </div>
                <div>
                    <label for="confirm" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-primary"></i>
                        Konfirmasi Password
                    </label>
                    <input type="password" id="confirm" name="confirm" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 transition-all" placeholder="Ulangi password">
                </div>
                <button type="submit" name="register" class="w-full bg-gradient-to-r from-primary to-accent text-white py-3 px-4 rounded-lg font-semibold btn-hover shadow-lg">
                    <i class="fas fa-user-plus mr-2"></i>
                    Register Admin
                </button>
            </form>
            <div class="text-center mt-6">
                <a href="login.php" class="text-sm text-gray-600 hover:text-primary transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</body>
</html> 