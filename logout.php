<?php
// เริ่ม session
session_start();

// ลบข้อมูลทั้งหมดใน session
$_SESSION = array();

// ลบ session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// ทำลาย session
session_destroy();

// redirect กลับไปหน้า login
header("Location: login.php");
exit();
?>