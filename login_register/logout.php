<?php
session_start();
session_unset(); // ลบข้อมูลเซสชันทั้งหมด
session_destroy(); // ทำลายเซสชัน

header("Location: ../index.php"); // เปลี่ยนเส้นทางไปยังหน้า index.php
exit();
?>
