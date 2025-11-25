<?php
// 2170045 서자영
    include 'db_connect.php';

    if ($conn) {
        echo "<h1>Apache, PHP, MySQL 연동 완료</h1>";
        echo "<p>team08 DB 연동 성공</p>";

        // 마지막 user 테이블까지 연동 확인
        $sql = "SHOW TABLES LIKE 'Users'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            echo "<p style='color:green;'>연동 성공</p>";
        } else {
            echo "<p style='color:red;'>연동 실패</p>";
        }

        $conn->close();

    } else {
        echo "<h1>연동 실패</h1>";
    }
?>
