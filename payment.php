<?php
require 'config/db.php';
$reservation_id = $_GET['reservation_id'] ?? null;
include "header.php";

if (!$reservation_id) {
    echo "Rezervasyon ID eksik!";
    exit;
}

$res = $pdo->prepare("SELECT * FROM reservations WHERE id = ?");
$res->execute([$reservation_id]);
$reservation = $res->fetch();

if (!$reservation) {
    echo "Rezervasyon bulunamadı!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['payment_amount'];
    $method = $_POST['payment_method'];
    $paid_days = $_POST['paid_days'];

    $update = $pdo->prepare("UPDATE reservations SET payment_status = ?, payment_amount = ?, payment_method = ?, paid_days = ? WHERE id = ?");
    $update->execute(['ödendi', $amount, $method, $paid_days, $reservation_id]);

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ödeme</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container my-5">
    <h2>Ödeme Bilgisi</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Ödeme Tutarı (₺)</label>
            <input type="number" name="payment_amount" class="form-control" required />
        </div>
        <div class="mb-3">
            <label class="form-label">Ödeme Yöntemi</label>
            <select name="payment_method" class="form-select" required>
                <option value="nakit">Nakit</option>
                <option value="kredi kartı">Kredi Kartı</option>
                <option value="havale">Havale</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Kaç Günlük Ödeme Yapıldı?</label>
            <input type="number" name="paid_days" class="form-control" min="1" value="<?= $reservation['days'] ?>" required />
        </div>
        <button type="submit" class="btn btn-primary">Ödemeyi Kaydet</button>
    </form>
</div>
</body>
</html>
