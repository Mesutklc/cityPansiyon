<?php
require 'config/db.php';
include "header.php";

// Girdi alma
$account_id = $_GET['id'] ?? null;
$start_date = $_GET['start_date'] ?? null;
$end_date = $_GET['end_date'] ?? null;
$customer_id = $_GET['customer_id'] ?? null;

if (!$account_id) {
    die("Kasa ID bulunamadı.");
}

// Kasa bilgisi
$stmt = $pdo->prepare("SELECT name FROM cash_accounts WHERE id = ?");
$stmt->execute([$account_id]);
$account = $stmt->fetch();
if (!$account) {
    die("Kasa bulunamadı.");
}

// Müşterileri çek
$customers = $pdo->query("SELECT id, full_name FROM customers ORDER BY full_name ASC")->fetchAll();

// Sorgu dinamiği
$where = "WHERE cash_account_id = :account_id";
$params = ['account_id' => $account_id];

if ($start_date && $end_date) {
    $where .= " AND DATE(created_at) BETWEEN :start AND :end";
    $params['start'] = $start_date;
    $params['end'] = $end_date;
}

if ($customer_id) {
    $where .= " AND customer_id = :customer_id";
    $params['customer_id'] = $customer_id;
}

// Hareketler
$stmt = $pdo->prepare("SELECT * FROM cash_transactions $where ORDER BY created_at DESC");
$stmt->execute($params);
$transactions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($account['name']) ?> - Hareketler</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-4">
  <h2><?= htmlspecialchars($account['name']) ?> - Hareket Geçmişi</h2>
  <a href="cash_accounts.php" class="btn btn-secondary mb-3">← Geri</a>

  <!-- 🔎 Filtre Formu -->
  <form method="get" class="row g-2 mb-4">
    <input type="hidden" name="id" value="<?= $account_id ?>">
    <div class="col-md-3">
      <label>Başlangıç Tarihi</label>
      <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>" class="form-control">
    </div>
    <div class="col-md-3">
      <label>Bitiş Tarihi</label>
      <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>" class="form-control">
    </div>
    <div class="col-md-4">
      <label>Müşteri</label>
      <select name="customer_id" class="form-select select2">
        <option value="">Tümü</option>
        <?php foreach ($customers as $c): ?>
          <option value="<?= $c['id'] ?>" <?= $customer_id == $c['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['full_name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2 d-flex align-items-end">
      <button type="submit" class="btn btn-primary w-100">Filtrele</button>
    </div>
  </form>

  <!-- 🧾 Hareket Tablosu -->
  <table class="table table-bordered table-sm">
    <thead>
      <tr>
        <th>#</th>
        <th>Tarih & Saat</th>
        <th>Tür</th>
        <th>Tutar (₺)</th>
        <th>Açıklama</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($transactions as $i => $row): ?>
      <tr>
        <td><?= $i + 1 ?></td>
        <td><?= date("d.m.Y H:i", strtotime($row['created_at'])) ?></td>
        <td><?= ucfirst($row['hareket_tipi']) ?></td>
        <td><?= number_format($row['tutar'], 2) ?></td>
        <td><?= htmlspecialchars($row['aciklama']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $('.select2').select2();
</script>
<?php include 'footer.php'; ?>

</body>
</html>
