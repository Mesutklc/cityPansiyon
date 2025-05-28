<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['admin_logged_in'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Yetkisiz erişim']);
    exit;
}

require 'config/db.php';

$term = isset($_GET['term']) ? trim($_GET['term']) : '';

if ($term === '') {
    echo json_encode(['results' => []]);
    exit;
}

$stmt = $pdo->prepare("SELECT id, full_name, phone, email, tc FROM customers WHERE full_name LIKE ? OR phone LIKE ? LIMIT 10");
$searchTerm = "%$term%";
$stmt->execute([$searchTerm, $searchTerm]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = [];
foreach ($results as $row) {
    $data[] = [
        'id' => $row['id'],
        'text' => $row['full_name'],
        'phone' => $row['phone'],
        'email' => $row['email'],
        'tc' => $row['tc']
    ];
}

echo json_encode(['results' => $data]);
exit;
