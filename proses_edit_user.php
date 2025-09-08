<?php
session_start();
require 'config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role']!='admin'){
    header("Location: index.php");
    exit;
}

$id = $_GET['id'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? 'user'; // default user

if(!empty($password)){
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET username=?, password=?, role=? WHERE id=?");
    $stmt->execute([$username, $hashed, $role, $id]);
} else {
    $stmt = $pdo->prepare("UPDATE users SET username=?, role=? WHERE id=?");
    $stmt->execute([$username, $role, $id]);
}

header("Location: user.php");
exit;
