<?php
// Cukup panggil satu file koneksi yang sudah benar.
include 'koneksi.php';

// Query untuk mengambil semua campaign yang aktif
$sql_campaigns = "SELECT * FROM tb_campaigns ORDER BY id DESC";
$result_campaigns = mysqli_query($koneksi, $sql_campaigns);

// Query untuk total donasi
$sql_total = "SELECT SUM(jumlah_donasi) as total_donasi FROM tb_donasi";
$result_total = mysqli_query($koneksi, $sql_total);
$data_total = mysqli_fetch_assoc($result_total);
$total_donasi = $data_total['total_donasi'] ?? 0;

// Query untuk 5 donatur terbaru
$sql_donatur = "SELECT nama_donatur, jumlah_donasi, pesan FROM tb_donasi ORDER BY id DESC LIMIT 5";
$result_donatur = mysqli_query($koneksi, $sql_donatur);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Donasi Zakat Dinamis</title>
    <link rel="stylesheet" href="donasi.css">
</head>
<body>
    
    <nav>
        <a href="index.php">Beranda</a>
        <a href="donasi.php" class="active">Zakat</a>
        <a href="kontak.php">Kontak</a> <!-- Diubah dari kontak.html -->
    </nav>

    <div class="main-container">

        <div class="content-wrapper">
            <div class="campaign-section">
                <h2>Pilih Program Donasi</h2>
                <div class="campaign-grid">
                    <?php
                    if (mysqli_num_rows($result_campaigns) > 0) {
                        while($campaign = mysqli_fetch_assoc($result_campaigns)) {
                    ?>
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($campaign['gambar']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($campaign['judul']); ?>">
                        <div class="card-body">
                            <h3 class="card-title"><?php echo htmlspecialchars($campaign['judul']); ?></h3>
                            <p><?php echo htmlspecialchars($campaign['deskripsi']); ?></p>
                            <a href="donasi.php?campaign_id=<?php echo $campaign['id']; ?>#form-donasi" class="btn-donasi">Donasi Program Ini</a>
                        </div>
                    </div>
                    <?php
                        }
                    } else {
                        echo "<p>Belum ada program donasi yang tersedia saat ini.</p>";
                    }
                    ?>
                </div>
            </div>

            <div class="form-section">
                <div class="form-container" id="form-donasi">
                    <h3>Formulir Donasi Cepat</h3>
                    <form action="proses_donasi.php" method="POST" id="donation-form">
                         <input type="hidden" name="campaign_id" value="<?php echo isset($_GET['campaign_id']) ? htmlspecialchars($_GET['campaign_id']) : '0'; ?>">
                        <label for="paket">Pilih Jumlah Paket:</label>
                        <select id="paket" name="paket" required>
                            <option value="">-- Pilih Paket --</option>
                            <option value="Paket 1">Paket 1 - Rp 25.000</option>
                            <option value="Paket 2">Paket 2 - Rp 50.000</option>
                            <option value="Paket 3">Paket 3 - Rp 100.000</option>
                        </select>
                        <label for="donasi">Pilih Jenis Donasi:</label>
                        <select id="donasi" name="donasi" required>
                            <option value="">-- Pilih Donasi --</option>
                            <option value="donasi 1">Donasi Fakir Miskin</option>
                            <option value="donasi 2">Donasi Yatim Piatu</option>
                        </select>
                        <label for="nama">Nama Anda:</label>
                        <input type="text" id="nama" name="nama" placeholder="Boleh dikosongkan (Hamba Allah)">
                        <label for="email">Email Anda:</label>
                        <input type="email" id="email" name="email" required>
                        <label for="pesan">Pesan (opsional):</label>
                        <textarea id="pesan" name="pesan" rows="2"></textarea>
                        <button type="submit">Kirim dan Lanjutkan Pembayaran</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="info-donasi">
            <div class="total-donasi">
                <h2>Total Donasi Terkumpul</h2>
                <p>Rp <?php echo number_format($total_donasi, 0, ',', '.'); ?></p>
            </div>
            <div class="donatur-list">
                <h3>5 Donatur Terbaru</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jumlah</th>
                            <th>Pesan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result_donatur) > 0) {
                            while($donatur = mysqli_fetch_assoc($result_donatur)) {
                                echo "<tr>";
                                $nama_tampil = !empty($donatur['nama_donatur']) ? htmlspecialchars($donatur['nama_donatur']) : "Hamba Allah";
                                echo "<td>" . $nama_tampil . "</td>";
                                echo "<td>Rp " . number_format($donatur['jumlah_donasi'], 0, ',', '.') . "</td>";
                                echo "<td>\"" . htmlspecialchars($donatur['pesan']) . "\"</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' style='text-align:center;'>Belum ada donatur. Jadilah yang pertama!</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const donationForm = document.getElementById('donation-form');
    if (donationForm) {
        const submitButton = donationForm.querySelector('button[type="submit"]');

        donationForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const originalButtonText = submitButton.textContent;
            submitButton.textContent = 'Memproses...';
            submitButton.disabled = true;

            const formData = new FormData(donationForm);

            fetch('proses_donasi.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'sukses') {
                    window.location.href = 'terima_kasih.php?id=' + data.last_id;
                } else {
                    alert('Terjadi kesalahan: ' + data.message);
                    submitButton.textContent = originalButtonText;
                    submitButton.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Tidak dapat terhubung ke server. Silakan coba lagi.');
                submitButton.textContent = originalButtonText;
                submitButton.disabled = false;
            });
        });
    }
});
</script>

</body>
</html>
<?php mysqli_close($koneksi); ?>
