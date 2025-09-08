<?php
session_start();
require 'config.php';
if(!isset($_SESSION['user_id'])) header("Location: index.php");

$username = $_POST['username'] ?? '';
$password_lama = $_POST['password_lama'] ?? '';
$password_baru = $_POST['password_baru'] ?? '';
$password_konfirmasi = $_POST['password_konfirmasi'] ?? '';

// Ambil data user
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Cek password lama
if(!password_verify($password_lama, $user['password'])){
    header("Location: profil.php?error=Password lama salah!");
    exit;
}

// Jika password baru diisi, pastikan cocok
if(!empty($password_baru)){
    if($password_baru !== $password_konfirmasi){
        header("Location: profil.php?error=Konfirmasi password baru tidak cocok!");
        exit;
    }
    $hashed = password_hash($password_baru, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET username=?, password=? WHERE id=?");
    $stmt->execute([$username, $hashed, $_SESSION['user_id']]);
} else {
    // Update hanya username
    $stmt = $pdo->prepare("UPDATE users SET username=? WHERE id=?");
    $stmt->execute([$username, $_SESSION['user_id']]);
}

// Update session username biar langsung terasa
$_SESSION['username'] = $username;

header("Location: profil.php?success=Profil berhasil diperbarui!");
exit;
