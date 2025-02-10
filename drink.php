<?php
require_once 'config/database.php';

// Fetch all dried food products
try {
    $stmt = $pdo->prepare("SELECT * FROM drinks ORDER BY name");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    $products = [];
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>อาหารแห้ง - ร้านขายของชำ</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        

    </style>
</head>

<body>
    <?php include './components/navbar.php'; ?>
    
    <main class="container py-4">
        <h2 class="mb-4">เครื่องดื่ม</h2>
        <div class="row g-4">
            <?php foreach ($products as $product): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="deal-item position-relative">
                        <div class="d-flex align-items-center h-100">
                            <div class="promo-item-image">
                                <img class="cover-image" 
                                     src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                     onerror="this.src='images/default.jpg'" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>">
                            </div>
                            
    
                            
                            <?php
                            $stockClass = '';
                            $stockText = '';
                            if ($product['stock'] > 50) {
                                $stockClass = 'in-stock';
                                $stockText = 'มีสินค้า';
                            } elseif ($product['stock'] > 0) {
                                $stockClass = 'low-stock';
                                $stockText = 'ใกล้หมด';
                            } else {
                                $stockClass = 'out-of-stock';
                                $stockText = 'หมด';
                            }
                            ?>
                            <span class="stock-status <?php echo $stockClass; ?>">
                                <?php echo $stockText; ?>
                            </span>
                            
                            <div class="promo-item-detail">
                                <div class="promo-item-name">
                                    <span><?php echo htmlspecialchars($product['name']); ?></span>
                                </div>
                                <div class="promo-item-desc">
                                    <?php echo htmlspecialchars($product['description']); ?>
                                </div>
                                <div class="promo-item-price">
                                    <div class="d-flex flex-column">
                                        <span class="discount-price">
                                            ฿<?php echo number_format($product['price'], 2); ?>
                                        </span>
                                    </div>
                                    <?php if ($product['stock'] > 0): ?>
                                        <button class="add-to-cart btn" 
                                                data-product-id="<?php echo $product['di_id']; ?>">
                                            <i class="fas fa-cart-plus"></i> เพิ่มลงตะกร้า
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-secondary" disabled>
                                            <i class="fas fa-times"></i> สินค้าหมด
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="product-count">
                                <i class="fas fa-box"></i> <?php echo $product['stock']; ?> ชิ้น
                            </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <?php include './components/footer.php'; ?>

    <script>
        document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        
        fetch('addtocart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                productId: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // อัพเดท UI ตะกร้า
                document.querySelector('.total-amount').textContent = 
                    '฿' + data.total.toFixed(2);
                    
                // แสดงข้อความสำเร็จ
                alert('เพิ่มสินค้าลงตะกร้าแล้ว!');
                
                // รีเฟรชแผงตะกร้าถ้าเปิดอยู่
                if (document.getElementById('cart-panel').classList.contains('active')) {
                    location.reload();
                }
            } else {
                alert('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง');
        });
    });
});

    </script>
</body>
</html>