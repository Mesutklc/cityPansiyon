<?php
require 'config/db.php';
include 'header.php';

// Ürün ve kasa listesi
$products = $pdo->query("SELECT * FROM kiosk_products ORDER BY name ASC")->fetchAll();
$accounts = $pdo->query("SELECT * FROM cash_accounts ORDER BY name ASC")->fetchAll();
?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Büfe Satışı</h2>
    <a href="manage_kiosk_products.php" class="btn btn-sm btn-outline-primary">+ Yeni Ürün Ekle</a>
  </div>

  <form action="save_kiosk_sale.php" method="POST">
    <div class="mb-3">
      <label>Ürün</label>
      <select name="product_id" id="productSelect" class="form-select" required>
        <option value="">Seçiniz</option>
        <?php foreach ($products as $p): ?>
          <option value="<?= $p['id'] ?>" data-price="<?= $p['unit_price'] ?>">
            <?= htmlspecialchars($p['name']) ?> (₺<?= number_format($p['unit_price'], 2) ?>)
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label>Adet</label>
      <input type="number" name="quantity" id="quantityInput" class="form-control" value="1" min="1" required>
    </div>

    <div class="mb-3">
      <label>Toplam Tutar</label>
      <input type="text" name="total_price" id="totalPrice" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Ödeme Yöntemi (Kasa)</label>
      <select name="cash_account_id" class="form-select" required>
        <option value="">Seçiniz</option>
        <?php foreach ($accounts as $acc): ?>
          <option value="<?= $acc['id'] ?>"><?= htmlspecialchars($acc['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <button type="submit" class="btn btn-success">Satışı Kaydet</button>
  </form>
</div>

<script>
function calculateTotal() {
  const unitPrice = parseFloat($('#productSelect option:selected').data('price') || 0);
  const quantity = parseInt($('#quantityInput').val() || 0);
  const total = (unitPrice * quantity).toFixed(2);
  $('#totalPrice').val(total);
}

$('#productSelect, #quantityInput').on('change keyup', calculateTotal);
$(document).ready(calculateTotal);
</script>
