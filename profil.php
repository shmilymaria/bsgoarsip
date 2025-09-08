<?php
session_start();
require 'config.php';
if(!isset($_SESSION['user_id'])) header("Location: index.php");

// Ambil data user yang sedang login
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Profil Saya</title>
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
    <h3>Profil Saya</h3>

    <!-- Alert feedback -->
    <?php if(isset($_GET['success'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_GET['success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>
    <?php if(isset($_GET['error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_GET['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <div class="card">
      <div class="card-body">
        <form action="proses_profil.php" method="post" id="formProfil">
          <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
          </div>

          <div class="mb-3">
            <label>Password Lama</label>
            <div class="input-group">
              <input type="password" name="password_lama" id="passwordLama" class="form-control" required>
              <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('passwordLama', this)">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>

          <div class="mb-3">
            <label>Password Baru (kosongkan jika tidak ganti)</label>
            <div class="input-group">
              <input type="password" name="password_baru" id="passwordBaru" class="form-control">
              <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('passwordBaru', this)">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>

          <div class="mb-3">
            <label>Konfirmasi Password Baru</label>
            <div class="input-group">
              <input type="password" name="password_konfirmasi" id="passwordKonfirmasi" class="form-control">
              <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('passwordKonfirmasi', this)">
                <i class="bi bi-eye"></i>
              </button>
            </div>
            <small id="passwordError" class="text-danger" style="display:none;">Password baru dan konfirmasi tidak cocok!</small>
          </div>

          <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function togglePassword(inputId, btn){
  const input = document.getElementById(inputId);
  const icon = btn.querySelector("i");
  if(input.type === "password"){
    input.type = "text";
    icon.classList.remove("bi-eye");
    icon.classList.add("bi-eye-slash");
  } else {
    input.type = "password";
    icon.classList.remove("bi-eye-slash");
    icon.classList.add("bi-eye");
  }
}

// Validasi password baru dan konfirmasi
document.getElementById("formProfil").addEventListener("submit", function(e){
  const passBaru = document.getElementById("passwordBaru").value;
  const passKonfirmasi = document.getElementById("passwordKonfirmasi").value;
  const errorMsg = document.getElementById("passwordError");

  if(passBaru !== "" || passKonfirmasi !== ""){
    if(passBaru !== passKonfirmasi){
      e.preventDefault(); // stop submit
      errorMsg.style.display = "block";
    } else {
      errorMsg.style.display = "none";
    }
  }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
