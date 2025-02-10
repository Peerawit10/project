<?php
$page_title = "แดชบอร์ด";
require_once 'config/database.php';
require_once 'components/functions.php';
require_once 'components/header.php';

// ตรวจสอบว่ามีการล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<div class="bg-white p-8 rounded-lg shadow-md w-96">
    <h2 class="text-2xl font-bold mb-6">ยินดีต้อนรับ <?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>
    
    <form method="POST" action="logout.php">
        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full"
                type="submit">
            ออกจากระบบ
        </button>
    </form>
</div>


