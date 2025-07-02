<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include '../koneksi.php';

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $gambar_path = '';

    // Proses upload gambar
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "../uploads/"; // Folder upload di luar folder admin
        // Buat nama file unik untuk menghindari tumpukan nama yang sama
        $target_file = $target_dir . time() . '-' . basename($_FILES["gambar"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Cek apakah file adalah gambar asli
        $check = getimagesize($_FILES["gambar"]["tmp_name"]);
        if ($check !== false) {
            // Pindahkan file dari temporary ke folder uploads
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                // Simpan path relatifnya saja untuk database
                $gambar_path = "uploads/" . time() . '-' . basename($_FILES["gambar"]["name"]);
            } else {
                $error = "Maaf, terjadi kesalahan saat mengupload file.";
            }
        } else {
            $error = "File yang diupload bukan gambar.";
        }
    } else {
        $error = "Gambar wajib diupload.";
    }

    // Jika tidak ada error, masukkan data ke database
    if (empty($error)) {
        $stmt = $koneksi->prepare("INSERT INTO tb_campaigns (judul, deskripsi, gambar) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $judul, $deskripsi, $gambar_path);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Gagal menyimpan data ke database.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Campaign Baru</title>
    <style>
        body { font-family: sans-serif; margin: 0; background: #f9f9f9; }
        .header { background: #333; color: white; padding: 15px 20px; }
        .header h1 { margin: 0; }
        .container { padding: 20px; max-width: 600px; margin: auto; background: white; margin-top: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn { padding: 10px 15px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="header"><h1>Tambah Campaign Baru</h1></div>
    <div class="container">
        <?php if($error): ?><p class="error"><?php echo $error; ?></p><?php endif; ?>
        <form action="tambah.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="judul">Judul Campaign</label>
                <input type="text" id="judul" name="judul" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="gambar">Gambar Campaign</label>
                <input type="file" id="gambar" name="gambar" required>
            </div>
            <button type="submit" class="btn">Simpan Campaign</button>
            <a href="index.php" style="margin-left:10px;">Batal</a>
        </form>
    </div>
</body>
</html>