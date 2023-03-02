<?php

namespace Database\Seeders;

use App\Models\Animal;
use Illuminate\Database\Seeder;

class AnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $recordCount = 10;

        for ($i = 0; $i < $recordCount; $i++) {
            $data[] = [
                'animalTypes' => '[1, 3, 7]',
                'weight' => rand(0,1000),
                'length' => rand(0,1000),
                'height' => rand(0,1000),
                'gender' => fake()->randomElement(['MALE', 'FEMALE', 'OTHER']),
                'lifeStatus' => fake()->randomElement(['ALIVE', 'DEAD']),
                'chippingDateTime' => fake()->dateTime,
                'chipperId' => rand(1,100000),
                'chippingLocationId' => rand(1,100000),
                'visitedLocations' => '[2, 4, 8]',
                'deathDateTime' => fake()->randomElement([fake()->dateTime, NULL]),
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ];
        }

        foreach (array_chunk($data, 1000) as $chunk) {
            Animal::insert($chunk);
        }
    }
}
