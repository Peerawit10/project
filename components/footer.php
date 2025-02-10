<?php
// ตรวจสอบว่า session ยังไม่ได้เริ่มต้น
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ตรวจสอบสถานะการล็อกอิน
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ร้านขายของชำ</title>
    <!-- <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"> -->
</head>

<body>
    <div class="footer-bar">
        <button><i class="fas fa-home"></i></button>
        <button onclick="openSearchPopup()"><i class="fas fa-search"></i></button>
        <?php if ($isLoggedIn): ?>
            <a href="cart.php"><button><i class="fas fa-shopping-cart"></i></button></a>
            <a href="profile.php"><button><i class="fas fa-user"></i></button></a>
            <a id="openmodal" ><button><i class="fa-solid fa-door-open"></i></button></a>
        <?php else: ?>
            <a href="login.php"><button><i class="fas fa-shopping-cart"></i></button></a>
            <a href="login.php"><button><i class="fas fa-user"></i></button></a>
            <a href="login.php"><button><i class="fa-solid fa-door-open"></i></button></a>
        <?php endif; ?>
    </div>

    <!-- ป๊อบอัพ -->
    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close">&times;</span>
            <img id="popup-image" src="" alt="">
            <h4 id="popup-name"></h4>
            <p id="popup-price"></p>
        </div>
    </div>

    <div id="logout-popup" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closeLogoutPopup()">&times;</span>
        <h4>Do you want to log out?</h4>
        <div class="popup-buttons">
            <button onclick="confirmLogout()">Yes</button>
            <button onclick="closeLogoutPopup()">No</button>
        </div>
    </div>
</div>

    <!-- ป๊อบอัพการค้นหา -->
    <div id="search-popup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closeSearchPopup()">&times;</span>
            <form id="search-form">
                <input type="text" id="search-input" placeholder="ค้นหาสินค้า...">
                <button type="submit">ค้นหา</button>
            </form>
        </div>
    </div>

    <script src="scripts.js"></script>
    <?php include 'slidingcart.php'; ?>
</body>

</html>