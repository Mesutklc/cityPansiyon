<?php
require 'config/db.php';
$name = trim($_POST['name'] ?? '');
if ($name) {
  $stmt = $pdo->prepare("INSERT INTO expense_types (name) VALUES (?)");
  $stmt->execute([$name]);
}
