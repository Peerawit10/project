<?php
// ฟังก์ชันดึงข้อมูลสินค้าในตะกร้า
function getCartItems($pdo) {
    if (empty($_SESSION['cart'])) {
        return [];
    }

    $items = [];
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $stmt = $pdo->prepare("SELECT * FROM stock WHERE id = ?");
        $stmt->execute([$productId]);
        if ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $product['quantity'] = $quantity;
            $items[] = $product;
        }
    }
    return $items;
}
?>

<div id="cart-panel" class="cart-panel">
    <div class="cart-header">
        <h3>ตะกร้าสินค้า</h3>
        <button class="close-cart" aria-label="ปิดตะกร้า">&times;</button>
    </div>

    <div class="cart-items">
        <?php
        $cartItems = getCartItems($pdo);
        if (empty($cartItems)):
        ?>
            <div class="empty-cart">
                <img src="images/empty-cart.png" alt="ตะกร้าว่าง">
                <p>ตะกร้าสินค้าว่างเปล่า</p>
            </div>
        <?php else: ?>
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-item" data-id="<?php echo htmlspecialchars($item['id']); ?>">
                    <div class="item-image">
                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>"
                            alt="<?php echo htmlspecialchars($item['name']); ?>">
                    </div>
                    <div class="item-details">
                        <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                        <div class="price">฿<?php echo number_format($item['price'], 2); ?></div>
                        <div class="quantity-controls">
                            <button class="decrease-qty" aria-label="ลดจำนวน">-</button>
                            <input type="number" value="<?php echo $item['quantity']; ?>" min="1"
                                class="qty-input" readonly>
                            <button class="increase-qty" aria-label="เพิ่มจำนวน">+</button>
                        </div>
                    </div>
                    <button class="remove-item" aria-label="ลบรายการ">&times;</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="cart-footer">
        <div class="cart-total">
            <span>ยอดรวมทั้งสิ้น:</span>
            <span class="total-amount">
                ฿<?php
                    $total = array_reduce($cartItems, function ($carry, $item) {
                        return $carry + ($item['price'] * $item['quantity']);
                    }, 0);
                    echo number_format($total, 2);
                    ?>
            </span>
        </div>
        <button class="checkout-btn" <?php echo empty($cartItems) ? 'disabled' : ''; ?>>
            ดำเนินการสั่งซื้อ
        </button>
    </div>
</div>

