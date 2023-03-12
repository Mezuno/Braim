<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
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
            $table->string('lifeStatus')->default('ALIVE');
            $table->timestamp('chippingDateTime')->default(Carbon::now()->format('Y-m-d H:i:s'));
            $table->foreignId('chipperId')
                ->references('id')
                ->on('users');
            $table->unsignedBigInteger('chippingLocationId');
            $table->json('visitedLocations')->nullable();
            $table->timestamp('deathDateTime')->nullable();
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
