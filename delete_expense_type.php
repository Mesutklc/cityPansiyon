<?php
require 'config/db.php';
$id = intval($_POST['id'] ?? 0);
if ($id) {
  $pdo->prepare("DELETE FROM expense_types WHERE id = ?")->execute([$id]);
}
