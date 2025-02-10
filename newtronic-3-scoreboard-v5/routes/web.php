<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('scoreboard');
});

Route::get('/operator', function () {
    return view('operator');
});

Route::post('/update-score', [ScoreController::class, 'updateScore']);
Route::get('/get-scores', [ScoreController::class, 'getScores']);

Route::post('/log-score', [ScoreController::class, 'logScore']);


Route::post('/score/update', function (Request $request) {
    $validated = $request->validate([
        'sport'  => 'required|string',
        'teamA'  => 'required|string',
        'teamB'  => 'required|string',
        'scoreA' => 'required|integer',
        'scoreB' => 'required|integer',
        'set'    => 'nullable|integer',
    ]);

    // Simpan ke database
    $log = ScoreLog::create($validated);

    return response()->json(['message' => 'Score log saved!', 'log' => $log], 201);
});

