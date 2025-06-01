<?php
require 'config/db.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$start = date('Y-m-d', strtotime('-7 days'));
$end = date('Y-m-d', strtotime('+14 days'));

$rooms = $pdo->query("SELECT id, room_number FROM rooms ORDER BY room_number ASC")->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
  SELECT r.*, c.full_name, c.balance, rm.room_number
  FROM reservations r
  LEFT JOIN customers c ON r.customer_id = c.id
  LEFT JOIN rooms rm ON r.room_id = rm.id
  WHERE r.start_date <= :end AND r.end_date > :start
");
$stmt->execute(['end' => $end, 'start' => $start]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// her gün + her oda = tek event
$events = [];

foreach ($rooms as $room) {
    $roomId = $room['id'];
    $roomNumber = $room['room_number'];
    $current = new DateTime($start);
    $endDate = new DateTime($end);

    while ($current < $endDate) {
        $dateStr = $current->format('Y-m-d');
        $nextDate = (clone $current)->modify('+1 day')->format('Y-m-d');

        $eventAdded = false;

        foreach ($reservations as $res) {
            if (
                $res['room_id'] == $roomId &&
                $dateStr >= $res['start_date'] &&
                $dateStr < $res['end_date']
            ) {
                // DOLU
                $events[] = [
    'id' => 'res_' . $res['id'] . '_' . $dateStr,
    'title' => 'Oda ' . $roomNumber . ' - ' . $res['full_name'] . ' (₺' . number_format($res['balance'], 2) . ')',
    'start' => $dateStr,
    'end' => $nextDate,
    'color' => '#dc3545',
    'roomId' => $roomId,
    'room_number' => $roomNumber,
    'status' => 'dolu',
    'reservationId' => $res['id'] // 🔥 bu ekleniyor!
];
                $eventAdded = true;
                break;
            }
        }

        if (!$eventAdded) {
            // BOŞ
            $events[] = [
                'id' => 'empty_' . $roomId . '_' . $dateStr,
                'title' => 'Oda ' . $roomNumber . ' - Boş',
                'start' => $dateStr,
                'end' => $nextDate,
                'color' => '#28a745',
                'roomId' => $roomId,
                'room_number' => $roomNumber,
                'status' => 'boş'
            ];
        }

        $current->modify('+1 day');
    }
}

header('Content-Type: application/json');
echo json_encode($events);
