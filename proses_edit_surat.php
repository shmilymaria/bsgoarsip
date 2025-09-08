<?php
session_start();
require 'config.php';
if(!isset($_SESSION['user_id'])) header("Location: index.php");

$type = $_GET['type'] ?? '';
$id   = $_GET['id'] ?? '';

$tables = [
    'masuk_in'  => 'surat_masuk_in',
    'masuk_eks' => 'surat_masuk_eks',
    'keluar_in' => 'surat_keluar_in',
    'keluar_eks'=> 'surat_keluar_eks'
];

if(!isset($tables[$type])) die("Tipe surat tidak valid");
$table = $tables[$type];

// ===== PROSES SIMPAN (POST) =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $waktu    = $_POST['waktu'] ?? '';
    $perihal  = $_POST['perihal'] ?? '';
    $no_surat = $_POST['no_surat'] ?? '';
    $filePath = null;

    // Kolom khusus
    if(in_array($type, ['masuk_in','masuk_eks'])){
        $kolomUtama = 'pengirim';
        $nilaiUtama = $_POST['pengirim'] ?? '';
    } else {
        $kolomUtama = 'penerima';
        $nilaiUtama = $_POST['penerima'] ?? '';
    }

    // Upload file jika ada
    if(!empty($_FILES['file_surat']['name'])){
        $targetDir = "uploads/";
        if(!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileName  = time().'_'.basename($_FILES['file_surat']['name']);
        $targetFile = $targetDir.$fileName;

        if(move_uploaded_file($_FILES['file_surat']['tmp_name'], $targetFile)){
            $filePath = $targetFile;
        }
    }

    // Query update
    if($filePath){
        $stmt = $pdo->prepare("UPDATE {$table} SET {$kolomUtama}=?, waktu=?, perihal=?, no_surat=?, file_surat=? WHERE id=?");
        $stmt->execute([$nilaiUtama, $waktu, $perihal, $no_surat, $filePath, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE {$table} SET {$kolomUtama}=?, waktu=?, perihal=?, no_surat=? WHERE id=?");
        $stmt->execute([$nilaiUtama, $waktu, $perihal, $no_surat, $id]);
    }

    header("Location: surat_{$type}.php");
    exit;
}

// ===== TAMPILKAN FORM EDIT (GET) =====
$stmt = $pdo->prepare("SELECT * FROM {$table} WHERE id=?");
$stmt->execute([$id]);
$surat = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$surat) die("Data tidak ditemukan");

// Tentukan label field
if(in_array($type, ['masuk_in','masuk_eks'])){
    $labelUtama = "Pengirim";
    $kolomUtama = "pengirim";
} else {
    $labelUtama = "Penerima";
    $kolomUtama = "penerima";
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Surat</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
  <h3>Edit Surat (<?= htmlspecialchars($type) ?>)</h3>
  <form action="proses_edit_surat.php?type=<?= $type ?>&id=<?= $id ?>" method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label><?= $labelUtama ?></label>
      <input type="text" name="<?= $kolomUtama ?>" class="form-control" value="<?= htmlspecialchars($surat[$kolomUtama] ?? '') ?>" required>
    </div>
    <div class="mb-3">
      <label>Waktu</label>
      <input type="date" name="waktu" class="form-control" value="<?= htmlspecialchars($surat['waktu'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
      <label>Perihal</label>
      <input type="text" name="perihal" class="form-control" value="<?= htmlspecialchars($surat['perihal'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
      <label>No. Surat</label>
      <input type="text" name="no_surat" class="form-control" value="<?= htmlspecialchars($surat['no_surat'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
      <label>File Surat (kosongkan jika tidak diganti)</label>
      <input type="file" name="file_surat" class="form-control">
      <?php if(!empty($surat['file_surat'])): ?>
        <small>File saat ini: <a href="<?= $surat['file_surat'] ?>" target="_blank">Lihat</a></small>
      <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <a href="surat_<?= $type ?>.php" class="btn btn-secondary">Batal</a>
  </form>
</body>
</html>
