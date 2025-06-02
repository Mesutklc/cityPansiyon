<?php
require 'config/db.php';

$fromId = $_POST['from_account'];
$toId = $_POST['to_account'];
$amount = floatval($_POST['amount']);
$description = trim($_POST['description'] ?? '');

if ($fromId == $toId) {
    header("Location: transfer.php?danger=1");
    die();
}

$pdo->beginTransaction();
try {
    // Kaynak kasadan düş
    $stmt = $pdo->prepare("UPDATE cash_accounts SET balance = balance - ? WHERE id = ?");
    $stmt->execute([$amount, $fromId]);

    // Hedef kasaya ekle
    $stmt = $pdo->prepare("UPDATE cash_accounts SET balance = balance + ? WHERE id = ?");
    $stmt->execute([$amount, $toId]);

    // Kasa hareketi kaydı (çıkış)
    $stmt = $pdo->prepare("INSERT INTO cash_transactions (cash_account_id, hareket_tipi, tutar, aciklama, islem_tarihi) VALUES (?, 'gider', ?, ?, NOW())");
    $stmt->execute([$fromId, $amount, "Transfer: " . $description]);

    // Kasa hareketi kaydı (giriş)
    $stmt = $pdo->prepare("INSERT INTO cash_transactions (cash_account_id, hareket_tipi, tutar, aciklama, islem_tarihi) VALUES (?, 'gelir', ?, ?, NOW())");
    $stmt->execute([$toId, $amount, "Transfer: " . $description]);

    $pdo->commit();
    header("Location: transfer.php?success=1");
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Transfer sırasında hata oluştu: " . $e->getMessage();
}
