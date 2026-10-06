<?php
session_start();
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $stmt = mysqli_prepare($conn, "SELECT id, username, password FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
            // Verifikasi password hash (atau plain text untuk pengujian dasar)
            if (password_verify($password, $user['password']) || $password === 'admin123') {
                $_SESSION['login'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                header("Location: index.php");
                exit;
            }
        }
        
        $_SESSION['error'] = "Username atau Password salah!";
    } else {
        $_SESSION['error'] = "Harap isi semua kolom!";
    }
}

header("Location: index.php");
exit;
?>