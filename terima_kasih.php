<?php
include 'koneksi.php';

// 1. Ambil ID donasi dari URL
$donasi_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($donasi_id == 0) {
    // Jika tidak ada ID, redirect ke halaman utama
    header("Location: index.php");
    exit();
}

// 2. Ambil detail donasi spesifik dari database
$sql = "SELECT d.*, c.judul as nama_campaign
        FROM tb_donasi d
        LEFT JOIN tb_campaigns c ON d.campaign_id = c.id
        WHERE d.id = ?";

$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "i", $donasi_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$donasi = mysqli_fetch_assoc($result);

// Jika donasi dengan ID tersebut tidak ditemukan
if (!$donasi) {
    echo "Data donasi tidak ditemukan.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih Atas Donasi Anda</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; box-sizing: border-box;}
        .receipt-container { background-color: white; padding: 40px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); max-width: 500px; width: 100%; text-align: center; }
        .receipt-container h1 { color: #28a745; margin-top: 0; }
        .receipt-container p { color: #555; line-height: 1.6; }
        .donation-details { text-align: left; margin: 30px 0; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc; padding: 20px 0; }
        .donation-details p { margin: 10px 0; }
        .donation-details strong { color: #333; }
        .payment-instruction { margin-top: 30px; }
        .qris-image { max-width: 250px; margin-top: 15px; }
        .btn-home { display: inline-block; margin-top: 30px; padding: 12px 25px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="receipt-container">
        <h1>Terima Kasih, <?php echo htmlspecialchars($donasi['nama_donatur']); ?>!</h1>
        <p>Alhamdulillah, niat baik Anda untuk berdonasi telah kami catat. Semoga Allah SWT membalasnya dengan kebaikan yang berlipat ganda.</p>
        
        <div class="donation-details">
            <p><strong>Detail Donasi Anda:</strong></p>
            <p><strong>Program:</strong> <?php echo htmlspecialchars($donasi['nama_campaign'] ?? 'Donasi Umum'); ?></p>
            <p><strong>Jumlah:</strong> Rp <?php echo number_format($donasi['jumlah_donasi'], 0, ',', '.'); ?></p>
            <p><strong>Waktu:</strong> <?php echo date('d F Y, H:i:s', strtotime($donasi['waktu_donasi'])); ?></p>
        </div>

        <div class="payment-instruction">
            <p>Silakan selesaikan donasi Anda dengan melakukan pembayaran melalui scan QRIS di bawah ini sesuai dengan jumlah di atas.</p>
            
            <img src="qris.jpg" alt="Scan QRIS untuk Pembayaran" class="qris-image">
            
        </div>

        <a href="index.php" class="btn-home">Kembali ke Beranda</a>
    </div>
</body>
</html>