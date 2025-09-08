<?php
session_start();
require 'config.php';
if(!isset($_SESSION['user_id'])) header("Location: index.php");
if($_SESSION['role']!='admin') die("Akses ditolak!");

// ambil semua user
$rows = $pdo->query("SELECT * FROM users")->fetchAll();

// jika ada parameter id (untuk edit)
$editUser = null;
if(isset($_GET['id'])){
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$_GET['id']]);
    $editUser = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Kelola User</title>
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
    <h3>Kelola User</h3>
    
      <!-- Tombol Tambah -->
      <a href="proses_tambah.php?type=user" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah
      </a>

    <!-- Form Edit muncul hanya kalau ada ?id= -->
    <?php if($editUser): ?>
  <div class="card mb-3">
    <div class="card-body">
      <h5>Edit User</h5>
      <form action="proses_edit_user.php?id=<?= $editUser['id'] ?>" method="post">
        <div class="mb-3">
          <label>Username</label>
          <input type="text" name="username" class="form-control" 
                 value="<?= htmlspecialchars($editUser['username']) ?>" required>
        </div>
        <div class="mb-3">
          <label>Password Baru (kosongkan jika tidak diganti)</label>
          <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
          <label>Role</label>
          <select name="role" class="form-control" required>
            <option value="admin" <?= $editUser['role']=='admin' ? 'selected' : '' ?>>Admin</option>
            <option value="user" <?= $editUser['role']=='user' ? 'selected' : '' ?>>User</option>
          </select>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="user.php" class="btn btn-secondary">Batal</a>
      </form>
    </div>
  </div>
<?php endif; ?>

    <!-- Tabel User -->
    <table class="table table-bordered">
      <tr>
        <th>No</th><th>Username</th><th>Role</th><th>Aksi</th>
      </tr>
      <?php foreach($rows as $i=>$r): ?>
      <tr>
        <td><?= $i+1 ?></td>
        <td><?= $r['username'] ?></td>
        <td><?= $r['role'] ?></td>
        <td>
          <a href="user.php?id=<?= $r['id'] ?>" 
             class="btn btn-warning btn-sm">
            <i class="bi bi-pencil-square"></i> Edit
          </a>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>

</div>
</html>