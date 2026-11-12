<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_produksi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('area');
            $table->string('mesin_peralatan');
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_produksi');
    }
};
