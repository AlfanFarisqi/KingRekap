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
        Schema::create('detail_prediksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prediksi_id')->constrained('prediksi')->cascadeOnDelete();
            $table->string('label_periode');
            $table->integer('urutan')->default(1);
            $table->string('tipe')->default('historis');
            $table->integer('jumlah_cup');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_prediksi');
    }
};
