<?php
require_once 'connection.php';

session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit(); // Dừng kịch bản sau khi chuyển hướng
}


// Kiểm tra xem ID đã được truyền qua URL hay không
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Truy vấn SQL để lấy thông tin của người dùng cần xem
    $sql = "SELECT * FROM users WHERE user_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Kiểm tra xem có dữ liệu người dùng hay không
    if ($user) {
        $username = $user['user_name'];
        $email = $user['user_email'];
    } else {
        // Xử lý trường hợp không tìm thấy người dùng
        echo "Không tìm thấy người dùng.";
    }
}

// Kiểm tra nếu người dùng nhấp vào đăng xuất
if (isset($_GET['logout'])) {
    // Xóa tất cả các biến session
    session_unset();
    // Hủy session
    session_destroy();
    // Chuyển hướng người dùng về trang đăng nhập
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="./public/css/style.css" rel="stylesheet">
    <title>List</title>
</head>

<body>
    <header class="header">
        <nav class="nav-list">
            <ul class="list-item">
                <li><a href="list.php">Home | </a></li>
                <li><a href="?logout=1">Đăng xuất</a></li>
            </ul>
        </nav>
    </header>

    <div class="container-form">
        <form action="" method="POST" enctype="multipart/form-data">
            <h3 class="text-form">Màn hình chi tiết</h3>
            <div class="control-flex">
                <h4>Username:</h4>
                <span><?php echo isset($username) ? $username : ''; ?></span>
            </div>

            <div class="control-flex">
                <h4>Email:</h4>
                <span><?php echo isset($email) ? $email : ''; ?></span>
            </div>

            <div class="group-button group-button-right">
                <a class="btn-edit" href="update.php?id=<?php echo $id; ?>">Chỉnh sửa</a>
            </div>
        </form>
    </div>


    <footer class="footer">
        <div class="text-footer">Lập trình web @2024</div>
    </footer>
</body>

</html>