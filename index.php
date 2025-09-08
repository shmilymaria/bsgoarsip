<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Login - Sistem Arsip Surat</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body {
      background: url('assets/background.jpg') no-repeat center center fixed;
      background-size: cover;
    }
  </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow-lg p-4" style="width: 25rem; background-color: rgba(255, 255, 255, 0.9);">
    <div class="text-center mb-3">
      <img src="assets/logo_bsg.png" width="120" alt="Logo">
      <h5 class="mt-2">Sistem Arsip Surat BSG</h5>
    </div>
    <?php if(isset($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <form action="proses_login.php" method="POST">
      <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
  </div>
</body>
</html>
