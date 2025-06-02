<?php
require 'config/db.php';

$full_name = $_POST['full_name'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$tc = $_POST['tc'] ?? '';
$balance = floatval($_POST['balance'] ?? 0);

$stmt = $pdo->prepare("INSERT INTO customers (full_name, phone, email, tc, balance) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$full_name, $phone, $email, $tc, $balance]);

header("Location: add_customer.php?success=1");
exit;
