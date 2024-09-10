<?php
session_start();
include '../database/db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $role = $_POST['role']; // รับบทบาทจากฟอร์มลงทะเบียน

    // ตรวจสอบการมีอยู่ของผู้ใช้
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username already exists.";
    } else {
        // เพิ่มผู้ใช้ใหม่
        $sql = "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $password, $email, $role);
        $stmt->execute();
        
        echo "Registration successful!";
        header("Location: login.html");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
