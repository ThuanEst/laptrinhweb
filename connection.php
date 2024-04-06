<?php
// Thông tin kết nối MySQL
$servername = "localhost";
$username = "root";
$password = "";
$database = "bkc";

try {
    // Tạo kết nối
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // Đặt chế độ báo lỗi PDO thành ngoại lệ
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Kết nối không thành công: " . $e->getMessage();
}
