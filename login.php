<?php
session_start();
require_once "config/db.php";

$hata = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :u LIMIT 1");
    $stmt->execute(['u' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Giriş başarılı
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['username']  = $user['username'];
        $_SESSION['role']      = $user['role'];
        header("Location: dashboard.php");
        exit;
    } else {
        $hata = 'Kullanıcı adı veya şifre hatalı!';
    }
}
?>
<!doctype html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Giriş | City Pansiyon</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container">
  <div class="row justify-content-center align-items-center" style="height:100vh;">
    <div class="col-md-5">
      <div class="card shadow-lg p-4">
        <h3 class="text-center mb-4">Admin Giriş Paneli</h3>

        <?php if ($hata): ?>
          <div class="alert alert-danger"><?= $hata ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
          <div class="mb-3">
            <label for="username" class="form-label">Kullanıcı Adı</label>
            <input type="text" name="username" class="form-control" id="username" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Şifre</label>
            <input type="password" name="password" class="form-control" id="password" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
        </form>
      </div>
    </div>
  </div>
</div>

</body>
</html>
