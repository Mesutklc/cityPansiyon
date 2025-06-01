<?php
require 'config/db.php';
include "header.php";

// Ürün silme işlemi
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $pdo->prepare("DELETE FROM kiosk_products WHERE id = ?")->execute([$delete_id]);
    header("Location: manage_kiosk_products.php");
    exit;
}

// AJAX ile güncelleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['field'])) {
    $field = $_POST['field'];
    $value = $_POST['value'];
    $id = $_POST['id'];

    if (in_array($field, ['name', 'unit_price'])) {
        $stmt = $pdo->prepare("UPDATE kiosk_products SET $field = ? WHERE id = ?");
        $stmt->execute([$value, $id]);
    }
    exit;
}

// Ürün ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = trim($_POST['name'] ?? '');
    $unit_price = floatval($_POST['unit_price'] ?? 0);

    if ($name && $unit_price > 0) {
        $stmt = $pdo->prepare("INSERT INTO kiosk_products (name, unit_price) VALUES (?, ?)");
        $stmt->execute([$name, $unit_price]);
        header("Location: manage_kiosk_products.php");
        exit;
    }
}

// Tüm ürünleri al
$products = $pdo->query("SELECT * FROM kiosk_products ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Büfe Ürün Yönetimi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .editable:hover {
      background-color: #f0f0f0;
      cursor: pointer;
    }
    input.inline-edit {
      width: 100%;
      border: none;
      background: #fffbd7;
    }
  </style>
</head>

<div class="container mt-3">
  <h2>Büfe Ürünleri</h2>

  <!-- Yeni ürün ekleme -->
  <form method="POST" class="row g-2 mb-4">
    <input type="hidden" name="add_product" value="1">
    <div class="col-md-4">
      <input type="text" name="name" class="form-control" placeholder="Ürün adı" required>
    </div>
    <div class="col-md-3">
      <input type="number" name="unit_price" step="0.01" class="form-control" placeholder="Birim fiyat (₺)" required>
    </div>
    <div class="col-md-2">
      <button class="btn btn-primary">Ekle</button>
    </div>
  </form>

  <!-- Ürün tablosu -->
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Ürün Adı</th>
        <th>Birim Fiyat (₺)</th>
        <th>Sil</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $p): ?>
        <tr data-id="<?= $p['id'] ?>">
          <td class="editable" data-field="name"><?= htmlspecialchars($p['name']) ?></td>
          <td class="editable" data-field="unit_price"><?= number_format($p['unit_price'], 2) ?></td>
          <td><a href="?delete=<?= $p['id'] ?>" class="btn btn-danger btn-sm">Sil</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
      </div>

<script>
$(document).ready(function(){
  $('.editable').click(function(){
    var td = $(this);
    if (td.find('input').length > 0) return; // Zaten inputsa dur
    var current = td.text().trim();
    var field = td.data('field');
    var row = td.closest('tr');
    var id = row.data('id');

    var input = $('<input type="text" class="inline-edit">').val(current);
    td.html(input);
    input.focus();

    input.blur(function(){
      var newVal = $(this).val().trim();
      td.text(newVal);

      // AJAX güncelle
      $.post('', {
        id: id,
        field: field,
        value: newVal
      });
    });
  });
});
</script>

</body>
</html>
