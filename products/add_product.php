<?php
session_start();
include '../database/db.php';

// ตรวจสอบว่าผู้ใช้เป็นผู้ขาย
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../index.php");
    exit();
}

// ตรวจสอบว่ามีการส่งข้อมูลฟอร์มมา
if (isset($_POST['add_product'])) {
    // รับข้อมูลจากฟอร์ม
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];
    $seller_id = $_SESSION['user_id']; // ใช้ user_id ของผู้ขาย

    // ตรวจสอบข้อมูลก่อนทำการเพิ่ม
    if (empty($name) || empty($price) || empty($description)) {
        echo "Please fill in all required fields.";
        exit();
    }

    // เตรียมคำสั่ง SQL สำหรับเพิ่มสินค้า
    $sql = "INSERT INTO products (name, description, price, image_url, seller_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssi", $name, $description, $price, $image_url, $seller_id);
        if ($stmt->execute()) {
            echo "Product added successfully!";
        } else {
            echo "Error executing SQL statement: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing SQL statement: " . $conn->error;
    }

    $conn->close();
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Add New Product</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">ประกาศขายสินค้า</h1>
        <a href="../index.php" class="btn btn-primary mb-4">กลับสู่หน้าแรก</a>

        <form action="add_product.php" method="POST">
            <div class="form-group">
                <label for="name">ชื่อสินค้า:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="description">รายละเอียด:</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="price">ราคา:</label>
                <input type="text" class="form-control" id="price" name="price" required>
            </div>

            <div class="form-group">
                <label for="image_url">URL รูปภาพ:</label>
                <input type="text" class="form-control" id="image_url" name="image_url">
            </div>

            <button type="submit" name="add_product" class="btn btn-success">เพิ่มสินค้า</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

