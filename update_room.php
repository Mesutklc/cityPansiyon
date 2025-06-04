<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'error' => 'Yetkisiz erişim']);
    exit;
}

require 'config/db.php';

$id = $_POST['id'] ?? null;
$field = $_POST['field'] ?? null;
$value = $_POST['value'] ?? null;

// Sadece izin verilen alanlar
$allowed_fields = ['room_number', 'capacity','price', 'status'];

if (!$id || !$field || !in_array($field, $allowed_fields)) {
    echo json_encode(['success' => false, 'error' => 'Geçersiz parametre']);
    exit;
}

// Basit validasyon
if ($field === 'capacity' && (!is_numeric($value) || $value < 1)) {
    echo json_encode(['success' => false, 'error' => 'Kapasite geçerli sayı olmalı']);
    exit;
}
if ($field === 'price' && (!is_numeric($value) || $value < 1)) {
    echo json_encode(['success' => false, 'error' => 'Fiyat geçerli sayı olmalı']);
    exit;
}

if ($field === 'status' && !in_array($value, ['boş', 'dolu', 'rezerve'])) {
    echo json_encode(['success' => false, 'error' => 'Durum geçersiz']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE rooms SET $field = ? WHERE id = ?");
    $stmt->execute([$value, $id]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
