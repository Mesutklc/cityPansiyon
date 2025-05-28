<?php



header('Content-Type: application/json');
require 'db.php';

// FormData ile geldiği için $_POST kullanılmalı
$reservationId = $_POST['id'] ?? null;
$newStatus = $_POST['status'] ?? null;
$cancelDate = $_POST['cancel_date'] ?? null;

if (!$reservationId || !$newStatus) {
    http_response_code(400);
    echo json_encode(['error' => 'Eksik parametre']);
    exit;
}

// Rezervasyonu getir
$stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ?");
$stmt->execute([$reservationId]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    http_response_code(404);
    echo json_encode(['error' => 'Rezervasyon bulunamadı']);
    exit;
}

// Müşteri bakiyesi güncelleme
function updateCustomerBalance($pdo, $customerId, $amount) {
    $stmt = $pdo->prepare("UPDATE customers SET bakiye = bakiye - ? WHERE id = ?");
    $stmt->execute([$amount, $customerId]);
}

// Günlük fiyatı hesapla
$start = new DateTime($reservation['start_date']);
$end = new DateTime($reservation['end_date']);
$totalDays = $start->diff($end)->days + 1;
$dailyPrice = $reservation['price'] / $totalDays;

// İptal işlemi
if ($newStatus === 'iptal' && $cancelDate) {
    $cancelDateObj = new DateTime($cancelDate);
    $startDateObj = new DateTime($reservation['start_date']);
    $endDateObj = new DateTime($reservation['end_date']);

    // Geçerli aralık kontrolü
    if ($cancelDateObj < $startDateObj || $cancelDateObj > $endDateObj) {
        http_response_code(400);
        echo json_encode(['error' => 'Tarih rezervasyon aralığında değil']);
        exit;
    }

    // Tek günlük rezervasyon tamamen iptal edilir
    if ($totalDays === 1) {
        $pdo->prepare("DELETE FROM reservations WHERE id = ?")->execute([$reservationId]);
        updateCustomerBalance($pdo, $reservation['customer_id'], $reservation['price']);
        echo json_encode(['success' => 'Rezervasyon iptal edildi']);
        exit;
    }

    // Başlangıç günü iptal
    if ($cancelDateObj == $startDateObj) {
        $newStart = (clone $startDateObj)->modify('+1 day');
        $newPrice = round($dailyPrice * ($totalDays - 1), 2);
        $pdo->prepare("UPDATE reservations SET start_date = ?, price = ? WHERE id = ?")
            ->execute([$newStart->format('Y-m-d'), $newPrice, $reservationId]);
        updateCustomerBalance($pdo, $reservation['customer_id'], $dailyPrice);
        echo json_encode(['success' => 'İlk gün iptal edildi']);
        exit;
    }

    // Bitiş günü iptal
    if ($cancelDateObj == $endDateObj) {
        $newEnd = (clone $endDateObj)->modify('-1 day');
        $newPrice = round($dailyPrice * ($totalDays - 1), 2);
        $pdo->prepare("UPDATE reservations SET end_date = ?, price = ? WHERE id = ?")
            ->execute([$newEnd->format('Y-m-d'), $newPrice, $reservationId]);
        updateCustomerBalance($pdo, $reservation['customer_id'], $dailyPrice);
        echo json_encode(['success' => 'Son gün iptal edildi']);
        exit;
    }

    // Ortadaki bir gün iptal edilirse rezervasyonu iki parçaya böl
    $beforeEnd = (clone $cancelDateObj)->modify('-1 day');
    $afterStart = (clone $cancelDateObj)->modify('+1 day');

    // 1. parça (başlangıçtan iptal gününün bir önceki gününe kadar)
    if ($startDateObj <= $beforeEnd) {
        $days1 = $startDateObj->diff($beforeEnd)->days + 1;
        $price1 = round($dailyPrice * $days1, 2);
        $pdo->prepare("INSERT INTO reservations 
            (room_id, customer_id, customer_name, phone, email, tc, start_date, end_date, price, note, status, payment_status, payment_amount, payment_method) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")
            ->execute([
                $reservation['room_id'],
                $reservation['customer_id'],
                $reservation['customer_name'],
                $reservation['phone'],
                $reservation['email'],
                $reservation['tc'],
                $startDateObj->format('Y-m-d'),
                $beforeEnd->format('Y-m-d'),
                $price1,
                $reservation['note'],
                $reservation['status'],
                $reservation['payment_status'],
                $reservation['payment_amount'],
                $reservation['payment_method']
            ]);
    }

    // 2. parça (iptal gününden sonraki günden bitiş gününe kadar)
    if ($afterStart <= $endDateObj) {
        $days2 = $afterStart->diff($endDateObj)->days + 1;
        $price2 = round($dailyPrice * $days2, 2);
        $pdo->prepare("INSERT INTO reservations 
            (room_id, customer_id, customer_name, phone, email, tc, start_date, end_date, price, note, status, payment_status, payment_amount, payment_method) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")
            ->execute([
                $reservation['room_id'],
                $reservation['customer_id'],
                $reservation['customer_name'],
                $reservation['phone'],
                $reservation['email'],
                $reservation['tc'],
                $afterStart->format('Y-m-d'),
                $endDateObj->format('Y-m-d'),
                $price2,
                $reservation['note'],
                $reservation['status'],
                $reservation['payment_status'],
                $reservation['payment_amount'],
                $reservation['payment_method']
            ]);
    }

    // Orijinal rezervasyon silinir
    $pdo->prepare("DELETE FROM reservations WHERE id = ?")->execute([$reservationId]);

    updateCustomerBalance($pdo, $reservation['customer_id'], $dailyPrice);
    echo json_encode(['success' => 'Orta gün iptal edildi']);
    exit;
}

// Statü güncelleme (örneğin sadece "onaylı", "bekliyor", vs.)
$pdo->prepare("UPDATE reservations SET status = ? WHERE id = ?")->execute([$newStatus, $reservationId]);
echo json_encode(['success' => 'Durum güncellendi']);
