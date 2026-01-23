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
        Schema::create('planes', function (Blueprint $table) {
    $table->id();
    $table->string('icao24')->unique();
    $table->string('callsign')->nullable();
    $table->string('origin_country')->nullable();
    $table->decimal('longitude', 10, 7)->nullable();
    $table->decimal('latitude', 10, 7)->nullable();
    $table->integer('baro_altitude')->nullable();
    $table->integer('velocity')->nullable();
    $table->integer('heading')->nullable();
    $table->boolean('on_ground')->default(false);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planes');
    }
};
