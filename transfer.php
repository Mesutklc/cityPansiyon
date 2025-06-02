<?php
require 'config/db.php';
include 'header.php';

// Kasa listesini al
$accounts = $pdo->query("SELECT * FROM cash_accounts ORDER BY name ASC")->fetchAll();



$accounts = $pdo->query("SELECT * FROM cash_accounts ORDER BY name ASC")->fetchAll();
$success = isset($_GET['success']) && $_GET['success'] == 1;
$danger = isset($_GET['danger']) && $_GET['danger'] == 1;
?>

<div class="container mt-5">
    <h2>Kasalar Arası Transfer</h2>

    <?php if ($success): ?>
        <div class="alert alert-success">✅ Transfer başarıyla gerçekleştirildi.</div>
    <?php endif; ?>
    <?php if ($danger): ?>
        <div class="alert alert-danger">✅ Aynı kasaya işlem yapılamaz!</div>
    <?php endif; ?>

    <form action="save_transfer.php" method="POST">
        <!-- (form alanları aynı şekilde devam eder) -->


    <form action="save_transfer.php" method="POST">
        <div class="mb-3">
            <label for="from_account" class="form-label">Kaynak Kasa</label>
            <select name="from_account" id="from_account" class="form-select" required>
                <option value="">Seçiniz</option>
                <?php foreach ($accounts as $acc): ?>
                    <option value="<?= $acc['id'] ?>"><?= htmlspecialchars($acc['name']) ?> (<?= number_format($acc['balance'], 2) ?> ₺)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="to_account" class="form-label">Hedef Kasa</label>
            <select name="to_account" id="to_account" class="form-select" required>
                <option value="">Seçiniz</option>
                <?php foreach ($accounts as $acc): ?>
                    <option value="<?= $acc['id'] ?>"><?= htmlspecialchars($acc['name']) ?> (<?= number_format($acc['balance'], 2) ?> ₺)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Tutar (₺)</label>
            <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Açıklama</label>
            <input type="text" name="description" id="description" class="form-control" placeholder="İsteğe bağlı">
        </div>

        <button type="submit" class="btn btn-primary">Transfer Yap</button>
    </form>
</div>
<?php include 'footer.php'; ?>
