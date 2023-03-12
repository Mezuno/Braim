<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\AnimalType;
use App\Models\Location;
use App\Models\User;
use Faker\Core\DateTime;
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
        $recordCount = 20;
        $locations = Location::get('id')->pluck('id')->toArray();
        $animalTypes = AnimalType::get('id')->pluck('id')->toArray();

        for ($i = 0; $i < $recordCount; $i++) {

            $visitedLocationsArray = [];
            $visitedLocationsArrayCount = rand(1,6);
            for ($j = 0; $j < $visitedLocationsArrayCount; $j++) {
                $visitedLocation = $locations[array_rand($locations, 1)];
                if ($j >= 1) {
                    while ($visitedLocation == $visitedLocationsArray[$j-1]) {
                        $visitedLocation = $locations[array_rand($locations, 1)];
                    }
                }
                array_push($visitedLocationsArray, $visitedLocation);
            }

            $animalTypesArray = [];
            $animalTypesArrayCount = rand(1,3);
            for ($j = 0; $j < $animalTypesArrayCount; $j++) {
                $animalType = $animalTypes[array_rand($animalTypes, 1)];
                while (in_array($animalType, $animalTypesArray)) {
                    $animalType = $animalTypes[array_rand($animalTypes, 1)];
                }
                array_push($animalTypesArray, $animalType);
            }

            $lifeStatus = fake()->randomElement(['ALIVE', 'DEAD']);
            $deathDateTime = null;
            if ($lifeStatus == 'DEAD') {
                $deathDateTime = fake()->dateTime;
            }

            $data[] = [
                'animalTypes' => json_encode($animalTypesArray, true),
                'weight' => rand(1,5),
                'length' => rand(1,5),
                'height' => rand(1,5),
                'gender' => fake()->randomElement(['MALE', 'FEMALE', 'OTHER']),
                'lifeStatus' => $lifeStatus,
                'chippingDateTime' => fake()->dateTime,
                'chipperId' => User::get('id')->random()->id,
                'chippingLocationId' => Location::get('id')->random()->id,
                'visitedLocations' => json_encode($visitedLocationsArray, true),
                'deathDateTime' => $deathDateTime,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ];
        }

        foreach (array_chunk($data, 1000) as $chunk) {
            Animal::insert($chunk);
        }
    }
}
