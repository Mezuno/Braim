<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
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
                'latitude' => rand(-9000,9000) / 100,
                'longitude' => rand(-18000,18000) / 100,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ];
        }

        foreach (array_chunk($data, 1000) as $chunk) {
            Location::insert($chunk);
        }
    }
}
