<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Live Scoreboard</title>
    <script src="https://cdn.socket.io/4.8.1/socket.io.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .scoreboard {
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .football { background-color:rgb(174, 206, 252); }
        .basketball { background-color:rgb(248, 179, 145); }
        .volleyball { background-color:rgb(219, 255, 142); }
        .score-text { font-size: 1.5rem; font-weight: bold; }
    </style>
</head>
<body>
 <i><h2 style='text-align: center;' class="mt-2">Refi Live Score</h2></i>
 <hr>
  <div class="row mt-2">
        <!-- Sepak Bola -->
        <div class="col-md-4">
            <div class="scoreboard football">
        <h2>⚽ Sepak Bola</h2>
        <p>Home: <span font-weight="bold" id="football-teamA">-</span></p>
        <p>Away: <span font-weight="bold" id="football-teamB">-</span></p>
        <p>Skor: <span id="football-score">0 - 0</span></p>
        <p>Babak: <span id="football-set">0</span></p>
        <p>Waktu: <span id="football-time">00:00</span></p>
        <button id="football-startPause" style="display: none;"></button>
    </div>
    </div>

    <!-- Basket -->
        <div class="col-md-4">
            <div class="scoreboard basketball">
        <h2>🏀 Basket</h2>
        <p>Home: <span font-weight="bold" id="basketball-teamA">-</span></p>
        <p>Away: <span font-weight="bold" id="basketball-teamB">-</span></p>
        <p>Skor: <span id="basketball-score">0 - 0</span></p>
        <p>Kuartet: <span id="basketball-set">0</span></p>
        <p>Waktu: <span id="basketball-time">00:00</span></p>
        <button id="basketball-startPause" style="display: none;"></button>
    </div>
    </div>

        <!-- Voli -->
        <div class="col-md-4">
            <div class="scoreboard volleyball">
        <h2>🏀 Voli</h2>
        <p>Home: <span id="volleyball-teamA">-</span></p>
        <p>Away: <span id="volleyball-teamB">-</span></p>
        <p>Skor: <span id="volleyball-score">0 - 0</span></p>
        <p>Set: <span id="volleyball-set">0</span></p>
        <p style="display: none;">Waktu: <span id="volleyball-time">00:00</span></p>
        <button id="volleyball-startPause" style="display: none;"></button>
    </div>
    </div>
    </div>

    <script>
        const socket = io("http://localhost:3000");

        socket.on("score_update", (data) => {
            console.log("Score update receivedq:", data);

            // Fungsi untuk memperbarui tampilan scoreboard
            function updateScoreboard(sport, teamA, teamB, score, set, time) {
                document.getElementById(`${sport}-teamA`).innerText = teamA;
                document.getElementById(`${sport}-teamB`).innerText = teamB;
                document.getElementById(`${sport}-score`).innerText = score;
                document.getElementById(`${sport}-set`).innerText = set;

                // Konversi waktu (detik ke menit:detik)
                let minutes = Math.floor(time / 60);
                let seconds = time % 60;
                document.getElementById(`${sport}-time`).innerText = 
                    `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            }

            // Perbarui setiap jenis olahraga
            if (data.football) {
                updateScoreboard("football", data.football.teamA, data.football.teamB, 
                    `${data.football.scoreA} - ${data.football.scoreB}`, 
                    data.football.set, data.football.time);
            }

            if (data.basketball) {
                updateScoreboard("basketball", data.basketball.teamA, data.basketball.teamB, 
                    `${data.basketball.scoreA} - ${data.basketball.scoreB}`, 
                    data.basketball.set, data.basketball.time);
            }

            if (data.volleyball) {
                updateScoreboard("volleyball", data.volleyball.teamA, data.volleyball.teamB, 
                    `${data.volleyball.scoreA} - ${data.volleyball.scoreB}`, 
                    data.volleyball.set, data.volleyball.time);
            }
        });

       // Objek untuk menyimpan timer di Scoreboard
let scoreboardTimers = {};

socket.on('timer_status_update', (data) => {
    console.log("Time update received:", data);

    let { sport, status, time } = data;
    const minutes = Math.floor(time / 60);
    const seconds = time % 60;

    // Perbarui tampilan waktu di scoreboard
    const timeElement = document.getElementById(`${sport}-time`);
    if (timeElement) {
        timeElement.innerText = `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
    } else {
        console.error(`Elemen waktu untuk ${sport} tidak ditemukan.`);
    }

    // Jika status 'run', jalankan timer di Scoreboard
    if (status === 'run') {
        // Hapus interval lama jika ada
        if (scoreboardTimers[sport]) {
            clearInterval(scoreboardTimers[sport]);
        }

        // Buat interval baru agar waktu terus bertambah
        scoreboardTimers[sport] = setInterval(() => {
            time++;
            const minutes = Math.floor(time / 60);
            const seconds = time % 60;
            timeElement.innerText = `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
        }, 1000);
    } 
    // Jika status 'stop', hentikan timer di Scoreboard
    else {
        if (scoreboardTimers[sport]) {
            clearInterval(scoreboardTimers[sport]);
            delete scoreboardTimers[sport]; // Hapus timer dari objek
        }
    }
});

    </script>
</body>
</html>
