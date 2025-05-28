<?php
require 'config/db.php';
include "header.php";
$customers = $pdo->query("SELECT * FROM customers")->fetchAll();
?>
<h2>Cari Hesaplar</h2>
<table class="table">
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
                <td><?= htmlspecialchars($c['balance']) ?></td>
                <td><a href="customer_detail.php?id=<?= $c['id'] ?>">Detay</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
