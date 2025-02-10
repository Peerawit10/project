<?php
session_start();
$page_title = "ร้านขายของชำ";
require_once 'config/database.php';
require_once 'components/functions.php';
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <link rel="stylesheet" href="styles.css?t=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
</head>

<body>

    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="user-dashboard">
            <div class="profile">
                <p class="fs-5 ">
                    <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
    </p>
                
            </div>
        </div>
    <?php else: ?>

        <div class="user-dashboard">
            <a href="login.php" class="btn btn-primary">เข้าสู่ระบบ</a>
        </div>
    <?php endif; ?>

    <header>
        <div class="menu-category d-none d-md-block">
            <div class="header-container">
                <div class="logo">
                    <a href="../pro/index.php">
                        <img src="images/logoB.png" alt="Logo" class="logo-image">
                </div>
                <div class="mt-3 pt-5">
                    <div class="row bg-dark text-white">
                        <div class="col col-12">
                            <div class="container-fluid container-xxl p-0 position-relative">
                                <div class="menu-navbar scroll-menu">
                                    <div class="menu-navbar-inner d-flex justify-content-between align-items-center">
                                        <div class="d-inline-flex flex-md-row flex-row justify-content-start nav-bg w-100">
                                            <div class="menu-links">
                                                <a href="../pro/hot.php" rel="Promotion Menu Link">
                                                    <div class="row p-0 m-0 align-items-center">
                                                        <div id="nav-card1" class="nav-card m-0 col col-12 col-md-12 text-center text-md-center">
                                                            <img class="pe-none" onerror="this.onerror=null; this.src='images/hot.png'" src="images/hot.png">
                                                            <div class="m-0 col col-12 col-md-12 text-center text-md-center">
                                                                <span class="d-block">เมนูยอดฮิต</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="menu-links">
                                                <a href="../pro/driedfood.php" rel="Promotion Menu Link">
                                                    <div class="row p-0 m-0 align-items-center">
                                                        <div id="nav-card2" class="nav-card m-0 col col-12 col-md-12 text-center text-md-center">
                                                            <img class="pe-none" onerror="this.onerror=null; this.src='images/driedfood.png'" src="images/driedfood.png">
                                                            <div class="m-0 col col-12 col-md-12 text-center text-md-center">
                                                                <span class="d-block">อาหารแห้ง</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="menu-links">
                                                <a href="../pro/drink.php" rel="Promotion Menu Link">
                                                    <div class="row p-0 m-0 align-items-center">
                                                        <div id="nav-card3" class="nav-card m-0 col col-12 col-md-12 text-center text-md-center">
                                                            <img class="pe-none" onerror="this.onerror=null; this.src='images/drink.png'" src="images/drink.png">
                                                            <div class="m-0 col col-12 col-md-12 text-center text-md-center">
                                                                <span class="d-block">เครื่องดื่ม</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="menu-links">
                                                <a href="../pro/condiment.php" rel="Promotion Menu Link">
                                                    <div class="row p-0 m-0 align-items-center">
                                                        <div id="nav-card4" class="nav-card m-0 col col-12 col-md-12 text-center text-md-center">
                                                            <img class="pe-none" onerror="this.onerror=null; this.src='images/comdiment.png'" src="images/comdiment.png">
                                                            <div class="m-0 col col-12 col-md-12 text-center text-md-center">
                                                                <span class="d-block">เครื่องปรุง</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="menu-links">
                                                <a href="../pro/snack.php" rel="Promotion Menu Link">
                                                    <div class="row p-0 m-0 align-items-center">
                                                        <div id="nav-card5" class="nav-card m-0 col col-12 col-md-12 text-center text-md-center">
                                                            <img class="pe-none" onerror="this.onerror=null; this.src='images/snack.png'" src="images/snack.png">
                                                            <div class="m-0 col col-12 col-md-12 text-center text-md-center">
                                                                <span class="d-block">ขนม</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="menu-links">
                                                <a href="../pro/items.php" rel="Promotion Menu Link">
                                                    <div class="row p-0 m-0 align-items-center">
                                                        <div id="nav-card6" class="nav-card m-0 col col-12 col-md-12 text-center text-md-center">
                                                            <img class="pe-none" onerror="this.onerror=null; this.src='images/items.png'" src="images/items.png">
                                                            <div class="m-0 col col-12 col-md-12 text-center text-md-center">
                                                                <span class="d-block">ของใช้</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </header>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // การเริ่มใช้งานที่ชัดเจนมากขึ้น
    var dropdownElementList = document.querySelectorAll('.dropdown-toggle')
    var dropdownList = Array.from(dropdownElementList).map(function(dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
});
</script>
</body>

</html>