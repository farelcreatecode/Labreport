<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('kode_laporan', 30)->unique();
            $table->string('nama_pelapor', 150);
            $table->string('kontak_pelapor', 100);
            $table->foreignId('laboratory_id')->constrained('laboratories')->cascadeOnDelete();
            $table->foreignId('facility_id')->nullable()->constrained('facilities')->nullOnDelete();
            $table->string('nomor_pc', 50)->nullable();
            $table->text('deskripsi_masalah');
            $table->enum('status', ['pending', 'diproses', 'selesai', 'ditolak'])->default('pending');
            $table->foreignId('technician_id')->nullable()->constrained('technicians')->nullOnDelete();
            $table->text('tanggapan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
