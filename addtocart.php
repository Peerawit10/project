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
    // เริ่ม transaction
    $pdo->beginTransaction();

    // ดึงข้อมูลสินค้าและล็อคแถวสำหรับการอัพเดท
    $stmt = $pdo->prepare("SELECT id, price, stock FROM stock WHERE id = ? FOR UPDATE");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        throw new Exception('ไม่พบสินค้า');
    }

    // ตรวจสอบว่ามีสินค้าเพียงพอ
    if ($product['stock'] <= 0) {
        throw new Exception('สินค้าหมด');
    }

    // อัพเดทจำนวนสินค้าในคลัง
    $newStock = $product['stock'] - 1;
    $updateStmt = $pdo->prepare("UPDATE stock SET stock = ? WHERE id = ?");
    $updateStmt->execute([$newStock, $productId]);

    // เพิ่มสินค้าลงตะกร้า
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]++;
    } else {
        $_SESSION['cart'][$productId] = 1;
    }

    // คำนวณยอดรวมใหม่
    $total = 0;
    $itemCount = 0;
    foreach ($_SESSION['cart'] as $pid => $qty) {
        $stmt = $pdo->prepare("SELECT price FROM stock WHERE id = ?");
        $stmt->execute([$pid]);
        if ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $total += $item['price'] * $qty;
            $itemCount += $qty;
        }
    }

    // ยืนยัน transaction
    $pdo->commit();

    echo json_encode([
        'success' => true,
        'total' => $total,
        'itemCount' => $itemCount,
        'updatedStock' => $newStock,
        'quantity' => $_SESSION['cart'][$productId]
    ]);

} catch (Exception $e) {
    // ถ้าเกิดข้อผิดพลาด ให้ rollback การทำงานทั้งหมด
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}