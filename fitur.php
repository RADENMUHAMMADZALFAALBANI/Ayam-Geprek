<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CampusCare</title>
  <!-- Pastikan link ke styles.css ini ada dan jalurnya benar -->
  <link rel="stylesheet" href="style.css"> 
</head>
<body>

  <!-- Header Section -->
  <header class="header">
    <a href="index.php" class="logo-link"> <!-- Diubah dari index.html -->
      <img src="logo.jpg" alt="Logo" class="logo">
    </a>
    
    <nav class="main-nav">
      <a href="index.php" class="active">Beranda</a> <!-- Diubah dari index.html -->
      <a href="kontak.php">Kontak</a> <!-- Diubah dari kontak.html, asumsikan ada kontak.php -->
    </nav>
  </header>

  <!-- Banner Section -->
  <section class="banner">
    <a href="donasi.php"> <!-- Diubah dari program.html -->
      <img src="banner.png" alt="Formulir Donasi">
    </a>
  </section>

  <!-- Donation Buttons -->
  <nav class="nav-buttons">
    <a href="donasi.php" class="donation-btn">INFAQ</a> <!-- Diubah dari infaq.html -->
    <a href="donasi.php" class="donation-btn">ZAKAT</a> <!-- Diubah dari zakat.html -->
    <a href="donasi.php" class="donation-btn">DONASI</a> <!-- Diubah dari donasi.html -->
  </nav>

  <!-- Donation Card -->
  <section class="donation-card">
    <a href="donasi.php"> <!-- Diubah dari zakat-details.html -->
      <img src="images.jpeg" alt="Progress Zakat" class="donation-image">
    </a>

    <div class="donation-content">
      <a href="donasi.php" class="card-link"> <!-- Diubah dari zakat-details.html -->
        <h2>Zakat Fitrah</h2>
        <p class="status">
          <span class="dot"></span> 
          Rp <span class="amount">0</span> terkumpul
        </p>
      </a>
      <a href="bayar.php" class="donate-button">Donate Now</a> <!-- Diubah dari bayar.html -->
    </div>
  </section>

  <footer>
    <div class="footer-content">
      <p>CampusCare UAD</p>
      <p>Jl. Kapas No.9, Karangmalang, Kec. Sapen, Kota Yogyakarta, Daerah Istimewa Yogyakarta 55281</p>
   <br>
      <br>
   <center><p>&copy; 2025 CampusCare UAD. All rights reserved.</p></center> 
  </footer>

</body>
</html>
