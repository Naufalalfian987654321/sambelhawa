<?php
require_once '../includes/db.php';
$conn = get_db_connection();
?>
<?php include 'includes/header.php'; ?>
<?php include '../includes/functions.php'; ?>
<?php
// Ambil kategori unik
$kategori_q = mysqli_query($conn, "SELECT DISTINCT kategori FROM produk WHERE kategori != ''");
?>

<style>
.products-hero {
    background: linear-gradient(135deg, #fffbe7 0%, #ffe5e0 100%);
    padding: 4rem 2rem;
    text-align: center;
    margin-bottom: 3rem;
    border-radius: 0 0 2rem 2rem;
    box-shadow: 0 4px 20px rgba(255, 152, 0, 0.1);
}

.products-hero h1 {
    font-family: 'Merriweather', serif;
    font-size: 3rem;
    font-weight: 900;
    color: #b71c1c;
    margin-bottom: 1rem;
    text-shadow: 0 2px 4px rgba(183, 28, 28, 0.1);
}

.products-hero p {
    font-size: 1.2rem;
    color: #666;
    max-width: 600px;
    margin: 0 auto 2rem auto;
}

.products-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.products-filters {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    display: flex;
    gap: 1rem;
    align-items: center;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-group label {
    font-weight: 600;
    color: #333;
    font-size: 0.9rem;
}

.filter-group select,
.filter-group input {
    padding: 0.5rem 1rem;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.filter-group select:focus,
.filter-group input:focus {
    outline: none;
    border-color: #ff9800;
    box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.1);
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.product-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 30px rgba(255, 152, 0, 0.2);
}

.product-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.product-content {
    padding: 1.5rem;
}

.product-name {
    font-size: 1.2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.product-description {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    line-height: 1.5;
}

.product-price {
    font-size: 1.3rem;
    font-weight: 800;
    color: #ff9800;
    margin-bottom: 1rem;
}

.product-form {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.quantity-input {
    width: 80px;
    padding: 0.5rem;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    text-align: center;
    font-weight: 600;
    transition: all 0.3s ease;
}

.quantity-input:focus {
    outline: none;
    border-color: #ff9800;
    box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.1);
}

.add-to-cart-btn {
    flex: 1;
    background: linear-gradient(135deg, #ff9800, #ffb74d);
    color: white;
    border: none;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.add-to-cart-btn:hover {
    background: linear-gradient(135deg, #ffb74d, #ff9800);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
}

.stock-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: #4caf50;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.stock-badge.out-of-stock {
    background: #f44336;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.empty-state i {
    font-size: 4rem;
    color: #ccc;
    margin-bottom: 1rem;
}

.empty-state h3 {
    font-size: 1.5rem;
    color: #333;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #666;
    margin-bottom: 2rem;
}

@media (max-width: 768px) {
    .products-hero h1 {
        font-size: 2rem;
    }
    
    .products-filters {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-group {
        justify-content: space-between;
    }
    
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
    }
}
</style>

<!-- Hero Section -->
<div class="products-hero">
    <h1><i class="fas fa-pepper-hot" style="margin-right: 0.5rem; color: #ff9800;"></i>Katalog Sambal</h1>
    <p>Temukan berbagai varian sambal pedas terbaik dengan cita rasa otentik Indonesia</p>
</div>

<div class="products-container">
    <!-- Filters -->
    <div class="products-filters">
        <div class="filter-group">
            <label for="sort">
                <i class="fas fa-sort" style="margin-right: 0.5rem; color: #ff9800;"></i>
                Urutkan:
            </label>
            <select id="sort" onchange="sortProducts(this.value)">
                <option value="newest">Terbaru</option>
                <option value="price-low">Harga Terendah</option>
                <option value="price-high">Harga Tertinggi</option>
                <option value="name">Nama A-Z</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="search">
                <i class="fas fa-search" style="margin-right: 0.5rem; color: #ff9800;"></i>
                Cari:
            </label>
            <input type="text" id="search" placeholder="Cari produk..." onkeyup="searchProducts(this.value)">
        </div>
        
        <div class="filter-group">
            <label for="stock">
                <i class="fas fa-box" style="margin-right: 0.5rem; color: #ff9800;"></i>
                Stok:
            </label>
            <select id="stock" onchange="filterByStock(this.value)">
                <option value="all">Semua</option>
                <option value="available">Tersedia</option>
                <option value="out">Habis</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="kategori">
                <i class="fas fa-tags" style="margin-right: 0.5rem; color: #ff9800;"></i>
                Kategori:
            </label>
            <select id="kategori" onchange="filterByKategori(this.value)">
                <option value="all">Semua</option>
                <?php while($kat = mysqli_fetch_assoc($kategori_q)): ?>
                    <option value="<?= htmlspecialchars($kat['kategori']) ?>"><?= htmlspecialchars($kat['kategori']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="products-grid" id="productsGrid">
        <?php
        $q = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
        $product_count = 0;
        while ($row = mysqli_fetch_assoc($q)) {
            $product_count++;
            $stock_class = $row['stok'] > 0 ? '' : 'out-of-stock';
            $stock_text = $row['stok'] > 0 ? 'Tersedia' : 'Habis';
            ?>
            <div class="product-card" data-name="<?php echo strtolower($row['nama']); ?>" data-price="<?php echo $row['harga']; ?>" data-stock="<?php echo $row['stok']; ?>" data-kategori="<?php echo htmlspecialchars($row['kategori']); ?>">
                <div class="stock-badge <?php echo $stock_class; ?>">
                    <?php echo $stock_text; ?>
                </div>
                <img src="assets/images/<?php echo $row['gambar']; ?>" alt="<?php echo $row['nama']; ?>" class="product-image" onerror="this.onerror=null;this.src='assets/images/logo.png';">
                <div class="product-content">
                    <h3 class="product-name"><?php echo $row['nama']; ?></h3>
                    <p class="product-description"><?php echo substr($row['deskripsi'], 0, 80); ?>...</p>
                    <div class="product-price">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></div>
                    <form method="post" action="cart.php" class="product-form">
                        <input type="hidden" name="id_produk" value="<?php echo $row['id']; ?>">
                        <input type="number" name="kuantitas" value="1" min="1" max="<?php echo $row['stok']; ?>" class="quantity-input" <?php echo $row['stok'] <= 0 ? 'disabled' : ''; ?>>
                        <button type="submit" name="add_to_cart" class="add-to-cart-btn" <?php echo $row['stok'] <= 0 ? 'disabled' : ''; ?>>
                            <i class="fas fa-cart-plus"></i>
                            <?php echo $row['stok'] > 0 ? 'Tambah ke Keranjang' : 'Stok Habis'; ?>
                        </button>
                    </form>
                </div>
            </div>
            <?php
        }
        ?>
    </div>

    <!-- Empty State -->
    <?php if ($product_count == 0): ?>
    <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <h3>Belum ada produk</h3>
        <p>Produk akan segera tersedia. Silakan cek kembali nanti.</p>
    </div>
    <?php endif; ?>
</div>

<script>
function sortProducts(sortBy) {
    const grid = document.getElementById('productsGrid');
    const products = Array.from(grid.children);
    
    products.sort((a, b) => {
        switch(sortBy) {
            case 'price-low':
                return parseInt(a.dataset.price) - parseInt(b.dataset.price);
            case 'price-high':
                return parseInt(b.dataset.price) - parseInt(a.dataset.price);
            case 'name':
                return a.dataset.name.localeCompare(b.dataset.name);
            case 'newest':
            default:
                return 0; // Already sorted by newest
        }
    });
    
    products.forEach(product => grid.appendChild(product));
}

function searchProducts(query) {
    const products = document.querySelectorAll('.product-card');
    const searchTerm = query.toLowerCase();
    
    products.forEach(product => {
        const name = product.dataset.name;
        if (name.includes(searchTerm)) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
}

function filterByStock(stockFilter) {
    const products = document.querySelectorAll('.product-card');
    
    products.forEach(product => {
        const stock = parseInt(product.dataset.stock);
        let show = true;
        
        switch(stockFilter) {
            case 'available':
                show = stock > 0;
                break;
            case 'out':
                show = stock <= 0;
                break;
            case 'all':
            default:
                show = true;
        }
        
        product.style.display = show ? 'block' : 'none';
    });
}

function filterByKategori(kat) {
    const products = document.querySelectorAll('.product-card');
    products.forEach(product => {
        const kategori = product.dataset.kategori;
        if (kat === 'all' || kategori === kat) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
}

// Add to cart animation
document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        if (this.disabled) {
            e.preventDefault();
            return;
        }
        
        // Add loading state
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menambahkan...';
        this.disabled = true;
        
        // Simulate loading (remove in production)
        setTimeout(() => {
            this.innerHTML = '<i class="fas fa-check"></i> Ditambahkan!';
            setTimeout(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            }, 1000);
        }, 500);
    });
});
</script>

<?php include 'includes/footer.php'; ?> 