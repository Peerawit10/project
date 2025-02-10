<?php
$host = 'localhost';
$dbname = 'auth_system';  // ชื่อฐานข้อมูลของคุณ
$username = 'root';      // username MySQL ของคุณ
$password = '';         // password MySQL ของคุณ

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>