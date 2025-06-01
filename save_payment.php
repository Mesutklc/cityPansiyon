<?php
require 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservation_id = $_POST['reservation_id'] ?? null;
    $customer_id = $_POST['customer_id'] ?? null;
    $amount = $_POST['amount'] ?? 0;
    $cash_account_id = $_POST['cash_account_id'] ?? null;

    if (!$reservation_id || !$customer_id || !$amount || !$cash_account_id) {
        die("Eksik bilgi gönderildi.");
    }

    // Kasa kontrol
    $stmt = $pdo->prepare("SELECT name FROM cash_accounts WHERE id = ?");
    $stmt->execute([$cash_account_id]);
    $account = $stmt->fetch();
    if (!$account) {
        die("Kasa tipi bulunamadı.");
    }

    // Rezervasyon + müşteri + oda bilgisi
    $stmt = $pdo->prepare("
        SELECT r.start_date, r.room_id, rooms.room_number, c.full_name 
        FROM reservations r 
        JOIN rooms ON r.room_id = rooms.id
        JOIN customers c ON r.customer_id = c.id
        WHERE r.id = ?
    ");
    $stmt->execute([$reservation_id]);
    $rez = $stmt->fetch();
    if (!$rez) {
        die("Rezervasyon bilgisi alınamadı.");
    }

    $aciklama = "{$rez['full_name']} - Oda {$rez['room_number']} - {$rez['start_date']} rezervasyonu için ödeme";

    // 1. Ödeme kaydı
    $stmt = $pdo->prepare("
        INSERT INTO cash_transactions 
        (cash_account_id, hareket_tipi, tutar, aciklama, customer_id) 
        VALUES (?, 'gelir', ?, ?, ?)
    ");
    $stmt->execute([$cash_account_id, $amount, $aciklama, $customer_id]);

    // 2. Kasa güncelle
    $stmt = $pdo->prepare("UPDATE cash_accounts SET balance = balance + ? WHERE id = ?");
    $stmt->execute([$amount, $cash_account_id]);

    // 3. Müşteri bakiyesi güncelle
    $stmt = $pdo->prepare("UPDATE customers SET balance = balance - ? WHERE id = ?");
    $stmt->execute([$amount, $customer_id]);

    // 4. Rezervasyon ödeme bilgisi güncelle
    $stmt = $pdo->prepare("UPDATE reservations SET payment_amount = payment_amount + ? WHERE id = ?");
    $stmt->execute([$amount, $reservation_id]);

    // Başarı mesajı ve yönlendirme
    echo "<div style='padding:20px; font-family:sans-serif; text-align:center;'>
            <h3 style='color:green;'>💸 Ödeme başarıyla alındı!</h3>
            <p>Dashboard sayfasına yönlendiriliyorsunuz...</p>
          </div>
          <script>
            setTimeout(function() {
              window.location.href = 'dashboard.php';
            }, 2000);
          </script>";
    exit;
}
?>
