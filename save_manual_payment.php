<?php
require 'config/db.php';
session_start();

// Gerekli verileri al
$customerId = $_POST['customer_id'] ?? null;
$cashAccountId = $_POST['cash_account_id'] ?? null;
$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : null;
$type = $_POST['type'] ?? null;
$now = date('Y-m-d H:i:s');
$createdBy = $_SESSION['admin_name'] ?? 'Sistem';

// Kontroller
if (!$customerId || !$cashAccountId || !$amount || !$type) {
    die("Eksik bilgi gönderildi.");
}

try {
    $pdo->beginTransaction();

    // Müşteri adı çek
    $stmt = $pdo->prepare("SELECT full_name FROM customers WHERE id = ?");
    $stmt->execute([$customerId]);
    $customer = $stmt->fetch();
    $customerName = $customer ? $customer['full_name'] : 'Bilinmeyen Müşteri';

    // İşlem: ödeme AL => müşteri borcu azalır, kasaya eklenir
    //        ödeme YAP => müşteri borcu artar, kasadan düşülür
    if ($type === 'receive') {
        $pdo->prepare("UPDATE customers SET balance = balance - ? WHERE id = ?")->execute([$amount, $customerId]);
        $pdo->prepare("UPDATE cash_accounts SET balance = balance + ? WHERE id = ?")->execute([$amount, $cashAccountId]);
        $hareketTipi = 'gelir';
        $aciklama = "Cari ödeme alındı: $customerName";
    } elseif ($type === 'send') {
        $pdo->prepare("UPDATE customers SET balance = balance + ? WHERE id = ?")->execute([$amount, $customerId]);
        $pdo->prepare("UPDATE cash_accounts SET balance = balance - ? WHERE id = ?")->execute([$amount, $cashAccountId]);
        $hareketTipi = 'gider';
        $aciklama = "Cari ödeme yapıldı: $customerName";
    } else {
        throw new Exception("Geçersiz işlem türü.");
    }

    // Kasa hareketi tablosuna kaydet
    $stmt = $pdo->prepare("INSERT INTO cash_transactions 
        (hareket_tipi, tutar, aciklama, islem_tarihi, olusturan_kullanici, created_at, cash_account_id, customer_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $hareketTipi,
        $amount,
        $aciklama,
        $now,
        $createdBy,
        $now,
        $cashAccountId,
        $customerId
    ]);

    $pdo->commit();

    // Başarılıysa yönlendir
    header("Location: customers.php?success=1");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    echo "İşlem başarısız: " . $e->getMessage();
}
?>
