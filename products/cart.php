<?php
session_start();
include '../database/db.php'; 

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    // ตรวจสอบว่ามีสินค้านี้อยู่ในตะกร้าหรือไม่
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // ดึงข้อมูลสินค้าจากฐานข้อมูล
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $product = $result->fetch_assoc();
                $_SESSION['cart'][$product_id] = [
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => $quantity
                ];
            }
            $stmt->close(); // ปิดการเชื่อมต่อฐานข้อมูล
        } else {
            // แจ้งเตือนหากไม่สามารถเตรียมคำสั่ง SQL ได้
            echo "Error preparing SQL statement.";
        }
    }
    $conn->close(); // ปิดการเชื่อมต่อฐานข้อมูล

    // เปลี่ยนเส้นทางไปยังหน้าตะกร้าสินค้า
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="bg-dark text-white text-center py-3">
        <h1>ตะกร้าสินค้าของคุณ</h1>
        <a href="../index.php" class="btn btn-light">กลับสู่หน้าหลัก</a>
    </header>
    <main class="container my-5">
        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
            <form action="checkout.php" method="post">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ชื่อสินค้า</th>
                            <th>จำนวน</th>
                            <th>ราคา</th>
                            <th>รวม</th>
                            <th>ดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_amount = 0;
                        foreach ($_SESSION['cart'] as $id => $product): 
                            $total = $product['quantity'] * $product['price'];
                            $total_amount += $total;
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td>
                                    <form action="update_cart.php" method="post">
                                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                        <input type="number" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" min="1" disabled>
                                    </form>
                                    </td>
                                <td><?php echo htmlspecialchars($product['price']); ?> บาท</td>
                                <td><?php echo number_format($total, 2); ?> บาท</td>
                                <td>
                                    <form action="remove_from_cart.php" method="post">
                                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                        <button type="submit" name="remove_from_cart" class="btn btn-danger btn-sm">ลบ</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3">ยอดรวม</td>
                            <td><?php echo number_format($total_amount, 2); ?> บาท</td>
                        </tr>
                    </tbody>
                </table>

                <h2 class="mt-4">เลือกวิธีการจัดส่ง</h2>
                <div class="form-group">
                    <select name="shipping_method" class="form-control" required>
                        <?php 
                        $shipping_methods = ['การจัดส่งมาตรฐาน', 'การจัดส่งด่วน', 'การจัดส่งวันถัดไป'];
                        foreach ($shipping_methods as $method): ?>
                            <option value="<?php echo htmlspecialchars($method); ?>"><?php echo htmlspecialchars($method); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" name="checkout" class="btn btn-light">ชำระเงิน</button>
            </form>
        <?php else: ?>
            <p class="text-center">ตะกร้าสินค้าของคุณว่างเปล่า</p>
        <?php endif; ?>
    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
