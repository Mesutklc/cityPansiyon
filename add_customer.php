<?php
require 'config/db.php';
include 'header.php';

$success = isset($_GET['success']);
?>

<div class="container mt-5">
  <h2>Yeni Cari (Müşteri) Oluştur</h2>

  <?php if ($success): ?>
    <div class="alert alert-success">✅ Müşteri başarıyla eklendi.</div>
  <?php endif; ?>

  <form action="save_customer.php" method="POST">
    <div class="mb-3">
      <label for="full_name" class="form-label">Ad Soyad</label>
      <input type="text" class="form-control" id="full_name" name="full_name" required>
    </div>

    <div class="mb-3">
      <label for="phone" class="form-label">Telefon</label>
      <input type="text" class="form-control" id="phone" name="phone">
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">E-posta</label>
      <input type="email" class="form-control" id="email" name="email">
    </div>

    <div class="mb-3">
      <label for="tc" class="form-label">T.C. Kimlik No</label>
      <input type="text" class="form-control" id="tc" name="tc">
    </div>

    <div class="mb-3">
      <label for="balance" class="form-label">Başlangıç Bakiyesi (₺)</label>
      <input type="number" step="0.01" class="form-control" id="balance" name="balance" value="0.00">
    </div>

    <button type="submit" class="btn btn-primary">Kaydet</button>
  </form>
</div>
<?php include 'footer.php'; ?>
