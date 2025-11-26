<?php
// player_score_trend.php
// 2176279 이유진
// 2271107 이경선
session_start();
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>Player Score Trend</title>
    <link rel="stylesheet" href="css/main.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 10px;
        }
        .header h1 { margin: 0; }
        .login-menu { text-align: right; }

        #chartContainer{
            width: 80%;
            color: var(--text-color);
            margin-top: 40px;
        }

        .form-section {
            margin: 20px 0;
        }

        .form-group {
            margin: 10px 0;
        }

        label {
            display: inline-block;
            min-width: 80px;
            margin-right: 10px;
        }

        select {
            min-width: 300px;
            padding: 5px;
        }

        button {
            margin-top: 10px;
            padding: 10px 20px;
        }
    </style>
</head>

<body>
    <?php include 'pages/nav.php'; ?>
<div class="layout">
    

    <h1>Get Score Trend by Player</h1>
    <div>
        Choose a player who you want to know his or her score trend.<br/>
        You can filter the players by team, name, etc.
    </div>

    <div class="form-section">
        <div class="form-group">
            <label for="teamSelect">Team</label>
            <select id="teamSelect">
                <option value="">-- Select Team --</option>
            </select>
        </div>

        <div class="form-group">
            <label for="playerSelect">Player</label>
            <select id="playerSelect" disabled>
                <option value="">-- Select Team First --</option>
            </select>
        </div>

        <div>
            <button id="btn" disabled>Get Result</button>
        </div>
    </div>

    <div id="chartContainer">
        <canvas id="playerChart"></canvas>
    </div>
</div>

<script>
    window.addEventListener('DOMContentLoaded', loadTeams);

    function loadTeams() {
        fetch('player_trend.php?action=getTeams')
            .then(res => res.json())
            .then(teams => {
                const select = document.getElementById('teamSelect');
                teams.forEach(team => {
                    const option = document.createElement('option');
                    option.value = team.teamID;
                    option.textContent = team.name;  // 이미 연도 범위 포함
                    // 차트 제목용으로 원래 이름 저장
                    option.setAttribute('data-raw-name', team.rawName || team.name);
                    select.appendChild(option);
                });
            })
            .catch(err => {
                console.error('fetch error:', err);
                alert('팀 목록을 불러오는데 실패했습니다.');
            });
    }

    const teamSelect = document.getElementById("teamSelect");
    const playerSelect = document.getElementById("playerSelect");
    const ctx = document.getElementById("playerChart").getContext("2d");
    const getResult = document.getElementById("btn");
    let chartInstance = null;

    // 팀 선택 시 선수 목록 로드
    teamSelect.addEventListener("change", () => {
        const teamID = teamSelect.value;
        playerSelect.innerHTML = '<option value="">-- Select Player --</option>';
        playerSelect.disabled = true;
        getResult.disabled = true;

        if (teamID) {
            fetch(`player_trend.php?action=getPlayers&teamID=${teamID}`)
                .then(res => res.json())
                .then(players => {
                    players.forEach(player => {
                        const option = document.createElement('option');
                        option.value = player.playerID;
                        option.textContent = player.fullName;
                        playerSelect.appendChild(option);
                    });
                    playerSelect.disabled = false;
                })
                .catch(err => {
                    console.error('fetch error:', err);
                    alert('선수 목록을 불러오는데 실패했습니다.');
                });
        }
    });

    playerSelect.addEventListener("change", () => {
        getResult.disabled = !playerSelect.value;
    });

    // 선수 선택 시 그래프 표시
    getResult.addEventListener("click", () => {
        const playerID = playerSelect.value;

        if (playerID) {
            fetch(`player_trend.php?action=getStats&playerID=${playerID}`)
                .then(res => res.json())
                .then(data => {
                    console.log(data);

                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    const stats = data.stats;
                    const labels = stats.map(s => s.yearID);
                    const AB = stats.map(s => s.AB);
                    const H = stats.map(s => s.H);
                    const hr = stats.map(s => s.HR);
                    const avg = stats.map(s => (parseFloat(s.battingAvg) * 1000).toFixed(0));

                    if (chartInstance) chartInstance.destroy();

                    // 차트 제목용으로 원래 팀 이름 사용 (연도 범위 없이)
                    const rawTeamName = teamSelect.options[teamSelect.selectedIndex].getAttribute('data-raw-name') ||
                        teamSelect.options[teamSelect.selectedIndex].text;
                    const playerName = playerSelect.options[playerSelect.selectedIndex].text;

                    chartInstance = new Chart(ctx, {
                        type: "line",
                        data: {
                            labels,
                            datasets: [
                                {
                                    label: "At Bats (AB)",
                                    data: AB,
                                    borderColor: "#36A2EB",
                                    backgroundColor: "rgba(54,162,235,0.2)",
                                    yAxisID: "y1",
                                    tension: 0.3
                                },
                                {
                                    label: "Hits (H)",
                                    data: H,
                                    borderColor: "#FF6384",
                                    backgroundColor: "rgba(255,99,132,0.2)",
                                    yAxisID: "y2",
                                    tension: 0.3
                                },
                                {
                                    label: "Home Runs (HR)",
                                    data: hr,
                                    borderColor: "#FF9F40",
                                    backgroundColor: "rgba(255,159,64,0.2)",
                                    yAxisID: "y2",
                                    tension: 0.3
                                },
                                {
                                    label: "Batting Average (×1000)",
                                    data: avg,
                                    borderColor: "#36eb7bff",
                                    backgroundColor: "rgba(54,235,123,0.2)",
                                    yAxisID: "y1",
                                    tension: 0.3
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            scales: {
                                y1: {
                                    type: "linear",
                                    position: "left",
                                    title: {
                                        display: true,
                                        text: "At Bats / Batting Average (×1000)"
                                    },
                                    grid: {
                                        drawOnChartArea: true
                                    }
                                },
                                y2: {
                                    type: "linear",
                                    position: "right",
                                    title: {
                                        display: true,
                                        text: "Hits / Home Runs"
                                    },
                                    grid: {
                                        drawOnChartArea: false
                                    }
                                }
                            },
                            plugins: {
                                title: {
                                    display: true,
                                    text: rawTeamName + " - " + playerName,
                                    font: { size: 20 }
                                },
                                legend: {
                                    display: true,
                                    position: 'top'
                                }
                            }
                        }
                    });
                })
                .catch(err => {
                    console.error('fetch error:', err);
                    alert('성적 데이터를 불러오는데 실패했습니다.');
                });
        }
    });

    Chart.defaults.color = "#FAFAF8";
</script>

<?php
if (isset($conn)) {
    $conn->close();
}
?>
</body>

</html>