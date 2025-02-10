<?php
require_once 'config/database.php';

// Fetch only condiment products
try {
    $stmt = $pdo->prepare("SELECT * FROM stock WHERE category = 'items' ORDER BY name");
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
    <link rel="stylesheet" href="styles.css?t=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        

    </style>
</head>

<body>
    <?php include './components/navbar.php'; ?>
    
    <main class="container py-4">
        <h2 class="mb-4">ของใช้</h2>
        <div class="row g-4">
            <?php foreach ($products as $product): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="deal-item position-relative ">
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
                                                data-product-id="<?php echo $product['id']; ?>">
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
            button.addEventListener('click', async function() {
                const productId = this.dataset.productId;
                const cartButton = this;
                const productCard = cartButton.closest('.deal-item');

                try {
                    const response = await fetch('addtocart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            productId: productId
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // อัพเดทราคารวมในตะกร้า
                        document.querySelector('.total-amount').textContent = '฿' + data.total.toFixed(2);

                        // อัพเดทจำนวนสินค้าในตะกร้า
                        if (data.itemCount !== undefined && document.querySelector('.cart-count')) {
                            document.querySelector('.cart-count').textContent = data.itemCount;
                        }

                        // อัพเดทจำนวนสินค้าคงเหลือในการ์ด
                        if (data.updatedStock !== undefined) {
                            const stockCountElement = productCard.querySelector('.product-count');
                            if (stockCountElement) {
                                stockCountElement.innerHTML = `<i class="fas fa-box"></i> ${data.updatedStock} ชิ้น`;
                            }

                            // อัพเดทสถานะสินค้า
                            const stockStatusElement = productCard.querySelector('.stock-status');
                            if (stockStatusElement) {
                                let newStockClass = '';
                                let newStockText = '';

                                if (data.updatedStock > 50) {
                                    newStockClass = 'in-stock';
                                    newStockText = 'มีสินค้า';
                                } else if (data.updatedStock > 0) {
                                    newStockClass = 'low-stock';
                                    newStockText = 'ใกล้หมด';
                                } else {
                                    newStockClass = 'out-of-stock';
                                    newStockText = 'หมด';

                                    // ปิดปุ่มเพิ่มลงตะกร้าถ้าสินค้าหมด
                                    cartButton.outerHTML = `
                                <button class="btn btn-secondary" disabled>
                                    <i class="fas fa-times"></i> สินค้าหมด
                                </button>
                            `;
                                }

                                // อัพเดทคลาสและข้อความ
                                stockStatusElement.className = `stock-status ${newStockClass}`;
                                stockStatusElement.textContent = newStockText;
                            }
                        }
                    
                        // แสดง notification
                        const notification = document.createElement('div');
                        notification.className = 'cart-notification';
                        notification.innerHTML = `
                    <div class="notification-content">
                        <i class="fas fa-check-circle"></i>
                        เพิ่มสินค้าลงตะกร้าแล้ว
                    </div>
                `;
                        document.body.appendChild(notification);

                        setTimeout(() => {
                            notification.classList.add('fade-out');
                            setTimeout(() => {
                                notification.remove();
                            }, 300);
                        }, 2000);

                        // อัพเดท mini cart ถ้ามีการเปิดอยู่
                        if (document.getElementById('cart-panel')?.classList.contains('active')) {
                            await updateMiniCart();
                        }

                        // เพิ่ม animation ที่ปุ่ม
                        cartButton.classList.add('added');
                        setTimeout(() => {
                            location.reload(); // รีเฟรชหน้าหลังจากอัปเดตตะกร้า
                            cartButton.classList.remove('added');
                        }, 1000);
                        
                    } else {
                        throw new Error(data.message || 'เกิดข้อผิดพลาดในการเพิ่มสินค้า');
                    }
                } catch (error) {
                    console.error('Error:', error);

                    const errorNotification = document.createElement('div');
                    errorNotification.className = 'cart-notification error';
                    errorNotification.innerHTML = `
                <div class="notification-content">
                    <i class="fas fa-exclamation-circle"></i>
                    เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง
                </div>
            `;
                    document.body.appendChild(errorNotification);

                    setTimeout(() => {
                        errorNotification.classList.add('fade-out');
                        setTimeout(() => {
                            errorNotification.remove();
                        }, 300);
                    }, 2000);
                }
            });
        });
        // ฟังก์ชันสำหรับอัพเดท mini cart
        async function updateMiniCart() {
            try {
                const response = await fetch('get-cart-items.php');
                const data = await response.json();

                const cartPanel = document.getElementById('cart-panel');
                if (cartPanel) {
                    const cartContent = cartPanel.querySelector('.cart-items');
                    if (cartContent && data.items) {
                        cartContent.innerHTML = data.items.map(item => `
                    <div class="cart-item">
                        <img src="${item.image_url}" alt="${item.name}" onerror="this.src='images/default.jpg'">
                        <div class="cart-item-details">
                            <h4>${item.name}</h4>
                            <p>฿${item.price.toFixed(2)} × ${item.quantity}</p>
                        </div>
                    </div>
                `).join('');
                    }
                }
            } catch (error) {
                console.error('Error updating mini cart:', error);
            }
        }
    </script>
</body>
</html>