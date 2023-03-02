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
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->json('animalTypes');
            $table->float('weight');
            $table->float('length');
            $table->float('height');
            $table->string('gender');
            $table->string('lifeStatus');
            $table->dateTime('chippingDateTime');
            $table->integer('chipperId')->unique();
            $table->unsignedBigInteger('chippingLocationId');
            $table->json('visitedLocations');
            $table->dateTime('deathDateTime')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
