<?php
require_once '../includes/db.php';
$conn = get_db_connection();
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>
<?php include '../includes/functions.php'; ?>

<!-- Hero Section -->
<section class="hero">
  <div class="hero-content">
    <h1 class="hero-title">Sambal Hawa<br><span style="color:#388e3c;font-size:1.2rem;font-weight:400;">Pedasnya Bikin Nagih, Nikmatnya Nusantara</span></h1>
    <div class="hero-desc">Rasakan sensasi sambal khas Indonesia dengan cita rasa otentik, bahan alami, dan resep warisan keluarga. Cocok untuk semua hidangan!</div>
    <div class="hero-cta">
      <a href="products.php" class="btn"><i class="fa-solid fa-fire"></i> Lihat Varian</a>
      <a href="#promo" class="btn secondary"><i class="fa-solid fa-tags"></i> Promo Spesial</a>
    </div>
  </div>
  <img src="assets/img/sambal1.jpeg" alt="Sambal Hawa" class="hero-img" onerror="this.src='assets/images/sambal1.jpeg'">
</section>

<!-- Promo Section -->
<section class="promo-section" id="promo">
  <div class="promo-content">
    <div class="promo-title"><i class="fa-solid fa-gift"></i> Promo Lebaran: Diskon 50% untuk Sambal Edisi Spesial!</div>
    <div class="promo-desc">Nikmati sambal edisi Lebaran dengan harga spesial. Stok terbatas, buruan pesan sebelum kehabisan!</div>
  </div>
  <img src="assets/img/sambal3.jpeg" alt="Promo Sambal" class="promo-img" onerror="this.src='assets/images/sambal3.jpeg'">
</section>

<!-- Produk Unggulan -->
<h2 class="section-title">Varian Sambal Unggulan</h2>
<div class="produk-list">
<?php
$q = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC LIMIT 6");
while ($row = mysqli_fetch_assoc($q)) {
    echo '<div class="produk">';
    echo '<img src="assets/images/'.$row['gambar'].'" alt="'.$row['nama'].'">';
    echo '<h3>'.$row['nama'].'</h3>';
    echo '<p>'.substr($row['deskripsi'],0,50).'...</p>';
    echo '<p style="color:#388e3c;font-weight:700;">'.rupiah($row['harga']).'</p>';
    echo '<form method="post" action="cart.php">';
    echo '<input type="hidden" name="id_produk" value="'.$row['id'].'">';
    echo '<input type="number" name="kuantitas" value="1" min="1" max="'.$row['stok'].'" style="width:60px;"> ';
    echo '<button type="submit" name="add_to_cart" class="btn"><i class="fa-solid fa-cart-plus"></i> Tambah ke Keranjang</button>';
    echo '</form>';
    echo '</div>';
}
?>
</div>

<!-- Testimoni Section -->
<section class="testimoni-section">
  <h2 class="section-title">Apa Kata Pelanggan?</h2>
  <div class="testimoni-list">
    <div class="testimoni-card">
      <div><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
      "Pedasnya pas, rasa sambalnya benar-benar Indonesia banget! Sudah repeat order 3x."
      <div class="testi-name">- Rina, Jakarta</div>
    </div>
    <div class="testimoni-card">
      <div><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i></div>
      "Cocok buat lauk apapun, kemasannya juga aman. Anak kos wajib coba!"
      <div class="testi-name">- Dimas, Bandung</div>
    </div>
    <div class="testimoni-card">
      <div><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
      "Sambal edisi lebaran-nya juara, keluarga suka semua. Terima kasih Sambel Hawa!"
      <div class="testi-name">- Ibu Sari, Surabaya</div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?> 