<style>
    .cart-panel {
        position: fixed;
        top: 0;
        right: -400px;
        width: 400px;
        height: 100vh;
        background: white;
        box-shadow: -2px 0 5px rgba(0, 0, 0, 0.2);
        transition: right 0.3s ease-in-out;
        z-index: 1000;
        display: flex;
        flex-direction: column;
    }

    .cart-panel.active {
        right: 0;
    }

    .cart-header {
        padding: 1.2rem;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cart-header h3 {
        margin: 0;
        font-size: 1.3rem;
        color: #343a40;
        font-weight: 600;
    }

    .close-cart {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #6c757d;
        cursor: pointer;
        padding: 0.5rem;
        transition: color 0.2s;
    }

    .close-cart:hover {
        color: #343a40;
    }

    .cart-items {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
    }

    .cart-item {
        display: flex;
        padding: 1rem;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        margin-bottom: 1rem;
        position: relative;
        gap: 1rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .cart-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .item-image {
        width: 90px;
        height: 90px;
        border-radius: 6px;
        overflow: hidden;
    }

    .cart-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .item-details {
        flex: 1;
    }

    .item-details h4 {
        margin: 0 0 0.5rem 0;
        font-size: 1.1rem;
        color: #212529;
    }

    .price {
        color: #dc3545;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.8rem;
    }

    .quantity-controls button {
        width: 32px;
        height: 32px;
        border: 1px solid #dee2e6;
        background: white;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .quantity-controls button:hover {
        background: #f8f9fa;
        border-color: #adb5bd;
    }

    .qty-input {
        width: 50px;
        height: 32px;
        text-align: center;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 1rem;
    }

    .cart-footer {
        padding: 1.2rem;
        border-top: 1px solid #e9ecef;
        background: #f8f9fa;
    }

    .cart-total {
        display: flex;
        justify-content: space-between;
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1.1rem;
        color: #212529;
    }

    .total-amount {
        color: #dc3545;
    }

    .checkout-btn {
        width: 100%;
        padding: 1rem;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 1.1rem;
        font-weight: 600;
        transition: background-color 0.2s;
    }

    .checkout-btn:hover:not(:disabled) {
        background: #0056b3;
    }

    .checkout-btn:disabled {
        background: #adb5bd;
        cursor: not-allowed;
    }

    .empty-cart {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
    }

    .empty-cart img {
        width: 180px;
        margin-bottom: 1.5rem;
        opacity: 0.6;
    }

    .empty-cart p {
        font-size: 1.1rem;
        margin: 0;
    }

    .remove-item {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: none;
        border: none;
        color: #adb5bd;
        cursor: pointer;
        font-size: 1.2rem;
        padding: 0.5rem;
        transition: color 0.2s;
        border-radius: 50%;
    }

    .remove-item:hover {
        color: #dc3545;
        background: #f8f9fa;
    }

    @media (max-width: 480px) {
        .cart-panel {
            width: 100%;
            right: -100%;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cartButton = document.querySelector('.footer-bar .fa-shopping-cart').parentElement;
        const cartPanel = document.getElementById('cart-panel');
        const closeCart = document.querySelector('.close-cart');

        // เปิดตะกร้า
        cartButton.addEventListener('click', function(e) {
            e.preventDefault();
            cartPanel.classList.add('active');
        });

        // ปิดตะกร้า
        closeCart.addEventListener('click', function() {
            cartPanel.classList.remove('active');
        });

        // จัดการการเปลี่ยนแปลงจำนวนสินค้า
        document.querySelectorAll('.quantity-controls button').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.qty-input');
                const currentQty = parseInt(input.value);
                const isIncrease = this.classList.contains('increase-qty');

                if (isIncrease) {
                    input.value = currentQty + 1;
                } else if (currentQty > 1) {
                    input.value = currentQty - 1;
                }

                updateCart(
                    this.closest('.cart-item').dataset.id,
                    parseInt(input.value)
                );
            });
        });

        // เพิ่มโค้ดส่วนจัดการการลบสินค้า
        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', function() {
                const cartItem = this.closest('.cart-item');
                const productId = cartItem.dataset.id;

                // ส่งคำขอลบสินค้า
                fetch('cartupdate.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            productId: productId,
                            action: 'remove'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // ลบ element ออกจาก DOM
                            cartItem.remove();

                            // อัพเดทยอดรวม
                            document.querySelector('.total-amount').textContent =
                                '฿' + data.total.toFixed(2);

                            // ตรวจสอบว่าตะกร้าว่างหรือไม่
                            if (data.itemCount === 0) {
                                const emptyCart = `
                            <div class="empty-cart">
                                <img src="images/empty-cart.png" alt="ตะกร้าว่าง">
                                <p>ตะกร้าสินค้าว่างเปล่า</p>
                            </div>
                        `;
                                document.querySelector('.cart-items').innerHTML = emptyCart;
                                document.querySelector('.checkout-btn').disabled = true;
                            }
                        } else {
                            console.error('Failed to remove item:', data.message);
                            alert('ไม่สามารถลบสินค้าได้ กรุณาลองใหม่อีกครั้ง');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง');
                    });
            });
        });

        // ฟังก์ชัน updateCart สำหรับอัพเดทจำนวน
        function updateCart(productId, quantity) {
            fetch('cartupdate.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        productId: productId,
                        quantity: quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // อัพเดทยอดรวม
                        document.querySelector('.total-amount').textContent =
                            '฿' + data.total.toFixed(2);
                    }
                });
        }
    });
</script>