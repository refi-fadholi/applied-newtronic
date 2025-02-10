<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Score;

class ScoreController extends Controller {
    // Simpan skor ke database
    public function updateScore(Request $request) {
        $score = Score::updateOrCreate(
            ['sport' => $request->sport],
            [
                'teamA' => $request->teamA,
                'teamB' => $request->teamB,
                'scoreA' => $request->scoreA,
                'scoreB' => $request->scoreB
            ]
        );

        return response()->json(['message' => 'Score updated!', 'score' => $score]);
    }

    // Ambil skor terbaru dari database
    public function getScores() {
        return response()->json(Score::all());
    }

    public function logScore(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'sport' => 'required|string',
            'team_a' => 'required|string',
            'team_b' => 'required|string',
            'score_a' => 'required|integer',
            'score_b' => 'required|integer',
            'additional_info' => 'nullable|string',
        ]);

        // Simpan log ke database
        $scoreLog = ScoreLog::create($validated);

        // Kirimkan data yang baru disimpan sebagai respons
        return response()->json($scoreLog);
    }
}
