<?php
require 'config/db.php';

$reservations = $pdo->query("SELECT r.id, r.room_id, r.start_date, r.end_date, r.status, c.full_name as customer_name FROM reservations r LEFT JOIN customers c ON r.customer_id = c.id")->fetchAll();

$events = [];

foreach ($reservations as $res) {
    $color = 'gray';
    if ($res['status'] === 'boş') $color = 'green';
    elseif ($res['status'] === 'dolu') $color = 'red';
    elseif ($res['status'] === 'rezerve') $color = 'orange';

    $events[] = [
        'id' => $res['id'],
        'title' => $res['customer_name'] . ' (' . $res['room_id'] . ')',
        'start' => $res['start_date'],
        'end' => (new DateTime($res['end_date']))->modify('+1 day')->format('Y-m-d'), // FullCalendar'da end tarihi son günün ertesi olarak verilir
        'color' => $color,
    ];
}

header('Content-Type: application/json');
echo json_encode($events);
