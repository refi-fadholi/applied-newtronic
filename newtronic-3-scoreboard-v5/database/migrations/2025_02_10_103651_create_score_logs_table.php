<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoreLogsTable extends Migration
{
    public function up()
    {
        Schema::create('score_logs', function (Blueprint $table) {
            $table->id();
            $table->string('sport');
            $table->string('team_a');
            $table->string('team_b');
            $table->integer('score_a');
            $table->integer('score_b');
            $table->string('additional_info')->nullable();  // Info tentang babak, set, atau timer
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('score_logs');
    }
}
