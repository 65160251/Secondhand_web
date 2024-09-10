<?php
session_start();
include '../database/db.php';

// ตรวจสอบว่าผู้ใช้เป็นผู้ขาย
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../index.php");
    exit();
}

// ตรวจสอบว่ามีการส่งข้อมูลมาหรือไม่
if (isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];
    $seller_id = $_SESSION['user_id'];

    // ตรวจสอบสินค้าว่าตรงกับผู้ขายหรือไม่
    $sql = "SELECT seller_id FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $product = $result->fetch_assoc();
        if ($product['seller_id'] === $seller_id) {
            // ลบสินค้าจากฐานข้อมูล
            $sql = "DELETE FROM products WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $product_id);
            if ($stmt->execute()) {
                echo "Product deleted successfully!";
            } else {
                echo "Error deleting product: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "You do not have permission to delete this product.";
        }
    } else {
        echo "Product not found.";
    }

    $conn->close();
    header("Location: ../index.php");
    exit();
}
?>
