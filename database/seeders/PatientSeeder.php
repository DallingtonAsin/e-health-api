<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\UserType;
use Illuminate\Database\Seeder;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Hash;

class PatientSeeder extends Seeder
{

    /**
     * The current Faker instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Create a new seeder instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->faker = $this->withFaker();
    }

    /**
     * Get a new Faker instance.
     *
     * @return \Faker\Generator
     */
    protected function withFaker()
    {
        return Container::getInstance()->make(Generator::class);
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_type_id = UserType::where('name', 'like', "%patient%")->first()->id;
        $password = '12345678';

        Patient::create([
            "user_type_id" => $user_type_id,
            "first_name" => $this->faker->firstName(),
            "last_name" => $this->faker->lastName(),
            "country_code" => '+256',
            "phone_number" => "774014727",
            "email" => $this->faker->email,
            "address" => $this->faker->streetAddress,
            "gender" => $this->faker->randomElement(["Male", "Female"]),
            "dob" => $this->faker->dateTimeBetween('-65 years', '-18 years')->format('Y-m-d'),
            'password' => Hash::make($password),
            'profile_status' => 1,
            'is_registered' => 1,
            'is_verified' => 1
        ]);

        Patient::create([
            "user_type_id" => $user_type_id,
            "first_name" => $this->faker->firstName(),
            "last_name" => $this->faker->lastName(),
            "country_code" => '+256',
            "phone_number" => "770014727",
            "email" => $this->faker->email,
            "address" => $this->faker->streetAddress,
            "gender" => $this->faker->randomElement(["Male", "Female"]),
            "dob" => $this->faker->dateTimeBetween('-65 years', '-18 years')->format('Y-m-d'),
            'password' => Hash::make($password),
            'profile_status' => 1,
            'is_registered' => 1,
            'is_verified' => 1
        ]);

        Patient::create([
            "user_type_id" => $user_type_id,
            "first_name" => $this->faker->firstName(),
            "last_name" => $this->faker->lastName(),
            "country_code" => '+256',
            "phone_number" => "779914727",
            "email" => $this->faker->email,
            "address" => $this->faker->streetAddress,
            "gender" => $this->faker->randomElement(["Male", "Female"]),
            "dob" => $this->faker->dateTimeBetween('-65 years', '-18 years')->format('Y-m-d'),
            'password' => Hash::make($password),
            'profile_status' => 1,
            'is_registered' => 1,
            'is_verified' => 1
        ]);
    }
}
