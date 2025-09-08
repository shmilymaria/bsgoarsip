<?php
session_start();
require 'config.php';
if(!isset($_SESSION['user_id'])) header("Location: index.php");

$type = $_GET['type'] ?? '';
$id   = $_GET['id'] ?? '';

if($type=="masuk_in"){
    $stmt = $pdo->prepare("DELETE FROM surat_masuk_in WHERE id=?");
    $stmt->execute([$id]);
    header("Location: surat_masuk_in.php");
}
elseif($type=="masuk_eks"){
    $stmt = $pdo->prepare("DELETE FROM surat_masuk_eks WHERE id=?");
    $stmt->execute([$id]);
    header("Location: surat_masuk_eks.php");
}
elseif($type=="keluar_in"){
    $stmt = $pdo->prepare("DELETE FROM surat_keluar_in WHERE id=?");
    $stmt->execute([$id]);
    header("Location: surat_keluar_in.php");
}
elseif($type=="keluar_eks"){
    $stmt = $pdo->prepare("DELETE FROM surat_keluar_eks WHERE id=?");
    $stmt->execute([$id]);
    header("Location: surat_keluar_eks.php");
}
