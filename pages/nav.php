<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <!-- <title>Baseball Analytics</title> -->
    <!-- <link rel="stylesheet" href="../css/main.css"/> -->
    <style>
        body{
            background-color: var(--background-color);
            color: var(--text-color); 
            width: 100% !important;
        }
        nav { 
            margin-top: 15px; 
            display: flex;
            flex-direction: column;
            border-top: 1px var(--text-color) solid;
            border-bottom: 1px var(--text-color) solid;
        }
        nav a { 
            margin-right: 15px; 
            text-decoration: none; 
            font-size: 1.1em; 
            width: fit-content;
            padding: 0 15px;
        }
        nav ul,li{
            list-style: none;
            display: inline-block;
        }
        nav ul{
            padding-bottom: 10px;
        }
        nav ul li{
            position: relative;
            width: max-content;
            align-items: center;
            height: 30px;
            padding: 10px 0px 10px 5px;
        }
        nav ul  li  ul{
            display: none;
            position: absolute;
            top: 100%;		/* 2차 메뉴를 1차 메뉴의 아래에 위치시킨다 */
            left: 0;		/* 2차 메뉴를 1차 메뉴의 왼쪽 벽에 붙인다 */
            width: 100%;
            height: auto;
            padding-left: 0;
            background-color: var(--text-color);
            color: var(--background-color);
            box-sizing: border-box;  
        }
        nav ul  li:hover  ul {
            display: flex;  
            flex-direction: column;
            box-sizing: border-box;  
            width: 100%;    	
        }
        nav ul li:hover{
            background-color: var(--text-color);
            color: var(--background-color);
        }
        nav ul li ul li:hover{
            background-attachment: fixed;
        }
    </style>
</head>
<body>

    <!-- 로그인/회원가입  -->
    <nav>
        <ul>
            <li><a href="index.php">Home</a> </li>
            <li>
                <a href="#">Analytics by Player</a> 
                <ul>
                    <li><a href="player_score_trend.php">Score Trend</a></li>
                    <li><a href="player_score_position.php">Score Position</a></li>
                    <li><a href="salary_ranking.php">Salary Ranking</a></li>
                </ul>
            </li>
            <li>
                <a href="#">Analytics by Team</a> 
                <ul>
                    <li><a href="team_ranking.php">Score Ranking</a></li>
                    <li><a href="team_salary_olap.php">Salary</a></li>
                </ul>
            </li>
            <li>
                <a href="#">Analytics by Season/Game</a> 
                <ul>
                    <li><a href="game_salary_olap.php">salary</a></li>
                    <li><a href="game_position.php">position</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <!-- <hr/> -->
</body>

</html>
