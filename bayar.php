<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Form</title>
    <!-- HAPUS SEMUA KODE CSS DI SINI DAN GANTI DENGAN LINK DI BAWAH INI -->
    <link rel="stylesheet" href="donasi.css"> 
</head>
<body>
    <!-- Perbaiki struktur navigasi. Jangan menumpuk <a> di dalam <a> -->
    <nav> <!-- Menggunakan tag nav terpisah untuk navigasi -->
        <a href="index.php" class="active">Beranda</a> <!-- Diubah dari index.html -->
        <a href="kontak.php">Kontak</a> <!-- Diubah dari kontak.html, asumsikan ada kontak.php -->
    </nav>

    <form action="aksi">
        <label for="paket">Pilih jumlah Paket:</label>
        <select name="paket" id="pkt">
            <option value="paket1">paket 1</option>
            <option value="paket2">paket 2</option>
            <option value="paket3">paket 3</option>
        </select>
        <br><br>
        
        <label for="pemabayaran">Metode pembayaran:</label>
        <select name="pemabayaran" id="pembayaran">
            <option value="pembayaran1">BRI</option>
            <option value="pembayaran2">mandiri</option>
            <option value="pembayaran3">qris</option>
        </select>
        <br><br>

        <label for="nama">Nama Lengkap</label>
        <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap">
        <br><br>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Masukkan email">
        <br><br>
        <label for="pesan">Pesan</label>
        <input type="text" id="pesan" name="pesan" placeholder="Tulis pesan Anda">
    </form>
</body>
</html>
