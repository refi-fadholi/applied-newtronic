import express from 'express';
import http from 'http';
import { Server } from 'socket.io';

const app = express();
const server = http.createServer(app);
const io = new Server(server, {
    cors: {
        origin: "*",
        methods: ["GET", "POST"]
    }
});

let scores = {
    football: { teamA: 'Team A', teamB: 'Team B', scoreA: 0, scoreB: 0, set: 0, time: 0 },
    basketball: { teamA: 'Team A', teamB: 'Team B', scoreA: 0, scoreB: 0, set: 0, time: 0 },
    volleyball: { teamA: 'Team A', teamB: 'Team B', scoreA: 0, scoreB: 0, set: 0, time: 0 }
};

io.on('connection', (socket) => {
    console.log('User connected');
    socket.emit('score_update', scores);  // Send initial scores to client

    socket.on('change_score', (data) => {
        scores[data.sport] = {
            teamA: data.teamA,
            teamB: data.teamB,
            scoreA: data.scoreA,
            scoreB: data.scoreB,
            set: data.set,
            time: data.time
        };
        io.emit('score_update', scores);  // Send updated scores to all clients
    });

    socket.on('disconnect', () => {
        console.log('User disconnected');
    });

    
// Handle timer status update (start/pause)
socket.on('timer_status', (data) => {
    const { sport, status, time } = data;
    
    // Perbarui data status timer di server (jika perlu disimpan)
    scores[sport].status = status;
    scores[sport].time = time;

    // Kirim status dan waktu terkini ke semua client (termasuk scoreboard)
    io.emit('timer_status_update', {
        sport,
        status,
        time
    });
});

});

server.listen(3000, () => {
    console.log('Server running on port 3000');
});


