<?php
require 'config/db.php';
include 'header.php';

$room_id = $_GET['room_id'] ?? null;
$start_date = $_GET['start_date'] ?? date('Y-m-d');

// Oda bilgilerini al
$room = null;
if ($room_id) {
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
    $stmt->execute([$room_id]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Rezervasyon</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .container { max-width: 600px; margin: 30px auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <h3>Yeni Rezervasyon</h3>
    <form action="save_reservation.php" method="POST" id="reservationForm">
        <input type="hidden" name="room_id" value="<?= $room_id ?>">
        <div class="form-group">
            <label>Oda Numarası</label>
            <input type="text" class="form-control" value="<?= $room['room_number'] ?? '' ?>" readonly>
        </div>
        <div class="form-group">
            <label>Başlangıç Tarihi</label>
            <input type="date" name="start_date" class="form-control" value="<?= $start_date ?>" readonly>
        </div>
        <div class="form-group">
            <label>Bitiş Tarihi</label>
            <input type="date" name="end_date" class="form-control" id="end_date" required>
        </div>
        <div class="form-group">
            <label>Müşteri Seç</label>
            <select id="customerSelect" name="customer_id" class="form-control" style="width:100%"></select>
        </div>
        <div class="form-group">
            <label>Ad Soyad</label>
            <input type="text" id="full_name" name="full_name" class="form-control">
        </div>
        <div class="form-group">
            <label>Telefon</label>
            <input type="text" id="phone" name="phone" class="form-control">
        </div>
        <div class="form-group">
            <label>E-posta</label>
            <input type="email" id="email" name="email" class="form-control">
        </div>
        <div class="form-group">
            <label>TC</label>
            <input type="text" id="tc" name="tc" class="form-control">
        </div>
        <div class="form-group">
            <label>Fiyat (Gecelik)</label>
            <input type="number" id="price" name="price" class="form-control" value="<?= $room['price'] ?? 0 ?>" readonly>
        </div>
        <div class="form-group">
            <label>Toplam Ücret</label>
            <input type="text" id="total_price" name="total_price" class="form-control" readonly>
        </div>
        <button type="submit" class="btn btn-primary">Kaydet</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#customerSelect').select2({
        placeholder: "Müşteri Ara",
        ajax: {
            url: 'get_customers.php',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { term: params.term };
            },
            processResults: function(data) {
                return { results: data.results };
            }
        }
    });

    $('#customerSelect').on('select2:select', function(e) {
        let data = e.params.data;
        $('#full_name').val(data.text);
        $('#phone').val(data.phone);
        $('#email').val(data.email);
        $('#tc').val(data.tc);
    });

    $('#end_date').on('change', function() {
        let start = new Date($('input[name="start_date"]').val());
        let end = new Date($(this).val());
        let price = parseFloat($('#price').val());

        if (start && end && end > start) {
            let days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
            let total = days * price;
            $('#total_price').val(total.toFixed(2));
        } else {
            $('#total_price').val('');
        }
    });
});
</script>

</body>
</html>
