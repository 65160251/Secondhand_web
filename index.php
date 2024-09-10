<?php
session_start();
include 'database/db.php';

$sql = "SELECT * FROM products";
if (isset($_SESSION['role']) && $_SESSION['role'] === 'seller') {
    $seller_id = $_SESSION['user_id'];
    $sql .= " WHERE seller_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $seller_id);
} else {
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="bg-dark text-white text-center py-3">
        <h1>ร้านค้ามือสอง</h1>
        <?php if (isset($_SESSION['username'])): ?>
            <p class="welcome">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'seller'): ?>
                <p><a href="products/add_product.php" class="btn btn-secondary">ประกาศขายสินค้า</a></p><br>
            <?php endif; ?>
        <?php else: ?>
            <p><a href="login_register/login.html" class="btn btn-primary">เข้าสู่ระบบ</a> | <a href="login_register/register.html" class="btn btn-primary">ลงทะเบียน</a></p>
        <?php endif; ?>
        <form action="search.php" method="get" class="form-inline justify-content-center my-3">
            <input type="text" name="query" class="form-control mr-2" placeholder="Search products..." required>
            <button type="submit" class="btn btn-outline-light">ค้นหาสินค้า</button>
        </form>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'buyer'): ?>
            <div class="d-flex justify-content-end mb-3 px-3">
                <a href="products/cart.php" class="btn btn-outline-light mr-2">ตะกร้าสินค้า (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a>
                <a href="products/order_history.php" class="btn btn-outline-light">ประวัติการสั่งซื้อ</a>
            </div>
        <?php endif; ?>
    </header>
    <main class="container my-5">
        <h2>สินค้าทั้งหมด</h2>
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while($product = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <?php if (!empty($product['image_url'])): ?>
                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="card-img-top">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="card-text">ราคา: <?php echo htmlspecialchars($product['price']); ?> บาท</p>
                                <a href="products/product_detail.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">คลิกดูรายละเอียด</a>
                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'seller'): ?>
                                    <form action="products/delete_product.php" method="post" class="mt-2">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <button type="submit" name="delete_product" class="btn btn-danger btn-sm">ลบสินค้า</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>ไม่มีสินค้า</p>
            <?php endif; ?>
        </div>
        <?php if (isset($_SESSION['username'])): ?>
            <a href="login_register/logout.php" class="btn btn-danger btn-sm">ออกจากระบบ</a>
        <?php endif; ?>
    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>


