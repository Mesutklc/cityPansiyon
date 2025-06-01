<?php
require 'config/db.php';
include "header.php";

$accounts = $pdo->query("SELECT id, name, balance FROM cash_accounts")->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Kasa Listesi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h2>Kasa Listesi</h2>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Kasa Adı</th>
        <th>Bakiye (₺)</th>
        <th>İşlemler</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($accounts as $account): ?>
      <tr>
        <td><?= htmlspecialchars($account['name']) ?></td>
        <td><?= number_format($account['balance'], 2) ?></td>
        <td>
          <a href="cash_account_history.php?id=<?= $account['id'] ?>" class="btn btn-sm btn-info">Hareketler</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
