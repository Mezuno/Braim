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
        Schema::create('visited_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animalId')
                ->references('id')
                ->on('animals');
            $table->dateTime('dateTimeOfVisitLocationPoint');
            $table->foreignId('locationPointId')
                ->references('id')
                ->on('locations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visited_locations');
    }
};
