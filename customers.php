<?php
require 'config/db.php';
include "header.php";
$customers = $pdo->query("SELECT * FROM customers")->fetchAll();
$cashAccounts = $pdo->query("SELECT * FROM cash_accounts")->fetchAll();
?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Cari Hesaplar</h2>
    <a href="add_customer.php" class="btn btn-outline-primary">➕ Cari Hesap Ekle</a>
  </div>

  <input type="text" id="customerSearch" class="form-control mb-3" placeholder="🔍 İsim, telefon, e-posta ara...">

  <table class="table table-bordered table-striped" id="customerTable">
    <thead>
      <tr>
        <th>Ad Soyad</th>
        <th>Telefon</th>
        <th>E-posta</th>
        <th>TC</th>
        <th>Bakiye</th>
        <th>İşlem</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($customers as $c): ?>
        <tr>
          <td><?= htmlspecialchars($c['full_name']) ?></td>
          <td><?= htmlspecialchars($c['phone']) ?></td>
          <td><?= htmlspecialchars($c['email']) ?></td>
          <td><?= htmlspecialchars($c['tc']) ?></td>
          <td><?= number_format($c['balance'], 2) ?> ₺</td>
          <td>
            <button class="btn btn-success btn-sm payment-receive" data-id="<?= $c['id'] ?>">💰 Ödeme Al</button>
            <button class="btn btn-warning btn-sm payment-send" data-id="<?= $c['id'] ?>">💸 Ödeme Yap</button>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="paymentForm" method="POST" action="save_manual_payment.php">
        <div class="modal-header">
          <h5 class="modal-title" id="paymentModalLabel">Ödeme İşlemi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="customer_id" id="modal_customer_id">
          <input type="hidden" name="type" id="modal_type">

          <div class="mb-3">
            <label class="form-label">Müşteri</label>
            <input type="text" class="form-control" id="modal_customer_name_display" readonly>
          </div>

          <div class="mb-3">
            <label for="amount" class="form-label">Tutar (₺)</label>
            <input type="number" name="amount" class="form-control" step="0.01" required>
          </div>

          <div class="mb-3">
            <label for="cash_account" class="form-label">Kasa Seç</label>
            <select name="cash_account_id" class="form-select" required>
              <?php foreach ($cashAccounts as $acc): ?>
                <option value="<?= $acc['id'] ?>"><?= htmlspecialchars($acc['name']) ?> (<?= number_format($acc['balance'], 2) ?> ₺)</option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Kaydet</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Arama ve modal script -->
<script>
$(document).ready(function () {
  // Arama
  $("#customerSearch").on("keyup", function () {
    const value = $(this).val().toLowerCase();
    $("#customerTable tbody tr").filter(function () {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
  });

  // Modal tetikleme
  $(".payment-receive, .payment-send").on("click", function () {
    const customerId = $(this).data("id");
    const customerName = $(this).closest("tr").find("td:first").text();
    const isReceive = $(this).hasClass("payment-receive");

    $("#modal_customer_id").val(customerId);
    $("#modal_type").val(isReceive ? "receive" : "send");
    $("#modal_customer_name_display").val(customerName);
    $("#paymentModalLabel").text(isReceive ? "💰 Ödeme Al" : "💸 Ödeme Yap");

    const modal = new bootstrap.Modal(document.getElementById("paymentModal"));
    modal.show();
  });
});
</script>
<?php include 'footer.php'; ?>
