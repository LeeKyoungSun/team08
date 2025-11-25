<?php
// 2170045 서자영
session_start();
include 'db_connect.php';

if (!isset($_SESSION['userid'])) {
    die("로그인이 필요합니다.");
}

$userId = $_SESSION['userid'];
$newUserName = $_POST['userName'];

// DB에 UPDATE
$sql = "UPDATE Users SET userName = ? WHERE userId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $newUserName, $userId);

if ($stmt->execute()) {
    // 세션 정보도 함께 UPDATE
    $_SESSION['username'] = $newUserName;
    
    echo "<script>
            alert('이름이 성공적으로 변경되었습니다.');
            location.href = 'mypage.php';
          </script>";
} else {
    echo "<script>
            alert('이름 수정 실패: " . $conn->error . "');
            window.history.back();
          </script>";
}
$stmt->close();
$conn->close();
?>
