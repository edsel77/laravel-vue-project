<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        for($i = 0; $i < 50; $i++) {
            $username = $faker->userName;
            App\User::create([
                'username' => $username,
                'firstname' => $faker->firstName,
                'lastname' => $faker->lastName,
                'password' => Hash::make($username),
                'address' => $faker->address,
                'postcode' => $faker->postcode,
                'contact' => $faker->e164PhoneNumber,
                'email' => $faker->email,
                'role' => rand(1, 2)
            ]);
        }
    }
}
