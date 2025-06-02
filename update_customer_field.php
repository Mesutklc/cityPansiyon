<?php
require 'config/db.php';

$id = $_POST['id'] ?? null;
$field = $_POST['field'] ?? '';
$value = $_POST['value'] ?? '';

$allowedFields = ['full_name', 'phone', 'email', 'tc'];
if (!in_array($field, $allowedFields)) {
    http_response_code(400);
    echo "Geçersiz alan";
    exit;
}

$stmt = $pdo->prepare("UPDATE customers SET $field = ? WHERE id = ?");
$success = $stmt->execute([$value, $id]);

echo $success ? "Güncellendi" : "Hata oluştu";
