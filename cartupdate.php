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
    // เริ่ม transaction
    $pdo->beginTransaction();

    // ดึงข้อมูลสินค้าและล็อคแถวสำหรับการอัพเดท
    $stmt = $pdo->prepare("SELECT id, price, stock FROM stock WHERE id = ? FOR UPDATE");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        throw new Exception('ไม่พบสินค้า');
    }

    if (isset($data['action']) && $data['action'] === 'remove') {
        // คืนสต็อกสินค้า
        if (isset($_SESSION['cart'][$productId])) {
            $returnStock = $_SESSION['cart'][$productId]; // จำนวนที่จะคืนกลับ
            $newStock = $product['stock'] + $returnStock;

            // อัพเดทสต็อกในฐานข้อมูล
            $updateStmt = $pdo->prepare("UPDATE stock SET stock = ? WHERE id = ?");
            $updateStmt->execute([$newStock, $productId]);

            // ลบสินค้าออกจากตะกร้า
            unset($_SESSION['cart'][$productId]);
        }
    } else if (isset($data['quantity'])) {
        // กรณีอัพเดทจำนวน
        $newQuantity = (int)$data['quantity'];
        $currentQuantity = isset($_SESSION['cart'][$productId]) ? $_SESSION['cart'][$productId] : 0;

        if ($newQuantity < $currentQuantity) {
            // กรณีลดจำนวน คืนสต็อก
            $returnStock = $currentQuantity - $newQuantity;
            $newStock = $product['stock'] + $returnStock;

            $updateStmt = $pdo->prepare("UPDATE stock SET stock = ? WHERE id = ?");
            $updateStmt->execute([$newStock, $productId]);
        } else if ($newQuantity > $currentQuantity) {
            // กรณีเพิ่มจำนวน ตรวจสอบและลดสต็อก
            $reduceStock = $newQuantity - $currentQuantity;
            if ($product['stock'] < $reduceStock) {
                throw new Exception('สินค้าในสต็อกไม่เพียงพอ');
            }
            $newStock = $product['stock'] - $reduceStock;

            $updateStmt = $pdo->prepare("UPDATE stock SET stock = ? WHERE id = ?");
            $updateStmt->execute([$newStock, $productId]);
        }

        $_SESSION['cart'][$productId] = $newQuantity;
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
        'message' => 'อัพเดทตะกร้าสำเร็จ'
    ]);
} catch (Exception $e) {
    // ถ้าเกิดข้อผิดพลาด ให้ rollback การทำงานทั้งหมด
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}
