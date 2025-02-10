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

try {
    // ค้นหาสินค้าจากตาราง stock อย่างเดียว
    $stmt = $pdo->prepare("SELECT id, price FROM stock WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        throw new Exception('ไม่พบสินค้า');
    }

    // เพิ่มสินค้าลงตะกร้า
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]++;
    } else {
        $_SESSION['cart'][$productId] = 1;
    }

    // คำนวณยอดรวมใหม่
    $total = 0;
    foreach ($_SESSION['cart'] as $pid => $qty) {
        $stmt = $pdo->prepare("SELECT price FROM stock WHERE id = ?");
        $stmt->execute([$pid]);
        if ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $total += $item['price'] * $qty;
        }
    }

    echo json_encode([
        'success' => true,
        'total' => $total,
        'quantity' => $_SESSION['cart'][$productId]
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}