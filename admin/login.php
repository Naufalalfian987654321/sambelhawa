<?php
session_start();
require_once '../includes/db.php';

$conn = get_db_connection();

// Inisialisasi pesan
$error = '';
$success = '';
$active_tab = 'login';

// Proses Login
if (isset($_POST['login'])) {
    $active_tab = 'login';
    $user = trim($_POST['username']);
    $pass = $_POST['password'];
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row && password_verify($pass, $row['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $user;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}

// Proses Register
if (isset($_POST['register'])) {
    $active_tab = 'register';
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    if ($username == '' || $password == '' || $confirm == '') {
        $error = 'Semua field wajib diisi!';
    } elseif ($password !== $confirm) {
        $error = 'Konfirmasi password tidak cocok!';
    } else {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $error = 'Username sudah terdaftar!';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hash);
            if ($stmt->execute()) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $username;
                $success = 'Registrasi berhasil! Anda akan diarahkan ke dashboard.';
                header('refresh:2;url=dashboard.php');
                exit;
            } else {
                $error = 'Gagal registrasi admin!';
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
    <title>Admin Panel - Sambal Hawa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .tab-btn { transition: all 0.2s; }
        .tab-btn.active { background: #b71c1c; color: #fff; box-shadow: 0 2px 12px #b71c1c33; }
        .tab-btn:not(.active) { background: #fffbe7; color: #b71c1c; }
    </style>
</head>
<body class="bg-gradient-to-br from-orange-50 to-red-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-8">
                <div class="mx-auto w-20 h-20 bg-gradient-to-br from-primary to-accent rounded-full flex items-center justify-center mb-4 shadow-lg">
                    <i class="fas fa-user-shield text-3xl text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Admin Panel</h1>
                <p class="text-gray-600">Login atau Register Admin</p>
            </div>
            <div class="flex mb-6 justify-center gap-4">
                <button class="tab-btn px-6 py-2 rounded-full font-semibold <?php if($active_tab=='login') echo 'active'; ?>" onclick="showTab('login')">Login</button>
                <button class="tab-btn px-6 py-2 rounded-full font-semibold <?php if($active_tab=='register') echo 'active'; ?>" onclick="showTab('register')">Register</button>
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
            <!-- Login Form -->
            <form method="post" id="loginForm" class="space-y-6" style="<?php if($active_tab!='login') echo 'display:none;'; ?>">
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
                <button type="submit" name="login" class="w-full bg-gradient-to-r from-primary to-accent text-white py-3 px-4 rounded-lg font-semibold btn-hover shadow-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </button>
            </form>
            <!-- Register Form -->
            <form method="post" id="registerForm" class="space-y-6" style="<?php if($active_tab!='register') echo 'display:none;'; ?>">
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
                    <i class="fas fa-user-plus mr-2"></i>Register Admin
                </button>
            </form>
            <div class="text-center mt-6">
                <a href="../public/index.php" class="text-sm text-gray-600 hover:text-primary transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali ke Website
                </a>
            </div>
        </div>
    </div>
    <script>
        function showTab(tab) {
            document.getElementById('loginForm').style.display = (tab === 'login') ? '' : 'none';
            document.getElementById('registerForm').style.display = (tab === 'register') ? '' : 'none';
            var btns = document.querySelectorAll('.tab-btn');
            btns[0].classList.toggle('active', tab === 'login');
            btns[1].classList.toggle('active', tab === 'register');
        }
    </script>
</body>
</html> 