<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Giriş yapılmamış']);
    exit;
}

require 'config/db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['reservation_id'])) {
    echo json_encode(['success' => false, 'message' => 'Geçersiz veri']);
    exit;
}

$reservation_id = (int)$data['reservation_id'];


$stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
$deleted = $stmt->execute([$reservation_id]);

if ($deleted) {
    echo json_encode(['success' => true, 'message' => 'Rezervasyon başarıyla iptal edildi']);
} else {
    echo json_encode(['success' => false, 'message' => 'İptal sırasında hata oluştu']);
}
