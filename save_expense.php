<?php
require 'config/db.php';

$expense_type_id = $_POST['expense_type_id'];
$cash_account_id = $_POST['cash_account_id'];
$description = $_POST['description'];
$amount = $_POST['amount'];
$date = date('Y-m-d H:i:s');

// 1. expenses tablosuna ekle
$stmt = $pdo->prepare("INSERT INTO expenses (expense_type_id, cash_account_id, amount, description , created_at) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$expense_type_id, $cash_account_id, $amount, $description, $date]);

// 2. cash_transactions tablosuna da kayıt (gider hareketi)
$stmt2 = $pdo->prepare("INSERT INTO cash_transactions (cash_account_id, tutar, hareket_tipi, aciklama, islem_tarihi) VALUES (?, ?, 'gider', ?, ?)");
$stmt2->execute([$cash_account_id, $amount, $description, $date]);

// 3. kasadan para düş (balance güncelle)
$pdo->prepare("UPDATE cash_accounts SET balance = balance - ? WHERE id = ?")
    ->execute([$amount, $cash_account_id]);

header("Location: add_expense.php?success=1");
exit;
