<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // Cek user di database dengan role admin saja
    $query = "SELECT * FROM user WHERE username='$username' AND role='admin' LIMIT 1";
    $result = mysqli_query($koneksi, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        // Cek password tanpa hash (plain text)
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: riwayatdonasi.php");
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Akun admin tidak ditemukan!";
    }
} else {
    $error = "Akses tidak valid!";
}

// Jika gagal login, tampilkan pesan dan kembali ke login.html
echo "<script>alert('$error');window.location='login.html';</script>";
?>