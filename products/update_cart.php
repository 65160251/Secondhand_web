<?php
session_start();

if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = intval($_POST['quantity']); // จำนวนใหม่ที่ผู้ใช้กรอก

    if ($quantity > 0) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        }
    }

    // เปลี่ยนเส้นทางไปยังหน้าตะกร้าสินค้า
    header("Location: cart.php");
    exit();
}
?>
