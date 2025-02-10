import sqlite3 from 'sqlite3';
import { open } from 'sqlite';

const dbPromise = open({
    filename: './scores.db',
    driver: sqlite3.Database
});

async function initDB() {
    const db = await dbPromise;
    await db.run(`
        CREATE TABLE IF NOT EXISTS score_logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            sport TEXT NOT NULL,
            teamA TEXT NOT NULL,
            teamB TEXT NOT NULL,
            scoreA INTEGER NOT NULL,
            scoreB INTEGER NOT NULL,
            timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    `);
}

async function logScore(sport, teamA, teamB, scoreA, scoreB) {
    const db = await dbPromise;
    await db.run(`
        INSERT INTO score_logs (sport, teamA, teamB, scoreA, scoreB)
        VALUES (?, ?, ?, ?, ?)
    `, [sport, teamA, teamB, scoreA, scoreB]);
}

export { initDB, logScore };
