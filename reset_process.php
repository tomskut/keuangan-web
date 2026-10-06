<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $new_password = $_POST['new_password'];

    if (!empty($username) && !empty($new_password)) {
        // Simulasi update password berhasil
        $_SESSION['success'] = "Password akun $username berhasil diperbarui! Silakan login.";
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['error'] = "Harap isi username dan password baru!";
        header("Location: index.php?page=reset");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}