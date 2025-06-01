<?php
require 'config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$reservationId = intval($_GET['id']);

// 1. Rezervasyon bilgilerini çek
$stmt = $pdo->prepare("SELECT customer_id, price FROM reservations WHERE id = ?");
$stmt->execute([$reservationId]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    die("Rezervasyon bulunamadı.");
}

$customerId = intval($reservation['customer_id']);
$price      = floatval($reservation['price']);

// 2. Rezervasyonu sil
$stmtDelete = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
$success = $stmtDelete->execute([$reservationId]);

// 3. Müşteri bakiyesini güncelle (fiyatı geri al)
if ($success) {
    $stmtBalance = $pdo->prepare("UPDATE customers SET balance = balance - :amount WHERE id = :id");
    $stmtBalance->execute([
        'amount' => $price,
        'id'     => $customerId
    ]);

    header('Location: dashboard.php?deleted=1');
    exit;
} else {
    die("Silme işlemi başarısız.");
}
