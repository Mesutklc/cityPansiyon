<?php
require 'config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: dashboard.php");
    exit;
}

$customerId   = $_POST['customer_id'] ?? null;
$roomId       = $_POST['room_id'] ?? null;
$startDate    = $_POST['start_date'] ?? null;
$endDate      = $_POST['end_date'] ?? null;
$dailyPrice   = floatval($_POST['daily_price'] ?? 0);
$totalPrice   = floatval($_POST['total_price'] ?? 0);
$note         = $_POST['note'] ?? '';
$status       = 'dolu';

// 1. Gerekli alan kontrolü
if (!$customerId || !$roomId || !$startDate || !$endDate || $totalPrice <= 0) {
    die("Eksik bilgi veya geçersiz toplam fiyat. Kayıt yapılamıyor.");
}

// 2. Rezervasyonu Kaydet
$stmt = $pdo->prepare("
    INSERT INTO reservations 
    (customer_id, room_id, start_date, end_date, price, note, status) 
    VALUES 
    (:customer_id, :room_id, :start_date, :end_date, :price, :note, :status)
");

$success = $stmt->execute([
    'customer_id' => $customerId,
    'room_id'     => $roomId,
    'start_date'  => $startDate,
    'end_date'    => $endDate,
    'price'       => $totalPrice,
    'note'        => $note,
    'status'      => $status
]);

// 3. Müşteri bakiyesi güncelleme
if ($success) {
    $stmtBalance = $pdo->prepare("UPDATE customers SET balance = balance + :amount WHERE id = :id");
    $stmtBalance->execute([
        'amount' => $totalPrice,
        'id'     => $customerId
    ]);

    header("Location: dashboard.php?added=1");
    exit;
} else {
    die("Rezervasyon kaydı başarısız.");
}
