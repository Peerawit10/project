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

try {
    $pdo->beginTransaction();

    // ตรวจสอบว่าสินค้ามีอยู่จริง
    $stmt = $pdo->prepare("SELECT price FROM stock WHERE id = ?");
    $stmt->execute([$productId]);
    
    if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
        throw new Exception('ไม่พบสินค้า');
    }

    // ตรวจสอบว่าเป็นการลบสินค้าหรือไม่
    if (isset($data['action']) && $data['action'] === 'remove') {
        unset($_SESSION['cart'][$productId]);
    } 
    // ถ้าไม่ใช่การลบ ให้อัพเดทจำนวนสินค้า
    else if (isset($data['quantity'])) {
        $_SESSION['cart'][$productId] = $data['quantity'];
    }

    // คำนวณยอดรวมใหม่
    $total = 0;
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $pid => $qty) {
            $stmt = $pdo->prepare("SELECT price FROM stock WHERE id = ?");
            $stmt->execute([$pid]);
            if ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $total += $product['price'] * $qty;
            }
        }
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'total' => $total,
        'itemCount' => count($_SESSION['cart'])
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    exit;
}