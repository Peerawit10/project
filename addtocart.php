<?php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['productId'])) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ถูกต้อง']);
    exit;
}

$productId = $data['productId'];

// สร้างตะกร้าถ้ายังไม่มี
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// เตรียมข้อมูลสินค้า
$productData = [
    'id' => $productId,
    'table' => null  // จะถูกกำหนดค่าเมื่อเจอสินค้า
];

// ค้นหาสินค้าและระบุตาราง
$queries = [
    'dried_foods' => ['id_column' => 'd_id', 'table' => 'dried_foods'],
    'drinks' => ['id_column' => 'di_id', 'table' => 'drinks'],
    'items' => ['id_column' => 'i_id', 'table' => 'items'],
    'snack' => ['id_column' => 's_id', 'table' => 'snack'],
    'condiment' => ['id_column' => 'c_id', 'table' => 'condiment']
];

foreach ($queries as $info) {
    $stmt = $pdo->prepare("SELECT price FROM {$info['table']} WHERE {$info['id_column']} = ?");
    $stmt->execute([$productId]);
    if ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $productData['table'] = $info['table'];
        break;
    }
}

// เพิ่ม/เพิ่มจำนวนสินค้าในตะกร้า
$cartKey = $productData['table'] . '_' . $productData['id'];
if (isset($_SESSION['cart'][$cartKey])) {
    $_SESSION['cart'][$cartKey]++;
} else {
    $_SESSION['cart'][$cartKey] = 1;
}

// คำนวณยอดรวมใหม่
$total = 0;
foreach ($_SESSION['cart'] as $key => $qty) {
    list($table, $pid) = explode('_', $key);
    foreach ($queries as $info) {
        if ($info['table'] === $table) {
            $stmt = $pdo->prepare("SELECT price FROM {$info['table']} WHERE {$info['id_column']} = ?");
            $stmt->execute([$pid]);
            if ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $total += $product['price'] * $qty;
                break;
            }
        }
    }
}

echo json_encode([
    'success' => true,
    'total' => $total,
    'quantity' => $_SESSION['cart'][$cartKey]
]);