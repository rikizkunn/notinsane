<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checksheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_produksi_id')->constrained('data_produksi')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_pemeriksaan');
            $table->text('hasil_pemeriksaan');
            $table->text('catatan')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checksheets');
    }
};
