<?php
require_once 'connection.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kiểm tra xem các trường đã được điền đầy đủ chưa
    if (empty($_POST['name']) || empty($_POST['password'])) {
        echo "Vui lòng nhập tên người dùng và mật khẩu!";
    } else {
        // Lấy dữ liệu từ biểu mẫu
        $name = $_POST['name'];
        $password = $_POST['password'];

        try {
            // Truy vấn để lấy thông tin về người dùng từ cơ sở dữ liệu
            $sql = "SELECT * FROM users WHERE username = :username";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $name);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Kiểm tra mật khẩu
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    header("Location: list.php");
                    exit();
                } else {
                    echo "Sai mật khẩu!";
                }
            } else {
                echo "Tên người dùng không tồn tại!";
            }
        } catch (PDOException $e) {
            echo "Đăng nhập không thành công: " . $e->getMessage();
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
    <title>Đăng nhập</title>
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
            <h3 class="text-form">Màn hình đăng nhập</h3>
            <div class="control-group">
                <label for="name">Username</label>
                <input type="text" name="name" id="name">
            </div>

            <div class="control-group">
                <label for="password">Mật khẩu</label>
                <input type="password" name="password" id="password">
            </div>

            <div class="control-group control-checked">
                <input type="checkbox" name="">
                <label for="">Ghi nhớ đăng nhập</label>
            </div>

            <div class="group-button group-button-right">
                <a href="#">Quên mật khẩu</a>
                <button type="submit">Đăng nhập</button>
            </div>
        </form>
    </div>

    <footer class="footer">
        <div class="text-footer">Lập trình web @2024</div>
    </footer>
</body>

</html>