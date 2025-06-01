<?php
require 'config/db.php';

$rooms = $pdo->query("SELECT id, room_number FROM rooms ORDER BY room_number ASC")->fetchAll(PDO::FETCH_ASSOC);

$resources = [];
foreach ($rooms as $room) {
    $resources[] = [
        'id' => $room['id'],
        'title' => 'Oda ' . $room['room_number']
    ];
}

echo json_encode($resources);
