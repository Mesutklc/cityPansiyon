<?php
require 'config/db.php';
include 'header.php';

$accounts = $pdo->query("SELECT * FROM cash_accounts ORDER BY name ASC")->fetchAll();
$types = $pdo->query("SELECT * FROM expense_types ORDER BY name ASC")->fetchAll();
?>
<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Gider Ekle</h2>
    <div>
      <a href="expense_types.php" class="btn btn-outline-primary me-2">➕ Yeni Gider Türü</a>
      <a href="expense_report.php" class="btn btn-outline-success">📊 Gider Raporu</a>
    </div>
  </div>

<div class="container mt-4">


  <form action="save_expense.php" method="POST">
    <div class="mb-3">
      <label for="expense_type" class="form-label">Gider Türü</label>
      <select name="expense_type_id" id="expense_type" class="form-select" required>
        <option value="">Seçiniz</option>
        <?php foreach ($types as $type): ?>
          <option value="<?= $type['id'] ?>"><?= htmlspecialchars($type['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="cash_account_id" class="form-label">Kasa</label>
      <select name="cash_account_id" id="cash_account_id" class="form-select" required>
        <option value="">Seçiniz</option>
        <?php foreach ($accounts as $acc): ?>
          <option value="<?= $acc['id'] ?>"><?= htmlspecialchars($acc['name']) ?> (<?= number_format($acc['balance'], 2) ?> ₺)</option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="description" class="form-label">Açıklama</label>
      <input type="text" name="description" id="description" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="amount" class="form-label">Tutar (₺)</label>
      <input type="number" name="amount" id="amount" step="0.01" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-danger">Gideri Kaydet</button>
  </form>
</div>
