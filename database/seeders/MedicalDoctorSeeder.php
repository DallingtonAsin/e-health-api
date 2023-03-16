<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicalDoctor;
use Faker\Generator;
use Illuminate\Container\Container;
use App\Models\UserType;


class MedicalDoctorSeeder extends Seeder
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

        $user_type_id = UserType::where('name', 'like', "%doctor%")->first()->id;

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "David",
            "last_name" => "Taremwa",
            "title" => "Dr.",
            "country_code" => '+256',
            "phone_number" => "774014727",
            "email" => $this->faker->email,
            "qualification" => "MAM",
            "profession" => $this->faker->randomElement(['Dentist', 'Nutrionist', 'Child life specialist', 'Dietitian', 'Orthoptist', 'Nurse', 'Physical therapist', 'Surgical first assistant', 'Phlebotomy technician', 'Medical physicist']),
            "languages" => serialize(['English', 'Runyankore']),
            "experience" => "5 Yrs",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Moses",
            "last_name" => "Mukasa",
            "title" => "Dr.",
            "country_code" => '+256',
            "phone_number" => "704709001",
            "email" => $this->faker->email,
            "qualification" => "MBBS, DNB",
            "profession" => $this->faker->randomElement(['Dentist', 'Nutrionist', 'Child life specialist', 'Dietitian', 'Orthoptist', 'Nurse', 'Physical therapist', 'Surgical first assistant', 'Phlebotomy technician', 'Medical physicist']),
            "languages" => serialize(['English', 'Luganda']),
            "experience" => "3 Yrs",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Francis",
            "last_name" => "Agaba",
            "title" => "Dr.",
            "country_code" => '+256',
            "phone_number" => "788246763",
            "email" => $this->faker->email,
            "qualification" => "MAM",
            "profession" => $this->faker->randomElement(['Dentist', 'Physiotherapist', 'Orthopaedic Officer', 'Nutrionist', 'Child life specialist', 'Dietitian', 'Orthoptist', 'Nurse', 'Physical therapist', 'Surgical first assistant', 'Phlebotomy technician', 'Medical physicist']),
            "languages" => serialize(['English', 'Luganda', 'Runyankore']),
            "experience" => "2 Yrs",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
        ]);


        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Herman",
            "last_name" => "Asiko",
            "title" => "Dr.",
            "country_code" => '+256',
            "phone_number" => "701595288",
            "email" => $this->faker->email,
            "qualification" => "BMBS",
            "profession" => $this->faker->randomElement(['Dentist', 'Nutrionist', 'Child life specialist', 'Dietitian', 'Orthoptist', 'Nurse', 'Physical therapist', 'Surgical first assistant', 'Phlebotomy technician', 'Medical physicist']),
            "languages" => serialize(['Luganda', 'Runyankore']),
            "experience" => "8 Yrs",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "John",
            "last_name" => "Asiimwe",
            "title" => "Dr.",
            "country_code" => '+256',
            "phone_number" => "701477494",
            "email" => $this->faker->email,
            "qualification" => "BM",
            "profession" => $this->faker->randomElement(['Dentist', 'Nutrionist', 'Child life specialist', 'Dietitian', 'Orthoptist', 'Nurse', 'Physical therapist', 'Surgical first assistant', 'Phlebotomy technician', 'Medical physicist']),
            "languages" => serialize(['English', 'Runyankore']),
            "experience" => "12 Yrs",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Sarah",
            "last_name" => "Turyomwe",
            "title" => "Dr.",
            "country_code" => '+256',
            "phone_number" => "774311727",
            "email" => $this->faker->email,
            "qualification" => "DRCOG",
            "profession" => $this->faker->randomElement(['Dentist', 'Nutrionist', 'Child life specialist', 'Dietitian', 'Orthoptist', 'Nurse', 'Physical therapist', 'Surgical first assistant', 'Phlebotomy technician', 'Medical physicist']),
            "languages" => serialize(['English', 'Luganda', 'Runyankore']),
            "experience" => "15 Yrs",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Annet",
            "last_name" => "Ninsiima",
            "title" => "Dr.",
            "country_code" => '+256',
            "phone_number" => "774016621",
            "email" => $this->faker->email,
            "qualification" => "DFFP",
            "profession" => $this->faker->randomElement(['Dentist', 'Nutrionist', 'Child life specialist', 'Dietitian', 'Orthoptist', 'Nurse', 'Physical therapist', 'Surgical first assistant', 'Phlebotomy technician', 'Medical physicist']),
            "languages" => serialize(['Runyankore']),
            "experience" => "7 Yrs",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Fred",
            "last_name" => "Mugisha",
            "title" => "Dr.",
            "country_code" => '+256',
            "phone_number" => "705709673",
            "email" => $this->faker->email,
            "qualification" => "CME",
            "profession" => $this->faker->randomElement(['Dentist', 'Nutrionist', 'Child life specialist', 'Dietitian', 'Orthoptist', 'Nurse', 'Physical therapist', 'Surgical first assistant', 'Phlebotomy technician', 'Medical physicist']),
            "languages" => serialize(['English', 'Runyankore', 'Luganda']),
            "experience" => "13 Yrs",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Richard",
            "last_name" => "Bimanya",
            "title" => "Dr.",
            "country_code" => '+256',
            "phone_number" => "788446769",
            "email" => $this->faker->email,
            "qualification" => "BmedSci",
            "profession" => $this->faker->randomElement(['Dentist', 'Nutrionist', 'Child life specialist', 'Dietitian', 'Orthoptist', 'Nurse', 'Physical therapist', 'Surgical first assistant', 'Phlebotomy technician', 'Medical physicist']),
            "languages" => serialize(['English', 'Luganda', 'Runyankore']),
            "experience" => "4.5 Yrs",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
        ]);


        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Peter",
            "last_name" => "Matsiko",
            "title" => "Dr.",
            "country_code" => '+256',
            "phone_number" => "791595273",
            "email" => $this->faker->email,
            "qualification" => "MBChB",
            "profession" => $this->faker->randomElement(['Dentist', 'Nutrionist', 'Child life specialist', 'Dietitian', 'Orthoptist', 'Nurse', 'Physical therapist', 'Surgical first assistant', 'Phlebotomy technician', 'Medical physicist']),
            "languages" => serialize(['Luganda', 'Runyankore']),
            "experience" => "8 Yrs",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Alfred",
            "last_name" => "Kalungi",
            "title" => "Dr.",
            "country_code" => '+256',
            "phone_number" => "700486422",
            "email" => $this->faker->email,
            "qualification" => "MRCGP",
            "profession" => $this->faker->randomElement(['Dentist', 'Nutrionist', 'Child life specialist', 'Dietitian', 'Orthoptist', 'Nurse', 'Physical therapist', 'Surgical first assistant', 'Phlebotomy technician', 'Medical physicist']),
            "languages" => serialize(['English', 'Runyankore']),
            "experience" => "12 Yrs",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Isaac",
            "last_name" => "Newton",
            "title" => "Dr.",
            "country_code" => '+256',
            "phone_number" => "786014733",
            "email" => $this->faker->email,
            "qualification" => "DRSH",
            "profession" => $this->faker->randomElement(['Dentist', 'Nutrionist', 'Child life specialist', 'Dietitian', 'Orthoptist', 'Nurse', 'Physical therapist', 'Surgical first assistant', 'Phlebotomy technician', 'Medical physicist']),
            "languages" => serialize(['English', 'Luganda', 'Runyankore']),
            "experience" => "15 Yrs",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
        ]);

    }
}
