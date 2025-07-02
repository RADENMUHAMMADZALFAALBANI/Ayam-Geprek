<?php
// Hubungkan ke database
include 'koneksi.php';

// Query untuk mengambil beberapa campaign (misal: 3 terbaru)
// dan menghitung total donasi untuk masing-masing campaign
$sql = "SELECT c.*, SUM(d.jumlah_donasi) as total_terkumpul
        FROM tb_campaigns c
        LEFT JOIN tb_donasi d ON c.id = d.campaign_id
        GROUP BY c.id
        ORDER BY c.id DESC
        LIMIT 3"; // Kita batasi 3 campaign teratas untuk ditampilkan di beranda
$result_campaigns = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CampusCare - Donasi Mudah dan Terpercaya</title>
  <!-- Pastikan link ke styles.css ini ada dan jalurnya benar -->
   <link rel="stylesheet" href="style.css">
</head>
<body>

  <header class="header">
    <a href="index.php" class="logo-link">
      <img src="logo.jpg" alt="Logo" class="logo">
    </a>
    
    <nav class="main-nav">
      <a href="index.php" class="active">Beranda</a>
     <a href="https://wa.me/6289534081336" target="_blank">Kontak</a>
     <a href="login.html" class="login-btn">Login</a>
    </nav>
  </header>

  <section class="banner">
    <a href="donasi.php">
      <img src="banner.png" alt="Formulir Donasi">
    </a>
  </section>

  <nav class="nav-buttons">
    <a href="donasi.php" class="donation-btn">INFAQ</a>
    <a href="donasi.php" class="donation-btn">ZAKAT</a>
    <a href="donasi.php" class="donation-btn">DONASI</a>
  </nav>

  <?php
  // Cek apakah ada campaign untuk ditampilkan
  if (mysqli_num_rows($result_campaigns) > 0) {
      // Lakukan perulangan untuk setiap campaign
      while($campaign = mysqli_fetch_assoc($result_campaigns)) {
  ?>

  <section class="donation-card">
    <a href="donasi.php?campaign_id=<?php echo $campaign['id']; ?>#form-donasi">
      <img src="<?php echo htmlspecialchars($campaign['gambar']); ?>" alt="<?php echo htmlspecialchars($campaign['judul']); ?>" class="donation-image">
    </a>

    <div class="donation-content">
      <a href="donasi.php?campaign_id=<?php echo $campaign['id']; ?>#form-donasi" class="card-link">
        <h2><?php echo htmlspecialchars($campaign['judul']); ?></h2>
        <p class="status">
          <span class="dot"></span> 
          Rp <span class="amount"><?php echo number_format($campaign['total_terkumpul'] ?? 0, 0, ',', '.'); ?></span> terkumpul
        </p>
      </a>
      <a href="donasi.php?campaign_id=<?php echo $campaign['id']; ?>#form-donasi" class="donate-button">Donate Now</a>
    </div>
  </section>

  <?php
      } // Akhir dari while loop
  } else {
      // Tampilkan pesan ini jika tidak ada campaign sama sekali di database
      echo "<p style='text-align:center; padding: 20px;'>Belum ada program donasi yang tersedia.</p>";
  }
  ?>

  <footer>
    <div class="footer-content">
      <p>CampusCare UAD</p>
      <p>Jl. Kapas No.9, Karangmalang, Kec. Sapen, Kota Yogyakarta, Daerah Istimewa Yogyakarta 55281</p>
    <br>
      <br>
    <center><p>&copy; <?php echo date("Y"); ?> CampusCare UAD. All rights reserved.</p></center> 
  </footer>
  <script>
    // Dark mode toggle logic
    const toggleBtn = document.getElementById('toggle-darkmode');
    const body = document.body;
    // Cek preferensi sebelumnya
    if(localStorage.getItem('theme') === 'dark') {
      body.classList.add('darkmode');
    }
    toggleBtn.onclick = function() {
      body.classList.toggle('darkmode');
      // Simpan preferensi
      if(body.classList.contains('darkmode')) {
        localStorage.setItem('theme', 'dark');
      } else {
        localStorage.setItem('theme', 'light');
      }
    }
  </script>
</body>
</html>
