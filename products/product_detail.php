<?php
session_start();
include '../database/db.php'; 

$product_id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดสินค้า</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <header class="bg-dark text-white text-center py-3">
        <h1>รายละเอียดสินค้า</h1>
        <a href="../index.php" class="btn btn-secondary">กลับสู่หน้าหลัก</a>
    </header>
    <main class="container my-5">
        <?php if ($product): ?>
            <div class="row">
                <div class="col-md-6">
                    <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                    <?php if (!empty($product['image_url'])): ?>
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid">
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <p><strong>ราคา:</strong> <?php echo htmlspecialchars($product['price']); ?> บาท</p>
                    <p><strong>รายละเอียด:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
                    
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'buyer'): ?>
                        <form action="cart.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" name="add_to_cart" class="btn btn-primary btn-lg">เพิ่มลงตะกร้า</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <p class="alert alert-danger">ไม่พบสินค้า</p>
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

