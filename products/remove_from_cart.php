<?php
session_start();

if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];

    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }

    // เปลี่ยนเส้นทางไปยังหน้าตะกร้าสินค้า
    header("Location: cart.php");
    exit();
}
?>
