<?php
require 'config/db.php';

$start = $_GET['start'] ?? date('Y-m-d');
$end = $_GET['end'] ?? date('Y-m-d', strtotime('+7 days'));

$rooms = $pdo->query("SELECT id, room_number FROM rooms")->fetchAll(PDO::FETCH_ASSOC);

// Tüm rezervasyonları al
$stmt = $pdo->prepare("
  SELECT * FROM reservations
  WHERE start_date <= :end AND end_date >= :start
");
$stmt->execute(['start' => $start, 'end' => $end]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$events = [];

foreach ($rooms as $room) {
    $roomId = $room['id'];
    $roomNumber = $room['room_number'];

    // Takvim aralığında gün gün ilerle
    $periodStart = new DateTime($start);
    $periodEnd = new DateTime($end);
    $periodEnd->modify('+1 day'); // dahil olması için

    // Doluluk günlerini diziye aktar
    $occupiedDates = [];

    foreach ($reservations as $res) {
        if ($res['room_id'] != $roomId) continue;

        $resStart = new DateTime($res['start_date']);
        $resEnd = new DateTime($res['end_date']);
        $resEnd->modify('+1 day');

        while ($resStart < $resEnd) {
            $occupiedDates[$resStart->format('Y-m-d')] = $res;
            $resStart->modify('+1 day');
        }
    }

    // Boş gün bloklarını bul
    $blockStart = null;
    $current = clone $periodStart;

    while ($current < $periodEnd) {
        $dateStr = $current->format('Y-m-d');

        if (!isset($occupiedDates[$dateStr])) {
            if (!$blockStart) {
                $blockStart = clone $current;
            }
        } else {
            // O gün doluysa, varsa önceki boş bloğu kaydet
            if ($blockStart) {
                $events[] = [
                    'id' => "empty_{$roomId}_{$blockStart->format('Ymd')}",
                    'title' => "Oda $roomNumber (boş)",
                    'start' => $blockStart->format('Y-m-d'),
                    'end' => $current->format('Y-m-d'),
                    'color' => '#28a745',
                    'textColor' => '#fff',
                    'extendedProps' => [
                        'roomId' => $roomId,
                        'status' => 'boş',
                        'customer_name' => '',
                        'balance' => '0.00',
                        'payment_status' => ''
                    ]
                ];
                $blockStart = null;
            }

            // Bu gün doluysa, dolu event'i ekle (sadece bir kez)
            $res = $occupiedDates[$dateStr];
            $resId = $res['id'];

            if (!isset($added[$resId])) {
                $events[] = [
                    'id' => $res['id'],
                    'title' => "Oda $roomNumber",
                    'start' => $res['start_date'],
                    'end' => date('Y-m-d', strtotime($res['end_date'] . ' +1 day')),
                    'color' => match($res['payment_status']) {
                        'tamamlandı' => '#28a745',
                        'kısmen'     => '#ffc107',
                        default      => '#dc3545',
                    },
                    'extendedProps' => [
                        'roomId' => $roomId,
                        'status' => $res['status'],
                        'customer_name' => $res['customer_name'] ?? 'Müşteri',
                        'balance' => number_format($res['payment_amount'], 2),
                        'payment_status' => $res['payment_status']
                    ]
                ];
                $added[$resId] = true;
            }
        }

        $current->modify('+1 day');
    }

    // Dönem sonuna kadar devam eden boşluk varsa ekle
    if ($blockStart) {
        $events[] = [
            'id' => "empty_{$roomId}_{$blockStart->format('Ymd')}",
            'title' => "Oda $roomNumber (boş)",
            'start' => $blockStart->format('Y-m-d'),
            'end' => $periodEnd->format('Y-m-d'),
            'color' => '#28a745',
            'textColor' => '#fff',
            'extendedProps' => [
                'roomId' => $roomId,
                'status' => 'boş',
                'customer_name' => '',
                'balance' => '0.00',
                'payment_status' => ''
            ]
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($events);
