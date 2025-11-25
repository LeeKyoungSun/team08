<?php
// 2170045 서자영
/*
 * db_connect.php: DB 연결 파일
 */

$host = 'localhost';  
$user = 'team08'; 
$pass = 'team08';
$db   = 'team08';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>
