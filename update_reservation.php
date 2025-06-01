<?php
require 'config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

$reservationId = intval($_POST['id'] ?? 0);
$customerId    = intval($_POST['customer_id'] ?? 0);
$roomId        = intval($_POST['room_id'] ?? 0);
$startDate     = $_POST['start_date'] ?? null;
$endDate       = $_POST['end_date'] ?? null;
$dailyPrice    = floatval($_POST['daily_price'] ?? 0);
$newTotal      = floatval($_POST['price'] ?? 0);

if (!$reservationId || !$customerId || !$roomId || !$startDate || !$endDate) {
    die("Eksik bilgi. Güncelleme yapılamıyor.");
}

// 1. reservations tablosundan eski fiyatı çek
$stmtOld = $pdo->prepare("SELECT price FROM reservations WHERE id = ?");
$stmtOld->execute([$reservationId]);
$oldData = $stmtOld->fetch(PDO::FETCH_ASSOC);

if (!$oldData) {
    die("Eski rezervasyon verisi alınamadı.");
}

$oldTotal = floatval($oldData['price']);

// 2. Rezervasyonu güncelle
$stmtUpdate = $pdo->prepare("
  UPDATE reservations 
  SET customer_id = :customer_id,
      room_id     = :room_id,
      start_date  = :start_date,
      end_date    = :end_date,
      price       = :price
  WHERE id = :id
");

$success = $stmtUpdate->execute([
    'customer_id' => $customerId,
    'room_id'     => $roomId,
    'start_date'  => $startDate,
    'end_date'    => $endDate,
    'price'       => $newTotal,
    'id'          => $reservationId
]);

// 3. Farkı hesapla (bakiye güncellemesi için)
$balanceChange = $newTotal - $oldTotal;

// 4. Müşteri bakiyesi güncelle
if ($success && $balanceChange != 0) {
    $stmtBalance = $pdo->prepare("UPDATE customers SET balance = balance + :diff WHERE id = :id");
    $stmtBalance->execute([
        'diff' => $balanceChange,
        'id'   => $customerId
    ]);
}

if ($success) {
    header('Location: dashboard.php?updated=1');
    exit;
} else {
    die("Güncelleme başarısız.");
}
