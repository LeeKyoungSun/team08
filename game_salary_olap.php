<?php
session_start();
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>player Score Trend</title>
    <link rel="stylesheet" href="css/main.css" />
    <style>
        .header {
            /* display: flex; */
            justify-content: space-between;
            align-items: center;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
        }
        .layout{
            overflow:hidden;
        }
        .cardLayout{
            display: inline-flex;
            overflow-x: scroll;
            /* width: 100%; */
        }
        .card{
            background-color: var(--secondary-text-color);
            width: 250px;
            height: 200px;
            margin: 0 30px;
        }
    </style>
</head>

<body>
    <div class="layout">
        <?php
        include 'pages/nav.php';
        ?>
        <h1>Get Salary Comparison by League <br/>in specific year </h1>
        <div>
            Get Salary Comparison by League in specific year<br/>
            you can filter the year and Get the result.
        </div>
   <div class="form-section">
       <div class="form-row">
           <div class="form-group">
               <label for="yearSelect"> Year </label>
               <select id="yearSelect">
                   <option value="">-- Select Year --</option>
               </select>
           </div>
       </div>

       <button id="searchBtn" > Get Result </button>
   </div>

   <div id="loadingDiv" class="loading" style="display:none;">
       Loading Data
   </div>

   <div id="errorDiv" class="error-message" style="display:none;"></div>
   
   <!-- 선택한 연도의 리그별 연봉 비교 -->
   <div class="cardLayout">
        <div class="card">

        </div>
        <div class="card">

        </div>
        <div class="card">

        </div>
        <div class="card">

        </div>
        <div class="card">

        </div><div class="card">

        </div><div class="card">

        </div>
    </div>     
    


<script>
    // 페이지 로드 시 연도 목록 불러오기
    window.addEventListener('DOMContentLoaded', loadYears);

    function loadYears() {
        fetch('game_roster.php?action=getYears')
            .then(res => res.json())
            .then(years => {
                const select = document.getElementById('yearSelect');
                years.forEach(year => {
                    const option = document.createElement('option');
                    option.value = year;
                    option.textContent = `${year}`;
                    select.appendChild(option);
                });
            })
            .catch(err => showError('Failed to load year data.'));
    }

    // 연도 선택 시 경기 목록 불러오기
    document.getElementById('searchBtn').addEventListener('click', function() {
        const yearID = document.getElementById('yearSelect').value;
        console.log("yearID:" +yearID);

        if (yearID) {
            fetch(`game_roster.php?action=getGames&yearID=${yearID}`)
                .then(res => res.json())
                .then(games => {
                    console.log(games);
                    games.forEach(gameID => {
                        // 여기 수정해서 연도별 리그 정보 가져오기, 리그명 -> 각각의 연봉 가져오기
                        const option = document.createElement('option');
                        option.value = gameID;
                        const gameType = gameID.includes('ALS') ? 'American League All-Star' :
                            gameID.includes('NLS') ? 'National League All-Star' : gameID;
                        option.textContent = gameType;
                    });
                })
                .catch(err => {
                    console.error('fetch error:', err);
                    showError('경기 목록을 불러오는데 실패했습니다.');
                }); 
        }
    });

    </script>
        <?php
        $conn->close();
        ?>
    </div>
</body>


</html>