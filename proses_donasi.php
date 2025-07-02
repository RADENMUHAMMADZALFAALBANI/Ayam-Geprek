<?php
// Impor class PHPMailer ke dalam namespace global
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Memuat file autoloader PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Hubungkan ke file koneksi database
include 'koneksi.php';

// Cek jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- Menangkap Data Form ---
    $pilihan_paket = $_POST['paket'];
    $nama_donatur  = !empty($_POST['nama']) ? $_POST['nama'] : "Hamba Allah"; // Jika nama kosong, isi "Hamba Allah"
    $email_donatur = $_POST['email'];
    $jenis_donasi = $_POST['donasi'];
    $pesan         = $_POST['pesan'];
    $campaign_id   = $_POST['campaign_id'] ?? 0;

    // Konversi value dari form ke enum database
    if ($jenis_donasi == "donasi 1") {
        $jenis_donasi_enum = "fakir_miskin";
    } elseif ($jenis_donasi == "donasi 2") {
        $jenis_donasi_enum = "yatim_piatu";
    } else {
        $jenis_donasi_enum = "";
    }

    // --- Menentukan Nominal ---
    $jumlah_donasi = 0;
    if ($pilihan_paket == "Paket 1") $jumlah_donasi = 25000;
    elseif ($pilihan_paket == "Paket 2") $jumlah_donasi = 50000;
    elseif ($pilihan_paket == "Paket 3") $jumlah_donasi = 100000;
    
    // Validasi sederhana
    if ($jumlah_donasi == 0 || empty($email_donatur)) {
        // Kirim respon error jika data tidak valid
        header('Content-Type: application/json');
        echo json_encode(['status' => 'gagal', 'message' => 'Data tidak lengkap atau paket tidak valid.']);
        exit();
    }

    if ($jumlah_donasi == 0 || empty($email_donatur) || empty($jenis_donasi_enum)) {
        // Kirim respon error jika data tidak valid
        header('Content-Type: application/json');
        echo json_encode(['status' => 'gagal', 'message' => 'Data tidak lengkap atau paket/jenis donasi tidak valid.']);
        exit();
    }

    // --- Menyimpan Data ke Database ---
    $sql = "INSERT INTO tb_donasi (campaign_id, nama_donatur, email_donatur, pilihan_paket, jenis_donasi, jumlah_donasi, pesan) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "issssis", $campaign_id, $nama_donatur, $email_donatur, $pilihan_paket, $jenis_donasi_enum, $jumlah_donasi, $pesan);
    $sukses_db = mysqli_stmt_execute($stmt);

    // =======================================================
    // BAGIAN INI DIPERBARUI UNTUK MERESPON AJAX DENGAN JSON
    // =======================================================
    if ($sukses_db) {
        // Ambil ID dari donasi yang baru saja dimasukkan
        $last_id = mysqli_insert_id($koneksi);
        
        // Buat instance PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Konfigurasi Server SMTP Gmail (JANGAN LUPA GANTI DENGAN DATA ANDA)
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'email.gmail.anda@gmail.com'; // << GANTI DENGAN EMAIL GMAIL ANDA
            $mail->Password   = 'xxxxxxxxxxxxxxxx';         // << GANTI DENGAN 16 KARAKTER APP PASSWORD ANDA
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // Pengirim Email
            $mail->setFrom('email.gmail.anda@gmail.com', 'Admin CampusCare'); // << GANTI NAMA & EMAIL ANDA

            // 1. Kirim Email ke Donatur
            $mail->addAddress($email_donatur, $nama_donatur);
            $mail->isHTML(true);
            $mail->Subject = 'Terima Kasih Atas Donasi Anda - CampusCare';
            $mail->Body    = "<h2>Terima Kasih, $nama_donatur!</h2><p>Donasi Anda sebesar <b>Rp " . number_format($jumlah_donasi, 0, ',', '.') . "</b> telah kami catat.</p><p>Silakan selesaikan pembayaran melalui QRIS yang telah ditampilkan di website.</p><p>Semoga Allah membalas kebaikan Anda dengan berlipat ganda. Aamiin.</p><br><p>Hormat kami,<br>Tim CampusCare</p>";
            $mail->send();

            // 2. Kirim Email Notifikasi ke Admin
            $mail->clearAddresses();
            $mail->addAddress('email.gmail.anda@gmail.com'); // << GANTI DENGAN EMAIL ADMIN (ANDA)
            $mail->Subject = 'Notifikasi Donasi Baru Masuk!';
            $mail->Body    = "<h2>Ada Donasi Baru!</h2><p><b>Nama:</b> $nama_donatur</p><p><b>Email:</b> $email_donatur</p><p><b>Jumlah:</b> Rp " . number_format($jumlah_donasi, 0, ',', '.') . "</p><p><b>Pesan:</b> $pesan</p>";
            $mail->send();

        } catch (Exception $e) {
            // Proses tetap lanjut meskipun email gagal, agar tidak mengganggu user
            // Di aplikasi sungguhan, error ini sebaiknya dicatat (logging)
        }
        
        // Siapkan respon SUKSES dalam format JSON untuk AJAX
        $response = [
            'status' => 'sukses',
            'message' => 'Donasi berhasil dicatat.',
            'last_id' => $last_id
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();

    } else {
        // Jika gagal, kirim respon GAGAL dalam format JSON
        $response = [
            'status' => 'gagal',
            'message' => 'Gagal menyimpan data donasi ke database.'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}
?>