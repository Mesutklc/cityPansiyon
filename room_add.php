<?php
session_start();
require_once "config/db.php";

// Giriş kontrolü
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Form gönderildiyse
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_number = $_POST['room_number'];
    $type = $_POST['type'];
    $capacity = intval($_POST['capacity']);
    $price = floatval($_POST['price']);
    $status = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO rooms (room_number, type, capacity, price, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$room_number, $type, $capacity, $price, $status]);

    header("Location: rooms.php");
    exit;
}
?>

<!doctype html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Yeni Oda Ekle</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <h2>Yeni Oda Ekle</h2>
  <a href="rooms.php" class="btn btn-secondary btn-sm mb-3">← Geri</a>

  <form method="POST" class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Oda Numarası</label>
      <input type="text" name="room_number" class="form-control" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">Oda Türü</label>
      <input type="text" name="type" class="form-control" placeholder="Tek kişilik, çift kişilik..." required>
    </div>

    <div class="col-md-4">
      <label class="form-label">Kapasite</label>
      <input type="number" name="capacity" class="form-control" min="1" required>
    </div>

    <div class="col-md-4">
      <label class="form-label">Fiyat (₺)</label>
      <input type="number" name="price" step="0.01" class="form-control" required>
    </div>

    <div class="col-md-4">
      <label class="form-label">Durum</label>
      <select name="status" class="form-select">
        <option value="boş" selected>Boş</option>
        <option value="dolu">Dolu</option>
      </select>
    </div>

    <div class="col-12">
      <button type="submit" class="btn btn-success">Odayı Ekle</button>
    </div>
  </form>
</div>
</body>
</html>
