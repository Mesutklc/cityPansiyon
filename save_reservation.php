<?php
session_start();
require 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = $_POST['room_id'] ?? null;
    $date = $_POST['date'] ?? null;
    $customer_name = $_POST['customer_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $tc = $_POST['tc'] ?? '';
    $days = $_POST['days'] ?? 1;
    $price = $_POST['price'] ?? 0;
    $note = $_POST['note'] ?? '';
    $status = $_POST['status'] ?? 'rezerve';

    if (!$room_id || !$date || !$customer_name || !$phone || !$price) {
        $_SESSION['reservation_error'] = "Eksik bilgi girdiniz.";
        header("Location: dashboard.php");
        exit;
    }

    // Başlangıç ve bitiş tarihini hesapla
    $startDate = new DateTime($date);
    $endDate = (clone $startDate)->modify("+".((int)$days - 1)." days");

    try {
        $stmt = $pdo->prepare("INSERT INTO reservations 
            (room_id, customer_name, phone, email, tc, start_date, end_date, days, price, note, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $room_id,
            $customer_name,
            $phone,
            $email,
            $tc,
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d'),
            $days,
            $price,
            $note,
            $status
        ]);

        $_SESSION['reservation_success'] = "Rezervasyon başarıyla eklendi.";
    } catch (PDOException $e) {
        $_SESSION['reservation_error'] = "Veritabanı hatası: " . $e->getMessage();
    }

    header("Location: dashboard.php");
    exit;
} else {
    header("Location: dashboard.php");
    exit;
}
