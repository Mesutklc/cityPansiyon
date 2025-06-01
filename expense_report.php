<?php
include "header.php";
require "config/db.php";

// Gider türleri ve kasaları al
$types = $pdo->query("SELECT * FROM expense_types")->fetchAll(PDO::FETCH_ASSOC);
$accounts = $pdo->query("SELECT * FROM cash_accounts")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h2 class="mb-4">Gider Raporlama</h2>

    <form class="row g-3" method="GET">
        <div class="col-md-3">
            <label for="start_date" class="form-label">Başlangıç Tarihi</label>
            <input type="date" id="start_date" name="start_date" class="form-control" value="<?= $_GET['start_date'] ?? '' ?>">
        </div>
        <div class="col-md-3">
            <label for="end_date" class="form-label">Bitiş Tarihi</label>
            <input type="date" id="end_date" name="end_date" class="form-control" value="<?= $_GET['end_date'] ?? '' ?>">
        </div>
        <div class="col-md-3">
            <label for="type_id" class="form-label">Gider Türü</label>
            <select name="type_id" class="form-select">
                <option value="">Tümü</option>
                <?php foreach($types as $type): ?>
                    <option value="<?= $type['id'] ?>" <?= ($_GET['type_id'] ?? '') == $type['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($type['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label for="account_id" class="form-label">Kasa</label>
            <select name="account_id" class="form-select">
                <option value="">Tümü</option>
                <?php foreach($accounts as $acc): ?>
                    <option value="<?= $acc['id'] ?>" <?= ($_GET['account_id'] ?? '') == $acc['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($acc['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Açıklama İçeriği</label>
            <input type="text" name="keyword" class="form-control" value="<?= $_GET['keyword'] ?? '' ?>" placeholder="Anahtar kelime ile ara">
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <button class="btn btn-primary w-100">Filtrele</button>
        </div>
    </form>

    <div class="mt-4">
        <a href="export_expenses.php?<?= http_build_query($_GET) ?>" class="btn btn-success">💾 Excel (CSV) İndir</a>
    </div>

    <table class="table table-bordered mt-3">
        <thead class="table-light">
            <tr>
                <th>Tarih</th>
                <th>Kasa</th>
                <th>Gider Türü</th>
                <th>Tutar</th>
                <th>Açıklama</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT e.*, et.name AS type_name, ca.name AS account_name 
                    FROM expenses e
                    JOIN expense_types et ON e.expense_type_id = et.id
                    JOIN cash_accounts ca ON e.cash_account_id = ca.id
                    WHERE 1";
            $params = [];

            if (!empty($_GET['start_date'])) {
                $sql .= " AND DATE(e.created_at) >= ?";
                $params[] = $_GET['start_date'];
            }
            if (!empty($_GET['end_date'])) {
                $sql .= " AND DATE(e.created_at) <= ?";
                $params[] = $_GET['end_date'];
            }
            if (!empty($_GET['type_id'])) {
                $sql .= " AND e.expense_type_id = ?";
                $params[] = $_GET['type_id'];
            }
            if (!empty($_GET['account_id'])) {
                $sql .= " AND e.cash_account_id = ?";
                $params[] = $_GET['account_id'];
            }
            if (!empty($_GET['keyword'])) {
                $sql .= " AND e.description LIKE ?";
                $params[] = '%' . $_GET['keyword'] . '%';
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row):
            ?>
            <tr>
                <td><?= date("Y-m-d H:i", strtotime($row['created_at'])) ?></td>
                <td><?= htmlspecialchars($row['account_name']) ?></td>
                <td><?= htmlspecialchars($row['type_name']) ?></td>
                <td><?= number_format($row['amount'], 2) ?> ₺</td>
                <td><?= htmlspecialchars($row['description']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include "footer.php"; ?>
