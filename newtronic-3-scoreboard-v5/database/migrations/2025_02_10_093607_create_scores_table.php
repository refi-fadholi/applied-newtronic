<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->string('sport');  // Jenis olahraga
            $table->string('teamA');  // Nama Tim A
            $table->string('teamB');  // Nama Tim B
            $table->integer('scoreA'); // Skor Tim A
            $table->integer('scoreB'); // Skor Tim B
            $table->timestamps();      // Waktu update
        });
    }

    public function down(): void {
        Schema::dropIfExists('scores');
    }
};
