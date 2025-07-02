<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include '../koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit();
}

// Ambil data campaign yang akan diedit
$stmt = $koneksi->prepare("SELECT * FROM tb_campaigns WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$campaign = $result->fetch_assoc();

if (!$campaign) {
    echo "Campaign tidak ditemukan!";
    exit();
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $gambar_path = $campaign['gambar']; // Gunakan gambar lama sebagai default

    // Jika ada gambar baru di-upload, proses seperti di tambah.php
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . time() . '-' . basename($_FILES["gambar"]["name"]);
        
        // Hapus gambar lama
        if (file_exists("../" . $campaign['gambar'])) {
            unlink("../" . $campaign['gambar']);
        }
        
        // Upload gambar baru
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $gambar_path = "uploads/" . time() . '-' . basename($_FILES["gambar"]["name"]);
        } else {
            $error = "Gagal mengupload gambar baru.";
        }
    }

    if (empty($error)) {
        $stmt_update = $koneksi->prepare("UPDATE tb_campaigns SET judul = ?, deskripsi = ?, gambar = ? WHERE id = ?");
        $stmt_update->bind_param("sssi", $judul, $deskripsi, $gambar_path, $id);
        if ($stmt_update->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Gagal mengupdate data.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Campaign</title>
    <!-- HAPUS SEMUA KODE CSS DI SINI DAN GANTI DENGAN LINK DI BAWAH INI -->
    <link rel="stylesheet" href="../styles.css"> <!-- Perhatikan jalur ke styles.css. Karena file ini di folder 'admin', perlu '..' untuk naik satu level -->
</head>
<body>
    <div class="admin-header"><h1>Edit Campaign: <?php echo htmlspecialchars($campaign['judul']); ?></h1></div>
    <div class="admin-container">
        <?php if($error): ?><p class="error"><?php echo $error; ?></p><?php endif; ?>
        <form action="edit.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="judul">Judul Campaign</label>
                <input type="text" id="judul" name="judul" value="<?php echo htmlspecialchars($campaign['judul']); ?>" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" required><?php echo htmlspecialchars($campaign['deskripsi']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="gambar">Gambar Campaign (Kosongkan jika tidak ingin ganti)</label>
                <input type="file" id="gambar" name="gambar">
                <p>Gambar saat ini:</p>
                <img src="../<?php echo htmlspecialchars($campaign['gambar']); ?>" alt="Gambar saat ini" class="current-img">
            </div>
            <button type="submit" class="admin-btn">Update Campaign</button>
            <a href="index.php" style="margin-left:10px;">Batal</a>
        </form>
    </div>
</body>
</html>
