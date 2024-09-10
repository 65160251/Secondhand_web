<?php
session_start();
include '../database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // ตรวจสอบรหัสผ่านที่เข้ารหัสกับรหัสผ่านที่ผู้ใช้กรอก
        if (password_verify($password, $user['password'])) {
            // ตั้งค่าข้อมูล session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // ใช้ role ในการตรวจสอบ
            header("Location: ../index.php");
            exit();
        } else {
            header("Location: login.html");
        }
    } else {
        header("Location: login.html");
    }

    $stmt->close();
    $conn->close();
}
?>
