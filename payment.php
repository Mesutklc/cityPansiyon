<?php
require 'config/db.php';
include "header.php";

$reservation_id = $_GET['reservation_id'] ?? null;
if (!$reservation_id) {
    die("Geçersiz rezervasyon ID");
}

// Rezervasyon ve müşteri bilgileri
$stmt = $pdo->prepare("
    SELECT r.*, c.full_name, c.id AS customer_id 
    FROM reservations r
    JOIN customers c ON r.customer_id = c.id
    WHERE r.id = ?
");
$stmt->execute([$reservation_id]);
$reservation = $stmt->fetch();

if (!$reservation) {
    die("Rezervasyon bulunamadı");
}

// Kasa türlerini çek
$accounts = $pdo->query("SELECT id, name FROM cash_accounts")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Ödeme Al</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-4">
  <h2>Ödeme Al</h2>
  <form action="save_payment.php" method="POST">
    <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
    <input type="hidden" name="customer_id" value="<?= $reservation['customer_id'] ?>">

    <div class="mb-3">
      <label>Müşteri</label>
      <input type="text" class="form-control" value="<?= $reservation['full_name'] ?>" readonly>
    </div>

    <div class="mb-3">
      <label>Ödeme Tutarı (₺)</label>
      <input type="number" name="amount" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Ödeme Yöntemi (Kasa Tipi)</label>
      <select name="cash_account_id" class="form-control" required>
        <option value="">-- Seçin --</option>
        <?php foreach ($accounts as $account): ?>
          <option value="<?= $account['id'] ?>"><?= $account['name'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <button type="submit" class="btn btn-success">Ödemeyi Kaydet</button>
  </form>
</div>
</body>
</html>
