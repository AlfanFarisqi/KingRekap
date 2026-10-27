<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prediksi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_prediksi');
            $table->integer('rentang_minggu')->default(4);
            $table->date('periode_mulai')->nullable();
            $table->date('periode_selesai')->nullable();
            $table->integer('total_prediksi_cup');
            $table->string('metode')->default('Moving Average');
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prediksi');
    }
};
