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
            "first_name" => "Grace",
            "last_name" => "Kaisa",
            "title" => "Dr.",
            "country_code" => '+256',
            "phone_number" => "774014727",
            "email" => $this->faker->email,
            "qualification" => "MAM",
            "profession" => $this->faker->randomElement(['Dentist', 'Nutrionist', 'Child life specialist', 'Dietitian', 'Orthoptist', 'Nurse', 'Physical therapist', 'Surgical first assistant', 'Phlebotomy technician', 'Medical physicist']),
            "languages" => serialize(['English', 'Runyankore']),
            "experience" => "5 Yrs",
            "image" => "https://pngimg.com/uploads/doctor/doctor_PNG15957.png",
            "specialty_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Moses",
            "last_name" => "MUkasa",
            "title" => "Dr.",
            "country_code" => '+256',
            "phone_number" => "704709074",
            "email" => $this->faker->email,
            "qualification" => "MBBS, DNB",
            "profession" => $this->faker->randomElement(['Dentist', 'Nutrionist', 'Child life specialist', 'Dietitian', 'Orthoptist', 'Nurse', 'Physical therapist', 'Surgical first assistant', 'Phlebotomy technician', 'Medical physicist']),
            "languages" => serialize(['English', 'Luganda']),
            "experience" => "3 Yrs",
            "image" => "https://thumbs.dreamstime.com/b/portrait-positive-black-doctor-holding-medical-chart-male-over-white-background-178499631.jpg",
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
            "image" => "https://www.shape.com/thmb/3BaNRJiYmLa4HCkvORgFpj7c1Xo=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/black-female-doctor-6d6a6c2ec3ae48ceaeeae61f78b7038e.jpg",
            "specialty_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
        ]);


        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Herman",
            "last_name" => "Asiko",
            "title" => "Dr.",
            "country_code" => '+256',
            "phone_number" => "701595279",
            "email" => $this->faker->email,
            "qualification" => "BMBS",
            "profession" => $this->faker->randomElement(['Dentist', 'Nutrionist', 'Child life specialist', 'Dietitian', 'Orthoptist', 'Nurse', 'Physical therapist', 'Surgical first assistant', 'Phlebotomy technician', 'Medical physicist']),
            "languages" => serialize(['Luganda', 'Runyankore']),
            "experience" => "8 Yrs",
            "image" => "https://familydoctor.org/wp-content/uploads/2018/02/41808433_l.jpg",
            "specialty_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Moses",
            "last_name" => "Asiimwe",
            "title" => "Dr.",
            "country_code" => '+256',
            "phone_number" => "701477499",
            "email" => $this->faker->email,
            "qualification" => "BM",
            "profession" => $this->faker->randomElement(['Dentist', 'Nutrionist', 'Child life specialist', 'Dietitian', 'Orthoptist', 'Nurse', 'Physical therapist', 'Surgical first assistant', 'Phlebotomy technician', 'Medical physicist']),
            "languages" => serialize(['English', 'Runyankore']),
            "experience" => "12 Yrs",
            "image" => "https://thumbs.dreamstime.com/b/smiling-female-doctor-holding-medical-records-lab-coat-her-office-clipboard-looking-camera-56673035.jpg",
            "specialty_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Dallington",
            "last_name" => "Asingwire",
            "title" => "Dr.",
            "country_code" => '+256',
            "phone_number" => "774014727",
            "email" => $this->faker->email,
            "qualification" => "DRCOG",
            "profession" => $this->faker->randomElement(['Dentist', 'Nutrionist', 'Child life specialist', 'Dietitian', 'Orthoptist', 'Nurse', 'Physical therapist', 'Surgical first assistant', 'Phlebotomy technician', 'Medical physicist']),
            "languages" => serialize(['English', 'Luganda', 'Runyankore']),
            "experience" => "15 Yrs",
            "image" => "https://t4.ftcdn.net/jpg/03/16/76/11/360_F_316761139_yVmLRT0AVwpZwOTgpmfrdIKrtFfg0bop.jpg",
            "specialty_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
        ]);


        // another list

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Annet",
            "last_name" => "Ninsiima",
            "title" => "Dr.",
            "country_code" => '+256',
            "phone_number" => "774014721",
            "email" => $this->faker->email,
            "qualification" => "DFFP",
            "profession" => $this->faker->randomElement(['Dentist', 'Nutrionist', 'Child life specialist', 'Dietitian', 'Orthoptist', 'Nurse', 'Physical therapist', 'Surgical first assistant', 'Phlebotomy technician', 'Medical physicist']),
            "languages" => serialize(['Runyankore']),
            "experience" => "7 Yrs",
            "image" => "https://thumbs.dreamstime.com/b/portrait-positive-black-doctor-holding-medical-chart-male-over-white-background-178499631.jpg",
            "specialty_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Fred",
            "last_name" => "Mugisha",
            "title" => "Dr.",
            "country_code" => '+256',
            "phone_number" => "704709073",
            "email" => $this->faker->email,
            "qualification" => "CME",
            "profession" => $this->faker->randomElement(['Dentist', 'Nutrionist', 'Child life specialist', 'Dietitian', 'Orthoptist', 'Nurse', 'Physical therapist', 'Surgical first assistant', 'Phlebotomy technician', 'Medical physicist']),
            "languages" => serialize(['English', 'Runyankore', 'Luganda']),
            "experience" => "13 Yrs",
            "image" => "https://st.depositphotos.com/1770836/1357/i/950/depositphotos_13576597-stock-photo-female-doctor-or-nurse.jpg",
            "specialty_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Richard",
            "last_name" => "Bimanya",
            "title" => "Dr.",
            "country_code" => '+256',
            "phone_number" => "788246769",
            "email" => $this->faker->email,
            "qualification" => "BmedSci",
            "profession" => $this->faker->randomElement(['Dentist', 'Nutrionist', 'Child life specialist', 'Dietitian', 'Orthoptist', 'Nurse', 'Physical therapist', 'Surgical first assistant', 'Phlebotomy technician', 'Medical physicist']),
            "languages" => serialize(['English', 'Luganda', 'Runyankore']),
            "experience" => "4.5 Yrs",
            "image" => "https://static2.bigstockphoto.com/4/7/3/large1500/374246794.jpg",
            "specialty_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
        ]);


        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "John",
            "last_name" => "Matsiko",
            "title" => "Dr.",
            "country_code" => '+256',
            "phone_number" => "791595279",
            "email" => $this->faker->email,
            "qualification" => "MBChB",
            "profession" => $this->faker->randomElement(['Dentist', 'Nutrionist', 'Child life specialist', 'Dietitian', 'Orthoptist', 'Nurse', 'Physical therapist', 'Surgical first assistant', 'Phlebotomy technician', 'Medical physicist']),
            "languages" => serialize(['Luganda', 'Runyankore']),
            "experience" => "8 Yrs",
            "image" => "https://www.seekpng.com/png/full/13-132502_alligator-black-male-doctor-png.png",
            "specialty_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Alfred",
            "last_name" => "Kalungi",
            "title" => "Dr.",
            "country_code" => '+256',
            "phone_number" => "700477422",
            "email" => $this->faker->email,
            "qualification" => "MRCGP",
            "profession" => $this->faker->randomElement(['Dentist', 'Nutrionist', 'Child life specialist', 'Dietitian', 'Orthoptist', 'Nurse', 'Physical therapist', 'Surgical first assistant', 'Phlebotomy technician', 'Medical physicist']),
            "languages" => serialize(['English', 'Runyankore']),
            "experience" => "12 Yrs",
            "image" => "https://pngimg.com/uploads/doctor/doctor_PNG15957.png",
            "specialty_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Isaac",
            "last_name" => "Newton",
            "title" => "Dr.",
            "country_code" => '+256',
            "phone_number" => "774014733",
            "email" => $this->faker->email,
            "qualification" => "DRSH",
            "profession" => $this->faker->randomElement(['Dentist', 'Nutrionist', 'Child life specialist', 'Dietitian', 'Orthoptist', 'Nurse', 'Physical therapist', 'Surgical first assistant', 'Phlebotomy technician', 'Medical physicist']),
            "languages" => serialize(['English', 'Luganda', 'Runyankore']),
            "experience" => "15 Yrs",
            "image" => "https://i.pinimg.com/originals/5b/a1/a3/5ba1a398ac0aa7fe01480166fd2b818f.png",
            "specialty_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
        ]);

    }
}
