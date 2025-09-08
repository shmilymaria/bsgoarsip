<?php
session_start();
require 'config.php';
if(!isset($_SESSION['user_id'])) header("Location: index.php");

// Ambil kata kunci jika ada
$keyword = $_GET['keyword'] ?? '';

if($keyword){
    $stmt = $pdo->prepare("
        SELECT sm.*, u.username
        FROM surat_masuk_eks sm
        JOIN users u ON sm.diupload_oleh = u.id
        WHERE sm.pengirim LIKE ?
           OR sm.waktu LIKE ?
           OR sm.perihal LIKE ?
           OR sm.no_surat LIKE ?
        ORDER BY sm.waktu DESC
    ");
    $stmt->execute(["%$keyword%","%$keyword%","%$keyword%","%$keyword%"]);
    $rows = $stmt->fetchAll();
} else {
    $rows = $pdo->query("
        SELECT sm.*, u.username
        FROM surat_masuk_eks sm
        JOIN users u ON sm.diupload_oleh = u.id
        ORDER BY sm.waktu DESC
    ")->fetchAll();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Surat Masuk Ekstern</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    .sidebar {
      background-color: #212529; /* sama dengan bg-dark */
      color: white;
      padding: 1rem;
      width: fit-content;    /* lebar menyesuaikan isi menu */
      min-width: 180px;      /* biar tidak terlalu kecil */
      height: auto;          /* tinggi ikut konten */
      min-height: 100vh;     /* tetap minimal setinggi layar */
    }
    .content {
      flex: 1;               /* konten ambil sisa ruang */
      padding: 1rem;
    }
    .sidebar a {
      display: flex;
      align-items: center;
      gap: 8px;              /* jarak ikon & teks */
      color: white;
      margin: 5px 0;
      padding: 8px 10px;
      border-radius: 6px;
      text-decoration: none;
      white-space: nowrap;   /* teks menu tidak pindah baris */
      transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .sidebar a i {
      transition: transform 0.3s ease;
    }
    .sidebar a:hover {
      background-color: #343a40; /* abu-abu lebih gelap saat hover */
      text-decoration: none;
    }
    .sidebar a:hover i {
      transform: translateX(5px); /* ikon geser kanan saat hover */
    }
    .sidebar a.text-danger:hover {
      background-color: #661d1d; /* merah gelap untuk logout */
    }
    .sidebar a.active {
      background-color: #495057; /* abu-abu gelap untuk menu aktif */
      font-weight: bold;
    }
  </style>
</head>
<body>
<div class="d-flex">

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>MENU</h2>
    <img src="assets/bsg.png" width="150" alt="Logo">

    <?php $current_page = basename($_SERVER['PHP_SELF']); ?>

    <a href="dashboard.php" class="<?= $current_page=='dashboard.php'?'active':'' ?>">
      <i class="bi bi-house-door"></i> Beranda
    </a>
    <a href="surat_masuk_in.php" class="<?= $current_page=='surat_masuk_in.php'?'active':'' ?>">
      <i class="bi bi-inbox"></i> Surat Masuk Intern
    </a>
    <a href="surat_masuk_eks.php" class="<?= $current_page=='surat_masuk_eks.php'?'active':'' ?>">
      <i class="bi bi-envelope"></i> Surat Masuk Ekstern
    </a>
    <a href="surat_keluar_in.php" class="<?= $current_page=='surat_keluar_in.php'?'active':'' ?>">
      <i class="bi bi-send"></i> Surat Keluar Intern
    </a>
    <a href="surat_keluar_eks.php" class="<?= $current_page=='surat_keluar_eks.php'?'active':'' ?>">
      <i class="bi bi-send-check"></i> Surat Keluar Ekstern
    </a>
    <?php if($_SESSION['role']=='admin'): ?>
      <a href="user.php" class="<?= $current_page=='user.php'?'active':'' ?>">
        <i class="bi bi-people"></i> Kelola User
      </a>
    <?php endif; ?>
    <a href="profil.php" class="<?= $current_page=='profil.php'?'active':'' ?>">
      <i class="bi bi-person-circle"></i> Profil Saya
    </a>
    <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
  <i class="bi bi-box-arrow-right"></i> Logout
</a>
  </div>

  <!-- Modal Konfirmasi Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <p>Apakah Anda yakin ingin keluar dari akun?</p>
      </div>
      <div class="modal-footer justify-content-center">
        <a href="logout.php" class="btn btn-danger">Ya, Keluar</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS (wajib untuk modal) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Content -->
  <div class="content">
    <h3>Arsip Surat Masuk Ekstern</h3>
<div class="d-flex justify-content-between align-items-center mb-3">

      <!-- Tombol Tambah -->
      <a href="proses_tambah.php?type=masuk_eks" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah
      </a>

<!-- Form Pencarian -->
<form method="GET" 
      class="d-flex flex-column flex-sm-row gap-2" 
      style="max-width:800px;">

  <input type="text" name="keyword" class="form-control" 
         placeholder="Cari surat..." value="<?= htmlspecialchars($keyword) ?>">

  <button type="submit" class="btn btn-outline-primary">
          <i class="bi bi-search"></i> Cari
        </button>

  <a href="surat_masuk_eks.php" class="btn btn-outline-secondary">Tampilkan Semua</a>
</form>
</div>

<table class="table table-bordered table-striped align-middle">
  <tr>
    <th class="text-nowrap">No</th>
    <th class="text-nowrap">Pengirim</th>
    <th class="text-nowrap">Waktu</th>
    <th class="text-nowrap">Perihal</th>
    <th class="text-nowrap">No. Surat</th>
    <th class="text-nowrap">File Surat</th>
    <?php if($_SESSION['role'] == 'admin'): ?>
      <th class="text-nowrap">Diupload oleh</th>
    <?php endif; ?>
    <th class="text-nowrap">Aksi</th>
  </tr>
  <?php 
  $total = count($rows);
  foreach($rows as $r): 
  ?>
  <tr>
    <td class="text-nowrap"><?= $total-- ?></td>
    <td class="text-nowrap"><?= $r['pengirim'] ?></td>
    <td class="text-nowrap"><?= $r['waktu'] ?></td>
    <td><?= $r['perihal'] ?></td>
    <td class="text-nowrap"><?= $r['no_surat'] ?></td>
    <td class="text-nowrap">
      <?php if($r['file_surat']): ?>
        <a href="<?= $r['file_surat'] ?>" target="_blank">Lihat File</a>
      <?php else: ?> - <?php endif; ?>
    </td>
    <?php if($_SESSION['role'] == 'admin'): ?>
      <td class="text-nowrap"><?= htmlspecialchars($r['username']) ?></td>
    <?php endif; ?>
    <td class="text-nowrap">
        <a href="proses_edit_surat.php?type=masuk_eks&id=<?= $r['id'] ?>" 
             class="btn btn-warning btn-sm">
            <i class="bi bi-pencil-square"></i> Edit
        </a>
          <button type="button" 
        class="btn btn-danger btn-sm" 
        data-bs-toggle="modal" 
        data-bs-target="#hapusModal" 
        data-id="<?= $r['id'] ?>">
  <i class="bi bi-trash"></i> Hapus
</button>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="hapusModal" tabindex="-1" aria-labelledby="hapusModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 shadow-lg border-0">
      <div class="modal-body text-center p-5">
        <div class="mb-3">
          <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 4rem;"></i>
        </div>
        <h4 class="mb-3">Hapus Data Surat</h4>
        <p class="text-muted">Apakah Anda yakin ingin menghapus data surat ini? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="d-flex justify-content-center gap-3 mt-4">
          <a href="#" id="btnHapus" class="btn btn-danger px-4">Ya, Hapus</a>
          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var hapusModal = document.getElementById('hapusModal');
  hapusModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget; 
    var id = button.getAttribute('data-id'); 
    var link = document.getElementById('btnHapus');
    link.href = "proses_hapus.php?type=masuk_eks&id=" + id;
  });
});
</script>
</body>
</html>
