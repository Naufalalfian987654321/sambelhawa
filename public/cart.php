<?php
session_start();
require_once '../includes/db.php';
$conn = get_db_connection();
include 'includes/header.php';
include '../includes/functions.php';

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if (isset($_POST['add_to_cart'])) {
    $id = intval($_POST['id_produk']);
    $qty = intval($_POST['kuantitas']);
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] += $qty;
    } else {
        $_SESSION['cart'][$id] = $qty;
    }
    echo "<script>alert('Ditambahkan ke keranjang!');location='cart.php';</script>";
}

if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    echo "<script>location='cart.php';</script>";
}

if (isset($_POST['update_cart'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        if ($qty <= 0) unset($_SESSION['cart'][$id]);
        else $_SESSION['cart'][$id] = $qty;
    }
    echo "<script>location='cart.php';</script>";
}
?>

<style>
.cart-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.cart-header {
    background: linear-gradient(135deg, #ff9800, #ffb74d);
    color: white;
    padding: 2rem;
    border-radius: 16px;
    margin-bottom: 2rem;
    text-align: center;
}

.cart-items {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    overflow: hidden;
}

.cart-item {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.3s ease;
}

.cart-item:hover {
    background: #fafafa;
}

.cart-item:last-child {
    border-bottom: none;
}

.cart-item-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 12px;
    margin-right: 1.5rem;
    border: 2px solid #ff9800;
}

.cart-item-details {
    flex: 1;
}

.cart-item-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
}

.cart-item-price {
    color: #ff9800;
    font-weight: 600;
    font-size: 1rem;
}

.cart-item-qty {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0 2rem;
}

.qty-input {
    width: 60px;
    padding: 0.5rem;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    text-align: center;
    font-weight: 600;
}

.qty-input:focus {
    outline: none;
    border-color: #ff9800;
    box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.1);
}

.cart-item-subtotal {
    font-weight: 600;
    color: #b71c1c;
    font-size: 1.1rem;
    margin: 0 2rem;
    min-width: 120px;
    text-align: right;
}

.cart-item-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-remove {
    background: #dc3545;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.btn-remove:hover {
    background: #c82333;
    transform: scale(1.05);
}

.cart-summary {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    margin-top: 2rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f0f0f0;
}

.summary-row:last-child {
    border-bottom: none;
    font-size: 1.2rem;
    font-weight: bold;
    color: #b71c1c;
}

.cart-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    justify-content: center;
}

.btn-update {
    background: #6c757d;
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
}

.btn-update:hover {
    background: #5a6268;
    transform: translateY(-2px);
}

.btn-checkout {
    background: linear-gradient(135deg, #ff9800, #ffb74d);
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.btn-checkout:hover {
    background: linear-gradient(135deg, #ffb74d, #ff9800);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
}

.empty-cart {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-cart-icon {
    font-size: 4rem;
    color: #ccc;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .cart-item {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .cart-item-qty,
    .cart-item-subtotal {
        margin: 0;
    }
    
    .cart-actions {
        flex-direction: column;
    }
}
</style>

<div class="cart-container">
    <!-- Cart Header -->
    <div class="cart-header">
        <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">
            <i class="fas fa-shopping-cart" style="margin-right: 0.5rem;"></i>
            Keranjang Belanja
        </h1>
        <p style="opacity: 0.9;">Kelola produk dalam keranjang Anda</p>
    </div>

    <?php if ($_SESSION['cart']): ?>
    <form method="post">
        <!-- Cart Items -->
        <div class="cart-items">
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $id => $qty) {
                $q = mysqli_query($conn, "SELECT * FROM produk WHERE id=$id");
                $row = mysqli_fetch_assoc($q);
                $sub = $row['harga'] * $qty;
                $total += $sub;
            ?>
            <div class="cart-item">
                <img src="assets/images/<?php echo $row['gambar']; ?>" alt="<?php echo $row['nama']; ?>" class="cart-item-image">
                
                <div class="cart-item-details">
                    <div class="cart-item-name"><?php echo $row['nama']; ?></div>
                    <div class="cart-item-price">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></div>
                </div>
                
                <div class="cart-item-qty">
                    <label style="font-weight: 600; color: #666;">Qty:</label>
                    <input type="number" name="qty[<?php echo $id; ?>]" value="<?php echo $qty; ?>" 
                           min="1" max="<?php echo $row['stok']; ?>" class="qty-input">
                </div>
                
                <div class="cart-item-subtotal">
                    Rp <?php echo number_format($sub, 0, ',', '.'); ?>
                </div>
                
                <div class="cart-item-actions">
                    <button type="button" onclick="removeItem(<?php echo $id; ?>)" class="btn-remove">
                        <i class="fas fa-trash mr-1"></i>Hapus
                    </button>
                </div>
            </div>
            <?php } ?>
        </div>

        <!-- Cart Summary -->
        <div class="cart-summary">
            <h3 style="color: #333; margin-bottom: 1.5rem; font-size: 1.3rem;">
                <i class="fas fa-calculator mr-2 text-primary"></i>
                Ringkasan Belanja
            </h3>
            
            <div class="summary-row">
                <span>Total Item:</span>
                <span><?php echo array_sum($_SESSION['cart']); ?> item</span>
            </div>
            
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
            </div>
            
            <div class="summary-row">
                <span>Ongkos Kirim:</span>
                <span>Rp 10.000</span>
            </div>
            
            <div class="summary-row">
                <span>Total:</span>
                <span>Rp <?php echo number_format($total + 10000, 0, ',', '.'); ?></span>
            </div>
        </div>

        <!-- Cart Actions -->
        <div class="cart-actions">
            <button type="submit" name="update_cart" class="btn-update">
                <i class="fas fa-sync-alt mr-2"></i>
                Update Keranjang
            </button>
            
            <a href="checkout.php" class="btn-checkout">
                <i class="fas fa-credit-card mr-2"></i>
                Lanjut ke Checkout
            </a>
        </div>
    </form>
    
    <?php else: ?>
    <!-- Empty Cart -->
    <div class="cart-items">
        <div class="empty-cart">
            <div class="empty-cart-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h3 style="color: #666; margin-bottom: 1rem;">Keranjang Belanja Kosong</h3>
            <p style="color: #999; margin-bottom: 2rem;">Belum ada produk yang ditambahkan ke keranjang</p>
            <a href="products.php" class="btn-checkout">
                <i class="fas fa-shopping-bag mr-2"></i>
                Mulai Belanja
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function removeItem(id) {
    if (confirm('Yakin ingin menghapus produk ini dari keranjang?')) {
        window.location.href = 'cart.php?remove=' + id;
    }
}

// Add quantity validation
document.addEventListener('DOMContentLoaded', function() {
    const qtyInputs = document.querySelectorAll('.qty-input');
    
    qtyInputs.forEach(input => {
        input.addEventListener('change', function() {
            const value = parseInt(this.value);
            const max = parseInt(this.getAttribute('max'));
            const min = parseInt(this.getAttribute('min'));
            
            if (value < min) {
                this.value = min;
            } else if (value > max) {
                this.value = max;
                alert('Jumlah melebihi stok yang tersedia!');
            }
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?> 