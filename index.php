<?php
session_start();

// Giriş yapıldıysa doğrudan yönlendir
if (isset($_SESSION['user_id'])) {
    header("Location: rooms.php");
    exit;
}
?>

<!doctype html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>City Pansiyon - Hoş Geldiniz</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #74ebd5, #acb6e5);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .welcome-box {
      background-color: white;
      padding: 2rem;
      border-radius: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
      max-width: 500px;
      text-align: center;
    }
  </style>
</head>
<body>

<div class="welcome-box">
  <h1 class="mb-3">🏨 City Pansiyon</h1>
  <p class="lead">Yönetim paneline hoş geldiniz.</p>
  <a href="login.php" class="btn btn-primary btn-lg mt-3">Yönetici Girişi</a>
</div>

</body>
</html>
