<?php
require_once 'connection.php';

session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit(); // Dừng kịch bản sau khi chuyển hướng
}


// Xử lý khi người dùng gửi yêu cầu xóa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $id = $_POST['id'];

    // Xóa dữ liệu từ cơ sở dữ liệu
    $sql = "DELETE FROM users WHERE user_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Chuyển hướng trở lại trang list sau khi xóa
    header("Location: list.php");
    exit();
}

// Số dòng dữ liệu mỗi trang
$rowsPerPage = 5;

// Tính tổng số dòng dữ liệu
$sqlCount = "SELECT COUNT(*) AS total FROM users";
$stmtCount = $conn->query($sqlCount);
$totalRows = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

// Tính tổng số trang
$totalPages = ceil($totalRows / $rowsPerPage);

// Xác định trang hiện tại
if (!isset($_GET['page']) || !is_numeric($_GET['page']) || $_GET['page'] < 1) {
    $currentPage = 1;
} elseif ($_GET['page'] > $totalPages) {
    $currentPage = $totalPages;
} else {
    $currentPage = $_GET['page'];
}

// Tính chỉ số bắt đầu của dữ liệu trong trang hiện tại
$startRow = ($currentPage - 1) * $rowsPerPage;

// Truy vấn SQL để lấy danh sách người dùng trong trang hiện tại
$sql = "SELECT * FROM users LIMIT $startRow, $rowsPerPage";
$stmt = $conn->query($sql);

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

    <div class="list-user">
        <h2>Danh sách user</h2>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td>
                            <a href="update.php?id=<?php echo $row['user_id']; ?>">Edit | </a>
                            <a href="view.php?id=<?php echo $row['user_id']; ?>">View | </a>
                            <form class="form-update" action="" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?php echo $row['user_id']; ?>">
                                <button class="btn-delete" type="submit" name="delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Phân trang -->
        <div class="pagination">
            <?php if ($totalPages > 1) : ?>
                <!-- Kiểm tra nếu có nhiều hơn 1 trang -->
                <?php if ($currentPage > 1) : ?>
                    <a href="?page=<?php echo ($currentPage - 1); ?>">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <?php if ($i == $currentPage) : ?>
                        <span><?php echo $i; ?></span>
                    <?php else : ?>
                        <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages) : ?>
                    <a href="?page=<?php echo ($currentPage + 1); ?>">Next</a>
                <?php endif; ?>
            <?php endif; ?>
            <!-- Kết thúc kiểm tra tổng số trang -->
        </div>


    </div>

    <footer class="footer">
        <div class="text-footer">Lập trình web @2024</div>
    </footer>
</body>

</html>