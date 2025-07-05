<?php
require_once '../includes/db.php';
$conn = get_db_connection();
session_start();
include 'includes/header.php';
include '../includes/functions.php';

if (!$_SESSION['cart']) {
    echo "<script>alert('Keranjang kosong!');location='products.php';</script>";
    exit;
}

if (isset($_POST['checkout'])) {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $total = 0;

    foreach ($_SESSION['cart'] as $id => $qty) {
        $q = mysqli_query($conn, "SELECT * FROM produk WHERE id=$id");
        $row = mysqli_fetch_assoc($q);
        $total += $row['harga'] * $qty;
    }

    mysqli_query($conn, "INSERT INTO pesanan (nama_pelanggan, alamat, telepon, total_harga) VALUES ('$nama', '$alamat', '$telepon', '$total')");
    $id_pesanan = mysqli_insert_id($conn);

    foreach ($_SESSION['cart'] as $id => $qty) {
        $q = mysqli_query($conn, "SELECT * FROM produk WHERE id=$id");
        $row = mysqli_fetch_assoc($q);
        mysqli_query($conn, "INSERT INTO detail_pesanan (id_pesanan, id_produk, kuantitas, harga_satuan) VALUES ($id_pesanan, $id, $qty, {$row['harga']})");
        mysqli_query($conn, "UPDATE produk SET stok = stok - $qty WHERE id=$id");
    }

    unset($_SESSION['cart']);
    echo "<script>location='order_success.php?id=$id_pesanan';</script>";
    exit;
}

// Calculate total
$total = 0;
$cart_items = [];
foreach ($_SESSION['cart'] as $id => $qty) {
    $q = mysqli_query($conn, "SELECT * FROM produk WHERE id=$id");
    $row = mysqli_fetch_assoc($q);
    $total += $row['harga'] * $qty;
    $cart_items[] = $row;
}
?>

<style>
.checkout-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.step-indicator {
    display: flex;
    justify-content: center;
    margin-bottom: 3rem;
}

.step {
    display: flex;
    align-items: center;
    margin: 0 1rem;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #ff9800;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 0.5rem;
}

.step.active .step-number {
    background: #b71c1c;
    transform: scale(1.1);
}

.step-line {
    width: 60px;
    height: 2px;
    background: #e0e0e0;
    margin: 0 0.5rem;
}

.step.active .step-line {
    background: #ff9800;
}

.checkout-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

.checkout-form {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #333;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: #ff9800;
    box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.1);
}

.order-summary {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    height: fit-content;
    position: sticky;
    top: 2rem;
}

.cart-item {
    display: flex;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.cart-item:last-child {
    border-bottom: none;
}

.cart-item img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    margin-right: 1rem;
}

.cart-item-details {
    flex: 1;
}

.cart-item-name {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.25rem;
}

.cart-item-price {
    color: #666;
    font-size: 0.9rem;
}

.cart-item-qty {
    background: #ff9800;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: bold;
}

.total-section {
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 2px solid #f0f0f0;
}

.total-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
}

.total-row.final {
    font-size: 1.2rem;
    font-weight: bold;
    color: #b71c1c;
    border-top: 1px solid #e0e0e0;
    padding-top: 0.5rem;
    margin-top: 0.5rem;
}

