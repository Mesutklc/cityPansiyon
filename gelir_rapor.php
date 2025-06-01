<?php
require 'config/db.php';
include 'header.php';

$startDate = $_GET['start_date'] ?? date('Y-m-01');
$endDate = $_GET['end_date'] ?? date('Y-m-d');

// Gelir ve giderleri gün bazında al
$stmt = $pdo->prepare("
    SELECT DATE(islem_tarihi) as tarih,
        SUM(CASE WHEN hareket_tipi = 'gelir' THEN tutar ELSE 0 END) as gelir,
        SUM(CASE WHEN hareket_tipi = 'gider' THEN tutar ELSE 0 END) as gider
    FROM cash_transactions
    WHERE DATE(islem_tarihi) BETWEEN :start AND :end
    GROUP BY DATE(islem_tarihi)
    ORDER BY tarih
");
$stmt->execute(['start' => $startDate, 'end' => $endDate]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$labels = [];
$gelir = [];
$gider = [];
$kar = [];

foreach ($data as $row) {
    $labels[] = $row['tarih'];
    $gelir[] = (float)$row['gelir'];
    $gider[] = (float)$row['gider'];
    $kar[] = (float)$row['gelir'] - (float)$row['gider'];
}

// Toplamlar
$totalGelir = array_sum($gelir);
$totalGider = array_sum($gider);
$totalKar = $totalGelir - $totalGider;
?>

<div class="container">
    <h2 class="mb-4">Gelir - Gider - Kar Raporu</h2>

    <form method="get" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Başlangıç Tarihi</label>
            <input type="date" name="start_date" value="<?= $startDate ?>" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">Bitiş Tarihi</label>
            <input type="date" name="end_date" value="<?= $endDate ?>" class="form-control">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary" type="submit">Filtrele</button>
        </div>
    </form>

    <canvas id="karChart" height="100" class="mt-5"></canvas>

    <table class="table table-bordered mt-5">
        <thead>
            <tr>
                <th>Tarih</th>
                <th>Gelir</th>
                <th>Gider</th>
                <th>Kar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row): ?>
            <tr>
                <td><?= $row['tarih'] ?></td>
                <td><?= number_format($row['gelir'], 2) ?> ₺</td>
                <td><?= number_format($row['gider'], 2) ?> ₺</td>
                <td><?= number_format($row['gelir'] - $row['gider'], 2) ?> ₺</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th>TOPLAM</th>
                <th><?= number_format($totalGelir, 2) ?> ₺</th>
                <th><?= number_format($totalGider, 2) ?> ₺</th>
                <th><?= number_format($totalKar, 2) ?> ₺</th>
            </tr>
        </tfoot>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('karChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [
                {
                    label: 'Gelir',
                    data: <?= json_encode($gelir) ?>,
                    borderColor: 'blue',
                    backgroundColor: 'rgba(0,0,255,0.1)',
                    tension: 0.3
                },
                {
                    label: 'Gider',
                    data: <?= json_encode($gider) ?>,
                    borderColor: 'orange',
                    backgroundColor: 'rgba(255,165,0,0.1)',
                    tension: 0.3
                },
                {
                    label: 'Kar',
                    data: <?= json_encode($kar) ?>,
                    borderColor: 'green',
                    backgroundColor: 'rgba(0,255,0,0.1)',
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Tarihe Göre Gelir - Gider - Kar Grafiği'
                }
            }
        }
    });
</script>
