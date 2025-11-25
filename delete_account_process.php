<?php
// 2170045 서자영
session_start();
include 'db_connect.php';

// 로그인 확인 + 탈퇴 버튼 눌렀는지 확인
if (!isset($_SESSION['userid']) || !isset($_POST['confirm_delete'])) {
    die("잘못된 접근입니다.");
}

$userId = $_SESSION['userid'];

// Users 테이블에서 본인 삭제
$sql = "DELETE FROM Users WHERE userId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userId);

if ($stmt->execute()) {
    // 탈퇴 성공 > 로그아웃
    session_destroy();
    
    echo "<script>
            alert('회원 탈퇴가 완료되었습니다.');
            location.href = 'index.php'; // 메인으로 이동
          </script>";
} else {
    // 탈퇴 실패
    echo "<script>
            alert('탈퇴 처리 중 오류가 발생했습니다: " . $conn->error . "');
            location.href = 'mypage.php'; // 마이페이지로 이동
          </script>";
}

$stmt->close();
$conn->close();
?>
