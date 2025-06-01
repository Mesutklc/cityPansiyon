<?php
require 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'] ?? null;
    $quantity = $_POST['quantity'] ?? 1;
    $total_price = $_POST['total_price'] ?? 0;
    $cash_account_id = $_POST['cash_account_id'] ?? null;

    if (!$product_id || !$quantity || !$total_price || !$cash_account_id) {
        die("Eksik bilgi gönderildi.");
    }

    // 1. Satışı kaydet
    $stmt = $pdo->prepare("INSERT INTO kiosk_sales (product_id, quantity, total_price, payment_method, cash_account_id) VALUES (?, ?, ?, 'Büfe', ?)");
    $stmt->execute([$product_id, $quantity, $total_price, $cash_account_id]);

    // 2. Kasa işlemi (gelir)
    $stmt = $pdo->prepare("SELECT name FROM kiosk_products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    $aciklama = "Büfe satışı: {$product['name']} x{$quantity}";

    $stmt = $pdo->prepare("INSERT INTO cash_transactions (cash_account_id, hareket_tipi, tutar, aciklama) VALUES (?, 'gelir', ?, ?)");
    $stmt->execute([$cash_account_id, $total_price, $aciklama]);

    // 3. Kasa bakiyesini artır
    $stmt = $pdo->prepare("UPDATE cash_accounts SET balance = balance + ? WHERE id = ?");
    $stmt->execute([$total_price, $cash_account_id]);

    // 4. Mesaj + yönlendirme
    echo "<div style='padding:20px; text-align:center; font-family:sans-serif;'>
            <h3 style='color:green;'>🎉 Satış başarıyla kaydedildi!</h3>
            <p>Yönlendiriliyorsunuz...</p>
          </div>
          <script>
            setTimeout(function() {
              window.location.href = 'kiosk_sale.php';
            }, 2000);
          </script>";
}
