<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('triage_locations', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokasi');
            $table->string('kategori')->default('Rumah Sakit'); // e.g., 'Rumah Sakit', 'Ambulans', 'Kejadian Triage'
            $table->text('alamat')->nullable();
            
            // Regional Administrative Filters
            $table->unsignedBigInteger('province_id');
            $table->unsignedBigInteger('regency_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('village_id')->nullable();

            // Map Coordinates
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            
            // Operational Capacity & Status
            $table->integer('kapasitas_bed')->default(0);
            $table->string('status')->default('Tersedia'); // e.g., 'Tersedia', 'Penuh', 'Darurat'

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('triage_locations');
    }
};