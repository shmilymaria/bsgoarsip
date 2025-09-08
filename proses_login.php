<?php
session_start();
require 'config.php';

$username = $_POST['username'];
$password = $_POST['password'];

// Ambil user berdasarkan username
$sql = $pdo->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
$sql->execute([$username]);
$user = $sql->fetch();

if($user){
    $dbPass = $user['password'];

    if(password_verify($password, $dbPass) || $dbPass === md5($password)){
        // Jika masih MD5 → upgrade ke password_hash
        if($dbPass === md5($password)){
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET password=? WHERE id=?");
            $update->execute([$newHash, $user['id']]);
        }

        // Login sukses
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // 🔹 Tambahkan notifikasi login berhasil
        $_SESSION['success'] = "Login berhasil! Selamat datang, ".$user['username']." 👋";

        header("Location: dashboard.php");
        exit;
    }
}

// Jika gagal
$_SESSION['error'] = "Username atau password salah!";
header("Location: index.php");
exit;
