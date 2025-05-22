<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!doctype html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Yönetim Paneli | City Pansiyon</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand">City Pansiyon | Yönetim Paneli</span>
    <span class="text-white">Hoş geldin, <?= htmlspecialchars($_SESSION['username']) ?></span>
    <a href="logout.php" class="btn btn-outline-light btn-sm">Çıkış</a>
  </div>
</nav>

<div class="container py-5">
  <h1>Kontrol Paneli</h1>
  <p>Burası gelecekte oda yönetimi, rezervasyon vb. sayfalara yönlendireceğimiz yer.</p>
</div>
</body>
</html>
