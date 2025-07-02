<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include '../koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. Ambil nama file gambar dari database sebelum record dihapus
    $stmt_select = $koneksi->prepare("SELECT gambar FROM tb_campaigns WHERE id = ?");
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    if ($result->num_rows === 1) {
        $campaign = $result->fetch_assoc();
        $gambar_path = "../" . $campaign['gambar'];
        
        // 2. Hapus file gambar dari folder 'uploads' jika file ada
        if (file_exists($gambar_path)) {
            unlink($gambar_path); // fungsi untuk menghapus file
        }
    }

    // 3. Hapus record dari database
    $stmt_delete = $koneksi->prepare("DELETE FROM tb_campaigns WHERE id = ?");
    $stmt_delete->bind_param("i", $id);
    $stmt_delete->execute();

    // Redirect kembali ke halaman utama admin
    header("Location: index.php");
    exit();
} else {
    // Jika tidak ada ID, kembali ke halaman utama
    header("Location: index.php");
    exit();
}
?>