.checkout-btn {
    width: 100%;
    background: linear-gradient(135deg, #ff9800, #ffb74d);
    color: white;
    border: none;
    padding: 1rem;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 1rem;
}

.checkout-btn:hover {
    background: linear-gradient(135deg, #ffb74d, #ff9800);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
}

.payment-methods {
    margin-top: 1.5rem;
}

.payment-method {
    display: flex;
    align-items: center;
    padding: 1rem;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.payment-method:hover {
    border-color: #ff9800;
    background: #fff8f3;
}

.payment-method input[type="radio"] {
    margin-right: 0.75rem;
}

.payment-method-icon {
    width: 40px;
    height: 40px;
    background: #f0f0f0;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.75rem;
    font-size: 1.2rem;
}

@media (max-width: 768px) {
    .checkout-grid {
        grid-template-columns: 1fr;
    }
    
    .step-indicator {
        flex-direction: column;
        align-items: center;
    }
    
    .step {
        margin: 0.5rem 0;
    }
    
    .step-line {
        display: none;
    }
}
</style>

<div class="checkout-container">
    <!-- Step Indicator -->
    <div class="step-indicator">
        <div class="step active">
            <div class="step-number">1</div>
            <span>Informasi Pengiriman</span>
        </div>
        <div class="step-line"></div>
        <div class="step">
            <div class="step-number">2</div>
            <span>Metode Pembayaran</span>
        </div>
        <div class="step-line"></div>
        <div class="step">
            <div class="step-number">3</div>
            <span>Konfirmasi</span>
        </div>
    </div>

    <div class="checkout-grid">
        <!-- Checkout Form -->
        <div class="checkout-form">
            <h2 style="color: #b71c1c; margin-bottom: 2rem; font-size: 1.8rem;">
                <i class="fas fa-shipping-fast" style="margin-right: 0.5rem;"></i>
                Informasi Pengiriman
            </h2>
            
            <form method="post" id="checkoutForm">
                <div class="form-group">
                    <label for="nama">
                        <i class="fas fa-user" style="margin-right: 0.5rem; color: #ff9800;"></i>
                        Nama Lengkap
                    </label>
                    <input type="text" id="nama" name="nama" required placeholder="Masukkan nama lengkap Anda">
                </div>

                <div class="form-group">
                    <label for="telepon">
                        <i class="fas fa-phone" style="margin-right: 0.5rem; color: #ff9800;"></i>
                        Nomor Telepon
                    </label>
                    <input type="tel" id="telepon" name="telepon" required placeholder="Contoh: 081234567890">
                </div>

                <div class="form-group">
                    <label for="alamat">
                        <i class="fas fa-map-marker-alt" style="margin-right: 0.5rem; color: #ff9800;"></i>
                        Alamat Pengiriman
                    </label>
                    <textarea id="alamat" name="alamat" rows="4" required placeholder="Masukkan alamat lengkap pengiriman"></textarea>
                </div>

                <div class="payment-methods">
                    <h3 style="color: #333; margin-bottom: 1rem;">
                        <i class="fas fa-credit-card" style="margin-right: 0.5rem; color: #ff9800;"></i>
                        Metode Pembayaran
                    </h3>
                    
                    <div class="payment-method">
                        <input type="radio" id="cod" name="payment" value="cod" checked>
                        <div class="payment-method-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600;">Bayar di Tempat (COD)</div>
                            <div style="font-size: 0.9rem; color: #666;">Bayar saat barang diterima</div>
                        </div>
                    </div>

                    <div class="payment-method">
                        <input type="radio" id="transfer" name="payment" value="transfer">
                        <div class="payment-method-icon">
                            <i class="fas fa-university"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600;">Transfer Bank</div>
                            <div style="font-size: 0.9rem; color: #666;">Transfer ke rekening kami</div>
                        </div>
                    </div>

                    <div class="payment-method">
                        <input type="radio" id="ewallet" name="payment" value="ewallet">
                        <div class="payment-method-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600;">E-Wallet</div>
                            <div style="font-size: 0.9rem; color: #666;">GoPay, OVO, DANA</div>
                        </div>
                    </div>
                </div>

                <button type="submit" name="checkout" class="checkout-btn">
                    <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
                    Proses Pesanan
                </button>
            </form>
        </div>

        <!-- Order Summary -->
        <div class="order-summary">
            <h3 style="color: #b71c1c; margin-bottom: 1.5rem; font-size: 1.4rem;">
                <i class="fas fa-shopping-bag" style="margin-right: 0.5rem;"></i>
                Ringkasan Pesanan
            </h3>

            <?php foreach ($cart_items as $item): ?>
            <div class="cart-item">
                <img src="assets/images/<?php echo $item['gambar']; ?>" alt="<?php echo $item['nama']; ?>">
                <div class="cart-item-details">
                    <div class="cart-item-name"><?php echo $item['nama']; ?></div>
                    <div class="cart-item-price">Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></div>
                </div>
                <div class="cart-item-qty">x<?php echo $_SESSION['cart'][$item['id']]; ?></div>
            </div>
            <?php endforeach; ?>

            <div class="total-section">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span>Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                </div>
                <div class="total-row">
                    <span>Ongkos Kirim</span>
                    <span>Rp 10.000</span>
                </div>
                <div class="total-row final">
                    <span>Total</span>
                    <span>Rp <?php echo number_format($total + 10000, 0, ',', '.'); ?></span>
                </div>
            </div>

            <div style="background: #fff8f3; padding: 1rem; border-radius: 8px; margin-top: 1rem; border-left: 4px solid #ff9800;">
                <div style="font-weight: 600; color: #b71c1c; margin-bottom: 0.5rem;">
                    <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>
                    Informasi
                </div>
                <div style="font-size: 0.9rem; color: #666;">
                    • Pesanan akan diproses dalam 1-2 hari kerja<br>
                    • Pengiriman menggunakan kurir terpercaya<br>
                    • Garansi uang kembali 100%
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Add some interactivity
document.addEventListener('DOMContentLoaded', function() {
    // Payment method selection
    const paymentMethods = document.querySelectorAll('.payment-method');
    paymentMethods.forEach(method => {
        method.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Remove active class from all methods
            paymentMethods.forEach(m => m.style.borderColor = '#e0e0e0');
            paymentMethods.forEach(m => m.style.background = 'white');
            
            // Add active class to selected method
            this.style.borderColor = '#ff9800';
            this.style.background = '#fff8f3';
        });
    });

    // Form validation
    const form = document.getElementById('checkoutForm');
    form.addEventListener('submit', function(e) {
        const nama = document.getElementById('nama').value.trim();
        const telepon = document.getElementById('telepon').value.trim();
        const alamat = document.getElementById('alamat').value.trim();
        
        if (!nama || !telepon || !alamat) {
            e.preventDefault();
            alert('Mohon lengkapi semua data yang diperlukan!');
            return false;
        }
        
        // Phone number validation
        const phoneRegex = /^[0-9]{10,13}$/;
        if (!phoneRegex.test(telepon)) {
            e.preventDefault();
            alert('Nomor telepon tidak valid!');
            return false;
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?> 