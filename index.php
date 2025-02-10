<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ร้านขายของชำ</title>
    <link rel="stylesheet" href="styles.css?t=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <?php include './components/navbar.php'; ?>
    <main>
        <section>
            <h2>สินค้าแนะนำ</h2>
            <div class="product-carousel">
                <button class="prev" onclick="scrollCarousel(-1)">‹</button>
                <div class="product-grid">
                    <?php
                    // ตัวอย่างสินค้า
                    $products = [
                        ["name" => "ข้าวสาร", "price" => 50, "image" => "images/rice.jpg"],
                        ["name" => "น้ำมันพืช", "price" => 30, "image" => "images/oil.jpg"],
                        ["name" => "น้ำปลา", "price" => 20, "image" => "images/fish_sauce.jpg"],
                        ["name" => "น้ำตาล", "price" => 25, "image" => "images/sugar.jpg"],
                        ["name" => "เกลือ", "price" => 10, "image" => "images/salt.jpg"],
                        ["name" => "ผงชูรส", "price" => 15, "image" => "images/msg.jpg"],
                    ];

                    foreach ($products as $product) {
                        echo "<div class='product-card' data-name='{$product['name']}' data-price='{$product['price']}' data-image='{$product['image']}'>
                                    <img src='{$product['image']}' alt='{$product['name']}'>
                                    <div class='product-info'>
                                        <h3>{$product['name']}</h3>
                                        <p>ราคา {$product['price']} บาท</p>
                                    </div>
                                  </div>";
                    }
                    ?>
                </div>
                <button class="next" onclick="scrollCarousel(1)">›</button>

        </section>

    </main>
    <!-- <p> Lorem ipsum dolor sit amet consectetur adipisicing elit. Hic necessitatibus dignissimos dolorum, illo vitae numquam repellat id assumenda sunt officiis consectetur et ipsa maiores nobis in esse molestiae deserunt a suscipit dolore porro, laudantium nulla voluptate temporibus. Perferendis ipsum nemo neque officia architecto alias obcaecati cum ipsa totam nesciunt, recusandae enim? Veniam dolor repudiandae necessitatibus nostrum iusto odit deserunt quaerat, dolores porro assumenda, expedita minus temporibus facilis. Autem officiis ea, nulla sed doloremque odit quod deserunt iste perferendis facere architecto, aspernatur repellendus. At cum, nobis minima, quod quidem sint voluptatibus ratione assumenda deleniti doloremque nostrum. Eligendi nesciunt incidunt facilis deserunt?</p> -->

    <?php include './components/footer.php'; ?>


    <script src="scripts.js"></script>
</body>

</html>