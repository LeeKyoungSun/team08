<?php
// 2170045 서자영
session_start();
session_destroy();

echo "<script>
        alert('로그아웃 되었습니다.');
        location.href = 'index.php'; // 메인 페이지로 이동
      </script>";
?>
