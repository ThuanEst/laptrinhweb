<?php
require_once 'connection.php';

session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit(); // Dừng kịch bản sau khi chuyển hướng
}


// Kiểm tra xem ID đã được truyền qua URL hay chưa
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Truy vấn SQL để lấy thông tin của người dùng cần cập nhật
    $sql = "SELECT * FROM users WHERE user_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Kiểm tra xem có dữ liệu người dùng hay không
    if ($user) {
        $name = $user['username'];
        $email = $user['email'];


        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'];
            $email = $_POST['email'];

            // Cập nhật dữ liệu vào cơ sở dữ liệu
            $update_sql = "UPDATE users SET user_name = :username, user_email = :email WHERE user_id = :id";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bindParam(':username', $name);
            $update_stmt->bindParam(':email', $email);

            // Thực thi truy vấn cập nhật
            if ($update_stmt->execute()) {
                // Nếu cập nhật thành công, chuyển hướng về trang list.php
                header('Location: list.php');
                exit();
            } else {
                echo "Cập nhật không thành công.";
            }
        }

        // Hiển thị dữ liệu cũ trong form
        $name = $user['username'];
        $email = $user['email'];
        $password = $user['password'];
    } else {
        echo "Không tìm thấy người dùng.";
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
    <title>Update</title>
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
            <h3 class="text-form">Màn hình cập nhật</h3>
            <div class="control-group">
                <label for="name">Username</label>
                <input type="text" name="name" id="name" value="<?php echo isset($name) ? $name : ''; ?>">
            </div>

            <div class="control-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo isset($email) ? $email : ''; ?>">
            </div>

            <div class="group-button group-button-right">
                <button type="submit">Cập nhật</button>
            </div>
        </form>
    </div>

    <footer class="footer">
        <div class="text-footer">Lập trình web @2024</div>
    </footer>
</body>

</html>