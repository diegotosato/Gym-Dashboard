<?php

namespace Database\Seeders;

use App\Models\Client;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker)
    {
        Client::create([
            'first_name' => 'Diego',
            'last_name' => 'Tosato',
            'slug' => 'diego-tosato',
            'gender' => 'male',
            'email' => 'tosato1998@gmail.com',
            'phone' => '3482965020',
            'date_of_birth' => '1998-09-28',
            'address' => 'Largo Amerigo Vespucci, 26',
            'tax_code' => 'TSTDGI98P28L407G',
            'role_id' => 3,
        ]);

        $usedSlugs = [];

        for ($i = 0; $i < 35; $i++) {
            $name = $faker->firstName();
            $surname = $faker->lastName();

            $slug = Str::slug($name . '-' . $surname);
            $suffix = 2;
            while (in_array($slug, $usedSlugs)) {
                $slug = Str::slug($name . '-' . $surname . '-' . $suffix);
                $suffix++;
            }
            $usedSlugs[] = $slug;

            $genders = ['male', 'female'];
            $rnd_key = array_rand($genders);

            Client::create([
                'first_name' => $name,
                'last_name' => $surname,
                'slug' => $slug,
                'gender' => $genders[$rnd_key],
                'email' => $faker->unique()->safeEmail(),
                'phone' => $faker->unique()->numerify('3#########'),
                'date_of_birth' => $faker->dateTimeBetween('-65 years', '-16 years'),
                'address' => $faker->address(),
                'tax_code' => $faker->unique()->regexify('[A-Z]{6}[0-9]{2}[A-Z]{1}[0-9]{2}[A-Z]{1}[0-9]{3}[A-Z]{1}'),
                'role_id' => 3,
            ]);
        }
    }
}
