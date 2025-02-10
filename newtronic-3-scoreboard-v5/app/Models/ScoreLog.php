<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'sport',
        'team_a',
        'team_b',
        'score_a',
        'score_b',
        'additional_info',
    ];
}
