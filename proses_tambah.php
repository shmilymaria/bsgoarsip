<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
}

$type = $_GET['type'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uploadDir = "uploads/"; // folder penyimpanan file
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = null;
    if (isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] == 0) {
        $ext      = pathinfo($_FILES['file_surat']['name'], PATHINFO_EXTENSION);
        $fileName = $uploadDir . time() . "_" . rand(100, 999) . "." . $ext;
        move_uploaded_file($_FILES['file_surat']['tmp_name'], $fileName);
    }

    if ($type == "masuk_in") {
        $stmt = $pdo->prepare("
            INSERT INTO surat_masuk_in
                (pengirim, waktu, perihal, no_surat, file_surat, diupload_oleh)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $_POST['pengirim'],
            $_POST['waktu'],
            $_POST['perihal'],
            $_POST['no_surat'],
            $fileName,
            $_SESSION['user_id'] // <-- tambahan
        ]);
        header("Location: surat_masuk_in.php");
    } elseif ($type == "masuk_eks") {
        $stmt = $pdo->prepare("
            INSERT INTO surat_masuk_eks
                (pengirim, waktu, perihal, no_surat, file_surat, diupload_oleh)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $_POST['pengirim'],
            $_POST['waktu'],
            $_POST['perihal'],
            $_POST['no_surat'],
            $fileName,
            $_SESSION['user_id'] // <-- tambahan
        ]);
        header("Location: surat_masuk_eks.php");
    } elseif ($type == "keluar_in") {
        $stmt = $pdo->prepare("
            INSERT INTO surat_keluar_in
                (penerima, waktu, perihal, no_surat, file_surat, diupload_oleh)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $_POST['penerima'],
            $_POST['waktu'],
            $_POST['perihal'],
            $_POST['no_surat'],
            $fileName,
            $_SESSION['user_id'] // <-- tambahan
        ]);
        header("Location: surat_keluar_in.php");
    } elseif ($type == "keluar_eks") {
        $stmt = $pdo->prepare("
            INSERT INTO surat_keluar_eks
                (penerima, waktu, perihal, no_surat, file_surat, diupload_oleh)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $_POST['penerima'],
            $_POST['waktu'],
            $_POST['perihal'],
            $_POST['no_surat'],
            $fileName,
            $_SESSION['user_id'] // <-- tambahan
        ]);
        header("Location: surat_keluar_eks.php");
    } elseif ($type == "user" && $_SESSION['role'] == 'admin') {
        $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT); // versi aman
        $stmt = $pdo->prepare("
            INSERT INTO users (username, password, role)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([
            $_POST['username'],
            md5($_POST['password']), // <-- tambahkan md5 di sini
            $_POST['role']
        ]);
        header("Location: user.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
    <h3>Tambah Data <?= ucfirst($type) ?></h3>
    <a href="dashboard.php" class="btn btn-secondary mb-2">Kembali</a>

    <form method="POST" enctype="multipart/form-data">
        <?php if ($type == "masuk_in"): ?>
            <div class="mb-2"><label>Pengirim</label><input type="text" name="pengirim" class="form-control" required></div>
            <div class="mb-2"><label>Waktu</label><input type="date" name="waktu" class="form-control" required></div>
            <div class="mb-2"><label>Perihal</label><input type="text" name="perihal" class="form-control" required></div>
            <div class="mb-2"><label>No Surat</label><input type="text" name="no_surat" class="form-control" required></div>
            <div class="mb-2"><label>Upload File Surat</label><input type="file" name="file_surat" class="form-control"></div>

        <?php elseif ($type == "masuk_eks"): ?>
            <div class="mb-2"><label>Pengirim</label><input type="text" name="pengirim" class="form-control" required></div>
            <div class="mb-2"><label>Waktu</label><input type="date" name="waktu" class="form-control" required></div>
            <div class="mb-2"><label>Perihal</label><input type="text" name="perihal" class="form-control" required></div>
            <div class="mb-2"><label>No Surat</label><input type="text" name="no_surat" class="form-control" required></div>
            <div class="mb-2"><label>Upload File Surat</label><input type="file" name="file_surat" class="form-control"></div>

        <?php elseif ($type == "keluar_in"): ?>
            <div class="mb-2"><label>Penerima</label><input type="text" name="penerima" class="form-control" required></div>
            <div class="mb-2"><label>Waktu</label><input type="date" name="waktu" class="form-control" required></div>
            <div class="mb-2"><label>Perihal</label><input type="text" name="perihal" class="form-control" required></div>
            <div class="mb-2"><label>No Surat</label><input type="text" name="no_surat" class="form-control" required></div>
            <div class="mb-2"><label>Upload File Surat</label><input type="file" name="file_surat" class="form-control"></div>

        <?php elseif ($type == "keluar_eks"): ?>
            <div class="mb-2"><label>Penerima</label><input type="text" name="penerima" class="form-control" required></div>
            <div class="mb-2"><label>Waktu</label><input type="date" name="waktu" class="form-control" required></div>
            <div class="mb-2"><label>Perihal</label><input type="text" name="perihal" class="form-control" required></div>
            <div class="mb-2"><label>No Surat</label><input type="text" name="no_surat" class="form-control" required></div>
            <div class="mb-2"><label>Upload File Surat</label><input type="file" name="file_surat" class="form-control"></div>

        <?php elseif ($type == "user" && $_SESSION['role'] == 'admin'): ?>
            <div class="mb-2"><label>Username</label><input type="text" name="username" class="form-control" required></div>
            <div class="mb-2"><label>Password</label><input type="password" name="password" class="form-control" required></div>
            <div class="mb-2">
                <label>Role</label>
                <select name="role" class="form-control">
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</body>
</html>