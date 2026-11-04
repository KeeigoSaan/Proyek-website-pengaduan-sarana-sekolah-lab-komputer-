<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aduan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_aduan')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('perangkat_id')->constrained('perangkat')->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('kategori_kerusakan')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('foto_bukti')->nullable();
            $table->enum('status', ['PENDING', 'IN_PROGRESS', 'RESOLVED', 'REJECTED'])->default('PENDING');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aduan');
    }
};