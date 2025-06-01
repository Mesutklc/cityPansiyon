<?php
require "config/db.php";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=gider_raporu.csv');
$output = fopen('php://output', 'w');

// Başlıklar
fputcsv($output, ['Tarih', 'Kasa', 'Gider Türü', 'Tutar', 'Açıklama'], ';');

// Sorgu ve filtreler
$sql = "SELECT e.*, et.name AS type_name, ca.name AS account_name 
        FROM expenses e
        JOIN expense_types et ON e.expense_type_id = et.id
        JOIN cash_accounts ca ON e.cash_account_id = ca.id
        WHERE 1";
$params = [];

if (!empty($_GET['start_date'])) {
    $sql .= " AND DATE(e.created_at) >= ?";
    $params[] = $_GET['start_date'];
}
if (!empty($_GET['end_date'])) {
    $sql .= " AND DATE(e.created_at) <= ?";
    $params[] = $_GET['end_date'];
}
if (!empty($_GET['type_id'])) {
    $sql .= " AND e.expense_type_id = ?";
    $params[] = $_GET['type_id'];
}
if (!empty($_GET['account_id'])) {
    $sql .= " AND e.cash_account_id = ?";
    $params[] = $_GET['account_id'];
}
if (!empty($_GET['keyword'])) {
    $sql .= " AND e.description LIKE ?";
    $params[] = '%' . $_GET['keyword'] . '%';
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {
    fputcsv($output, [
        date("Y-m-d H:i", strtotime($row['created_at'])),
        $row['account_name'],
        $row['type_name'],
        number_format($row['amount'], 2, ',', '.') . ' ₺',
        $row['description']
    ], ';');
}

fclose($output);
exit;
