<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Operator Control</title>
    <script src="https://cdn.socket.io/4.8.1/socket.io.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .control-panel {
            margin-top: 20px;
            padding: 20px;
            border-radius: 10px;
            background-color: #f1f1f1;
        }
        input, button {
            margin: 10px;
        }
        .timer {
            font-size: 20px;
        }
    </style>
</head>
<body>
 <i><h2 style='text-align: center; font-weight: bold;' class="mt-2">Refi Score Controls</h2></i>
 <hr>
    <div class="container mt-4">
    <div class="row">
        <!-- Sepak Bola -->
        <div class="col-md-4" style="background-color:rgb(164, 164, 164);">
            <div class="card shadow-sm">
                <div class="card-body" style="background-color:rgb(174, 206, 252);">
                    <h3 class="card-title text-center">⚽ Sepak Bola</h3>
                    <div class="mb-2">
                        <label class="form-label">Tim A:</label>
                        <input type="text" id="football-teamA" class="form-control" value="Team A">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Tim B:</label>
                        <input type="text" id="football-teamB" class="form-control" value="Team B">
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Skor Tim A:</label>
                            <input type="number" id="football-scoreA" class="form-control" value="0">
                        </div>
                        <div class="col">
                            <label class="form-label">Skor Tim B:</label>
                            <input type="number" id="football-scoreB" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="mt-2">
                        <label class="form-label">Babak:</label>
                        <input type="number" id="football-set" class="form-control" value="1"  min="1" max="2">
                    </div>
                    <button id="football-update" class="btn btn-primary w-100 mt-2">Update Skor</button>
                    <div class="text-center mt-3">
                        <h5>Waktu: <span id="football-time">00:00</span></h5>
                        <button id="football-startPause" class="btn btn-warning">Start</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Basket -->
        <div class="col-md-4" style="background-color:rgb(164, 164, 164);">
            <div class="card shadow-sm">
                <div class="card-body" style="background-color:rgb(248, 179, 145);">
                    <h3 class="card-title text-center">🏀 Basket</h3>
                    <div class="mb-2">
                        <label class="form-label">Tim A:</label>
                        <input type="text" id="basketball-teamA" class="form-control" value="Team A">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Tim B:</label>
                        <input type="text" id="basketball-teamB" class="form-control" value="Team B">
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Skor Tim A:</label>
                            <input type="number" id="basketball-scoreA" class="form-control" value="0">
                        </div>
                        <div class="col">
                            <label class="form-label">Skor Tim B:</label>
                            <input type="number" id="basketball-scoreB" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="mt-2">
                        <label class="form-label">Kuartet:</label>
                        <input type="number" id="basketball-set" class="form-control" value="1" min="1" max="4">
                    </div>
                    <button id="basketball-update" class="btn btn-primary w-100 mt-2">Update Skor</button>
                    <div class="text-center mt-3">
                        <h5>Waktu: <span id="basketball-time">00:00</span></h5>
                        <button id="basketball-startPause" class="btn btn-warning">Start</button>
                    </div>
                    
                </div>
            </div>
        </div>

        <!-- Voli -->
        <div class="col-md-4" style="background-color:rgb(164, 164, 164);">
            <div class="card shadow-sm">
                <div class="card-body" style="background-color:rgb(219, 255, 142);">
                    <h3 class="card-title text-center">🏐 Voli</h3>
                    <div class="mb-2">
                        <label class="form-label">Tim A:</label>
                        <input type="text" id="volleyball-teamA" class="form-control" value="Team A">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Tim B:</label>
                        <input type="text" id="volleyball-teamB" class="form-control" value="Team B">
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Skor Tim A:</label>
                            <input type="number" id="volleyball-scoreA" class="form-control" value="0">
                        </div>
                        <div class="col">
                            <label class="form-label">Skor Tim B:</label>
                            <input type="number" id="volleyball-scoreB" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="mt-2">
                        <label class="form-label">Set:</label>
                        <input type="number" id="volleyball-set" class="form-control" value="1" min="1" max="5">
                    </div>
                    <button id="volleyball-update" class="btn btn-primary w-100 mt-2">Update Skor</button>
                    <div class="text-center mt-3" style="display: none;">
                        <h5>Waktu: <span id="volleyball-time">00:00</span></h5>
                        <button id="volleyball-startPause" class="btn btn-warning">Start</button>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/scoreboard.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        const socket = io("http://localhost:3000");

        // Timer Logic
        let timers = {
            football: { time: 0, interval: null, running: false },
            basketball: { time: 0, interval: null, running: false },
            volleyball: { time: 0, interval: null, running: false }
        };

        // Function to handle timer
        function startPauseTimer(sport) {
    let status_timer = 'stop'; // Inisialisasi status timer

    // Cek apakah timer sedang berjalan
    if (timers[sport].running) {
        // Hentikan interval dan ubah status ke 'pause'
        clearInterval(timers[sport].interval);
        timers[sport].running = false;
        status_timer = 'pause'; // Update status menjadi 'pause'
    } else {
        // Mulai interval dan jalankan timer
        timers[sport].interval = setInterval(() => {
            timers[sport].time++; // Increment waktu setiap detik
            updateTime(sport); // Perbarui tampilan waktu di operator
        }, 1000);
        timers[sport].running = true;
        status_timer = 'run'; // Update status menjadi 'run'
    }

    // Kirim status timer dan waktu terkini ke server
    socket.emit('timer_status', {
        sport,
        status: status_timer,
        time: timers[sport].time
    });
}


        // Update the time display
        function updateTime(sport) {
            const minutes = Math.floor(timers[sport].time / 60);
            const seconds = timers[sport].time % 60;
            document.getElementById(`${sport}-time`).innerText = `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
        }

        // Update score and send data to server
        function updateScore(sport) {
            const teamA = document.getElementById(`${sport}-teamA`).value;
            const teamB = document.getElementById(`${sport}-teamB`).value;
            const scoreA = parseInt(document.getElementById(`${sport}-scoreA`).value);
            const scoreB = parseInt(document.getElementById(`${sport}-scoreB`).value);
            const set = parseInt(document.getElementById(`${sport}-set`).value);

            socket.emit('change_score', {
                sport,
                teamA,
                teamB,
                scoreA,
                scoreB,
                set,
                time: timers[sport].time
            });
        }

        // Event Listeners for the Update buttons and Start/Pause
        document.getElementById('football-update').addEventListener('click', () => updateScore('football'));
        document.getElementById('basketball-update').addEventListener('click', () => updateScore('basketball'));
        document.getElementById('volleyball-update').addEventListener('click', () => updateScore('volleyball'));

        document.getElementById('football-startPause').addEventListener('click', () => startPauseTimer('football'));
        document.getElementById('basketball-startPause').addEventListener('click', () => startPauseTimer('basketball'));
        document.getElementById('volleyball-startPause').addEventListener('click', () => startPauseTimer('volleyball'));
    </script>
</body>
</html>
