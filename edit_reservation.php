<?php
require 'config/db.php';
session_start();

$reservationId = $_GET['reservation_id'] ?? null;
if (!$reservationId) die("Rezervasyon ID'si belirtilmedi.");

// Rezervasyon + müşteri bilgisi
$stmt = $pdo->prepare("
  SELECT r.*, c.full_name, c.phone, c.email, c.tc 
  FROM reservations r 
  JOIN customers c ON r.customer_id = c.id 
  WHERE r.id = ?
");
$stmt->execute([$reservationId]);
$res = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$res) die("Rezervasyon bulunamadı.");

// Odalar
$rooms = $pdo->query("SELECT id, room_number, price FROM rooms ORDER BY room_number")->fetchAll(PDO::FETCH_ASSOC);

// Gün sayısı
$days = (strtotime($res['end_date']) - strtotime($res['start_date'])) / (60 * 60 * 24);

// JavaScript için fiyatlar
$roomMap = [];
foreach ($rooms as $room) {
    $roomMap[$room['id']] = $room['price'];
}
?>
<?php include 'header.php'; ?>

<div class="container">
  <h3 class="mb-4">Rezervasyon Düzenle</h3>

  <form action="update_reservation.php" method="POST">
    <input type="hidden" name="id" value="<?= $res['id'] ?>">
    <input type="hidden" name="customer_id" value="<?= $res['customer_id'] ?>">

    <!-- Müşteri Bilgileri (readonly) -->
    <div class="mb-3"><label>Müşteri Adı</label>
      <input type="text" class="form-control" value="<?= htmlspecialchars($res['full_name']) ?>" disabled>
    </div>
    <div class="mb-3"><label>Telefon</label>
      <input type="text" class="form-control" value="<?= htmlspecialchars($res['phone']) ?>" disabled>
    </div>
    <div class="mb-3"><label>E-posta</label>
      <input type="text" class="form-control" value="<?= htmlspecialchars($res['email']) ?>" disabled>
    </div>
    <div class="mb-3"><label>TC</label>
      <input type="text" class="form-control" value="<?= htmlspecialchars($res['tc']) ?>" disabled>
    </div>

    <!-- Tarihler -->
    <div class="mb-3"><label>Başlangıç Tarihi</label>
      <input type="date" name="start_date" class="form-control" value="<?= $res['start_date'] ?>" required>
    </div>
    <div class="mb-3"><label>Bitiş Tarihi</label>
      <input type="date" name="end_date" class="form-control" value="<?= $res['end_date'] ?>" required>
    </div>

    <!-- Oda -->
    <div class="mb-3"><label>Oda Numarası</label>
      <select class="form-control" name="room_id" required>
        <?php foreach ($rooms as $room): ?>
          <option value="<?= $room['id'] ?>" <?= $room['id'] == $res['room_id'] ? 'selected' : '' ?>>
            <?= $room['room_number'] ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- Günlük Ücret -->
    <div class="mb-3"><label>Günlük Ücret (₺)</label>
      <input type="number" step="0.01" name="daily_price" class="form-control" value="<?= round($res['price'] / max(1, $days), 2) ?>" required>
    </div>

    <!-- Toplam Tutar -->
    <div class="mb-3"><label>Toplam Tutar (₺)</label>
      <input type="number" step="0.01" name="price" class="form-control" value="<?= $res['price'] ?>" required>
    </div>

    <button type="submit" class="btn btn-primary">Güncelle</button>
    <a href="delete_reservation.php?id=<?= $res['id'] ?>" class="btn btn-danger" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</a>
    <a href="payment.php?reservation_id=<?= $res['id'] ?>" class="btn btn-success">
  Ödeme Al
</a>

  </form>
</div>

<!-- JS -->
<script>
  const roomPrices = <?= json_encode($roomMap) ?>;

  $(document).ready(function () {
    // Oda değişince günlük fiyatı güncelle
    $('select[name="room_id"]').on('change', function () {
      const roomId = $(this).val();
      const price = roomPrices[roomId] ?? 0;
      $('input[name="daily_price"]').val(price);
      updateTotal();
    });

    // Tarih veya günlük ücret değişince toplam tutarı güncelle
    $('input[name="start_date"], input[name="end_date"], input[name="daily_price"]').on('change', function () {
      updateTotal();
    });

    // 🔧 Sayfa ilk açıldığında oda seçimini tetikle
    $('select[name="room_id"]').trigger('change');

    function updateTotal() {
      const start = new Date($('input[name="start_date"]').val());
      const end = new Date($('input[name="end_date"]').val());
      const daily = parseFloat($('input[name="daily_price"]').val()) || 0;
      const days = (end - start) / (1000 * 60 * 60 * 24);
      if (days > 0) {
        $('input[name="price"]').val((days * daily).toFixed(2));
      }
    }
  });
</script>
<?php include 'footer.php'; ?>
