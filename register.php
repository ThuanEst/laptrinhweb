<?php
require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kiểm tra xem các trường đã được điền đầy đủ chưa
    if (empty($_POST['name']) || empty($_POST['password']) || empty($_POST['rpassword']) || empty($_POST['email'])) {
        echo "Vui lòng điền đầy đủ thông tin!";
    } else {
        // Lấy dữ liệu từ biểu mẫu
        $name = $_POST['name'];
        $password = $_POST['password'];
        $rpassword = $_POST['rpassword'];
        $email = $_POST['email'];

        // Kiểm tra xem mật khẩu và mật khẩu nhập lại có khớp nhau không
        if ($password != $rpassword) {
            echo "Mật khẩu không khớp!";
        } else {
            // Hash mật khẩu trước khi lưu vào cơ sở dữ liệu (tăng tính bảo mật)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            try {
                // Thực hiện truy vấn để chèn dữ liệu vào cơ sở dữ liệu
                $sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':username', $name);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                header("Location: login.php");
                exit();
            } catch (PDOException $e) {
                echo "Đăng ký không thành công: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="./public/css/style.css" rel="stylesheet">
    <title>Đăng ký</title>
</head>

<body>
    <header class="header">
        <nav class="nav-list">
            <ul class="list-item">
                <li><a href="list.php">Home | </a></li>
                <li><a href="login.php">Đăng nhập | </a></li>
                <li><a href="register.php">Đăng ký</a></li>
            </ul>
        </nav>
    </header>

    <div class="container-form">
        <form action="" method="POST" enctype="multipart/form-data">
            <h3 class="text-form">Màn hình đăng ký</h3>
            <div class="control-group">
                <label for="name">Username</label>
                <input type="text" name="name" id="name">
            </div>

            <div class="control-group">
                <label for="password">Mật khẩu</label>
                <input type="password" name="password" id="password">
            </div>

            <div class="control-group">
                <label for="re-password">Nhập lại mật khẩu</label>
                <input type="password" name="rpassword" id="re-password">
            </div>

            <div class="control-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email">
            </div>

            <div class="group-button group-button-right">
                <a href="login.php">Đã có tài khoản</a>
                <button type="submit">Đăng ký</button>
            </div>
        </form>
    </div>

    <footer class="footer">
        <div class="text-footer">Lập trình web @2024</div>
    </footer>
</body>

</html>