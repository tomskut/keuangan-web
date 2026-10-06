<?php
session_start();
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($username) && !empty($password) && !empty($confirm_password)) {
        if ($password !== $confirm_password) {
            $_SESSION['error'] = "Konfirmasi password tidak cocok!";
            header("Location: index.php?page=register");
            exit;
        }

        // Cek apakah username sudah dipakai
        $stmt_check = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt_check, "s", $username);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $_SESSION['error'] = "Username sudah terdaftar! Gunakan username lain.";
            header("Location: index.php?page=register");
            exit;
        }

        // Hash password demi keamanan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Simpan user baru ke database
        $stmt = mysqli_prepare($conn, "INSERT INTO users (username, password) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ss", $username, $hashed_password);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Pendaftaran berhasil! Silakan login.";
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['error'] = "Gagal mendaftar, coba lagi!";
        }
    } else {
        $_SESSION['error'] = "Harap isi semua kolom!";
    }
}

header("Location: index.php?page=register");
exit;
?>