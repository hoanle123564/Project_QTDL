<?php
$host = 'localhost';
$dbname = 'quan_ly_thu_vien';
$username = 'root';
$password = '';
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES 'utf8'"); // Hỗ trợ tiếng Việt
} catch (PDOException $e) {
    die("Lỗi kết nối: " . $e->getMessage());
}
?>