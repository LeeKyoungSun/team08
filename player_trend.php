<?php
// player_trend.php
// 2271107 이경선
// 1. DB 연결
require_once 'db_connect.php';

// 2. AJAX 요청 처리 (팀 목록, 선수 목록, 성적 데이터)
if (isset($_GET['action'])) {
    header('Content-Type: application/json; charset=utf-8');

    // 팀 목록 조회 - 5년 이상 뛴 선수가 있는 팀만 표시
    if ($_GET['action'] === 'getTeams') {
        $sql = "
            SELECT DISTINCT 
                t1.teamID,
                (SELECT t2.name 
                 FROM Teams t2 
                 WHERE t2.teamID = t1.teamID 
                 ORDER BY t2.yearID DESC, t2.lgID DESC 
                 LIMIT 1) as name,
                (SELECT MIN(t3.yearID) 
                 FROM Teams t3 
                 WHERE t3.teamID = t1.teamID) as first_year,
                (SELECT MAX(t4.yearID) 
                 FROM Teams t4 
                 WHERE t4.teamID = t1.teamID) as last_year
            FROM Teams t1
            WHERE t1.teamID IN (
                -- 5년 이상 뛴 선수가 최소 1명 이상 있는 팀만
                SELECT DISTINCT b.teamID
                FROM Batting b
                WHERE b.AB > 25
                GROUP BY b.teamID, b.playerID
                HAVING COUNT(DISTINCT b.yearID) >= 5
            )
            ORDER BY name, first_year
        ";

        $result = $conn->query($sql);

        if (!$result) {
            echo json_encode(['error' => 'Query error: ' . $conn->error]);
            exit;
        }

        $teams = [];
        while ($row = $result->fetch_assoc()) {
            $name = $row['name'];
            $firstYear = $row['first_year'];
            $lastYear = $row['last_year'];

            // 연도 범위 추가
            if ($firstYear && $lastYear) {
                if ($firstYear == $lastYear) {
                    $displayName = $name . " (" . $firstYear . ")";
                } else {
                    $displayName = $name . " (" . $firstYear . "-" . $lastYear . ")";
                }
            } else {
                $displayName = $name;
            }

            $teams[] = [
                'teamID' => $row['teamID'],
                'name' => $displayName,
                'rawName' => $name
            ];
        }

        echo json_encode($teams);
        exit;
    }

    // 특정 팀의 선수 목록 조회 (5년 이상 데이터가 있는 선수만)
    if ($_GET['action'] === 'getPlayers' && isset($_GET['teamID'])) {
        $teamID = $_GET['teamID'];
        $sql = "
            SELECT 
                b.playerID, 
                m.nameFirst, 
                m.nameLast,
                COUNT(DISTINCT b.yearID) as years_played
            FROM Batting b
            JOIN Master m ON b.playerID = m.playerID
            WHERE b.teamID = ? AND b.AB > 25
            GROUP BY b.playerID, m.nameFirst, m.nameLast
            HAVING COUNT(DISTINCT b.yearID) >= 5
            ORDER BY m.nameLast, m.nameFirst
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $teamID);
        $stmt->execute();
        $result = $stmt->get_result();
        $players = [];
        while ($row = $result->fetch_assoc()) {
            $players[] = [
                'playerID' => $row['playerID'],
                'fullName' => $row['nameFirst'] . ' ' . $row['nameLast'] . ' (' . $row['years_played'] . ' years)'
            ];
        }
        echo json_encode($players);
        exit;
    }

    //Window Function
    // 선수 성적 데이터 조회 (연도별 합산 - stint 처리)
    if ($_GET['action'] === 'getStats' && isset($_GET['playerID'])) {
        $playerID = $_GET['playerID'];

        // ★ Window Function LAG() 사용하여 전년 대비 증감 계산
        $sql = "
            SELECT
                yearID,
                teamName,
                total_G,
                total_AB,
                total_H,
                total_HR,
                battingAvg,
                -- ★ LAG() Window Function으로 전년 값 가져오기
                total_HR - LAG(total_HR, 1, 0) OVER (ORDER BY yearID) AS hr_change,
                ROUND(battingAvg - LAG(battingAvg, 1, 0.0) OVER (ORDER BY yearID), 3) AS avg_change
            FROM (
                -- 연도별 집계 서브쿼리
                SELECT
                    b.playerID,
                    b.yearID,
                    (SELECT t.name 
                     FROM Batting b2
                     JOIN Teams t ON b2.teamID = t.teamID AND b2.yearID = t.yearID AND b2.lgID = t.lgID
                     WHERE b2.playerID = b.playerID AND b2.yearID = b.yearID
                     ORDER BY b2.AB DESC
                     LIMIT 1) as teamName,
                    SUM(b.G) AS total_G,
                    SUM(b.AB) AS total_AB,
                    SUM(b.H) AS total_H,
                    SUM(b.HR) AS total_HR,
                    CASE WHEN SUM(b.AB) > 0 
                         THEN ROUND(SUM(b.H) / SUM(b.AB), 3) 
                         ELSE 0.000 
                    END AS battingAvg
                FROM Batting b
                WHERE b.playerID = ?
                GROUP BY b.playerID, b.yearID
                HAVING SUM(b.AB) >= 25
            ) AS yearly_stats
            ORDER BY yearID ASC
        ";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            echo json_encode(['error' => '쿼리 준비 오류: ' . $conn->error]);
            exit;
        }

        $stmt->bind_param("s", $playerID);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            echo json_encode(['error' => '쿼리 실행 오류: ' . $stmt->error]);
            exit;
        }

        $stats = [];
        while ($row = $result->fetch_assoc()) {
            $stats[] = [
                'yearID' => $row['yearID'],
                'teamName' => $row['teamName'] ?? 'Unknown',
                'G' => $row['total_G'],
                'AB' => $row['total_AB'],
                'H' => $row['total_H'],
                'HR' => (int)$row['total_HR'],
                'hr_change' => (int)$row['hr_change'],  // ★ Window Function 결과
                'battingAvg' => number_format((float)$row['battingAvg'], 3),
                'avg_change' => number_format((float)$row['avg_change'], 3)  // ★ Window Function 결과
            ];
        }

        $stmt->close();

        if (empty($stats)) {
            echo json_encode(['error' => "해당 선수의 데이터가 없거나, 연간 타석 25개 이상인 시즌이 없습니다."]);
            exit;
        }

        echo json_encode(['success' => true, 'stats' => $stats], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// API 엔드포인트가 아닌 직접 접근 시
if (isset($conn)) {
    $conn->close();
}
?>