<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        User::insert([
//            'firstName' => 'Mezuno',
//            'lastName' => 'Mekishido',
//            'email' => 'mekishido@gmail.com',
//            'password' => Hash::make('123654789gG'),
//        ]);

        $data = [];
        $recordCount = 4;

        for ($i = 0; $i < $recordCount; $i++) {
            $data[] = [
                'firstName' => fake()->firstName,
                'lastName' => fake()->lastName,
                'email' => rand(1, 1000).fake()->email, //rand is salt
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(20),
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ];
        }

        foreach (array_chunk($data, 1000) as $chunk) {
            User::insert($chunk);
        }
    }
}
