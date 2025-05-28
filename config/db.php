<?php
// Veritabanı bağlantısı PDO ile

$host = 'localhost';
$db   = 'citypansiyon';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
    PDO::ATTR_EMULATE_PREPARES   => false,                  
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

/**
 * Veri ekleme fonksiyonu
 * @param string $table
 * @param array $data [sutun => değer]
 * @return int|false Insert edilen ID veya false
 */
function veri_ekle($table, $data) {
    global $pdo;
    $columns = implode(", ", array_keys($data));
    $placeholders = implode(", ", array_fill(0, count($data), '?'));

    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(array_values($data))) {
        return $pdo->lastInsertId();
    }
    return false;
}

/**
 * Veri çekme fonksiyonu
 * @param string $table
 * @param string|null $where WHERE koşulu (örn: "id = ?")
 * @param array $params WHERE için bind parametreleri (örn: [5])
 * @return array|false
 */
function veri_cek($table, $where = null, $params = []) {
    global $pdo;
    $sql = "SELECT * FROM $table";
    if ($where) {
        $sql .= " WHERE $where";
    }
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        return $stmt->fetchAll();
    }
    return false;
}

/**
 * Veri güncelleme fonksiyonu
 * @param string $table
 * @param array $data [sutun => değer]
 * @param string $where WHERE koşulu (örn: "id = ?")
 * @param array $whereParams WHERE için bind parametreleri (örn: [5])
 * @return bool
 */
function veri_guncelle($table, $data, $where, $whereParams = []) {
    global $pdo;
    $setParts = [];
    foreach ($data as $column => $value) {
        $setParts[] = "$column = ?";
    }
    $setStr = implode(", ", $setParts);

    $sql = "UPDATE $table SET $setStr WHERE $where";
    $stmt = $pdo->prepare($sql);

    $values = array_values($data);
    $params = array_merge($values, $whereParams);
    return $stmt->execute($params);
}

/**
 * Veri silme fonksiyonu
 * @param string $table
 * @param string $where WHERE koşulu (örn: "id = ?")
 * @param array $params WHERE için bind parametreleri (örn: [5])
 * @return bool
 */
function veri_sil($table, $where, $params = []) {
    global $pdo;
    $sql = "DELETE FROM $table WHERE $where";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($params);
}
