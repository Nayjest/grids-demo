<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $faker = Faker\Factory::create();

        for ($i = 1; $i <= 2000; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => uniqid(),
                'birthday' => $faker->date(),
                'phone_number' => $faker->phoneNumber,
                'country' => $faker->country,
                'company' => $faker->company,
                'posts_count' => rand(0, 50),
                'comments_count' => rand(0, 1000),
            ]);
        }
    }
}
