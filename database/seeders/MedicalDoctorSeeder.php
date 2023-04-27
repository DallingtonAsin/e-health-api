<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicalDoctor;
use App\Models\MedicalFacility;
use Faker\Generator;
use Illuminate\Container\Container;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;


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
        $password = '12345678';

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "David",
            "last_name" => "Taremwa",
            "country_code" => '+256',
            "phone_number" => "774014727",
            "email" => $this->faker->email,
            "dob" => $this->faker->dateTimeBetween('-65 years', '-18 years')->format('Y-m-d'),
            "gender" => $this->faker->randomElement(["Male", "Female"]),
            "address" => $this->faker->streetAddress,
            "qualification" => "MAM",
            'primary_facility_id' => MedicalFacility::inRandomOrder()->first()->id,
            'training_institute' => 'Makerere',
            'umdp_lincense_id' => $this->faker->regexify('[A-Z]{2}-\d{5}'),
            'bio_summary' => "I'm a cardiologist with over 10 years of experience in the field. I'm currently practicing at ABC Hospital, where I specialize in diagnosing and treating cardiovascular diseases. I earned my medical degree from Stanford University School of Medicine and completed my residency and fellowship training at the Mayo Clinic. I'm board-certified in cardiology and have been recognized for my contributions to the field, including several publications in top-tier medical journals.",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
            'password' => Hash::make($password),
            'profile_status' => 1,
            'is_registered' => 1,
        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Moses",
            "last_name" => "Mukasa",
            "country_code" => '+256',
            "phone_number" => "704709001",
            "email" => $this->faker->email,
            "dob" => $this->faker->dateTimeBetween('-65 years', '-18 years')->format('Y-m-d'),
            "gender" => $this->faker->randomElement(["Male", "Female"]),
            "address" => $this->faker->streetAddress,
            "qualification" => "MBBS, DNB",
            'primary_facility_id' => MedicalFacility::inRandomOrder()->first()->id,
            'training_institute' => 'Makerere',
            'umdp_lincense_id' => $this->faker->regexify('[A-Z]{2}-\d{5}'),
            'bio_summary' => "I'm a cardiologist with over 10 years of experience in the field. I'm currently practicing at ABC Hospital, where I specialize in diagnosing and treating cardiovascular diseases. I earned my medical degree from Stanford University School of Medicine and completed my residency and fellowship training at the Mayo Clinic. I'm board-certified in cardiology and have been recognized for my contributions to the field, including several publications in top-tier medical journals.",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
            'password' => Hash::make($password),
            'profile_status' => 1,
            'is_registered' => 1,
        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Francis",
            "last_name" => "Agaba",
            "country_code" => '+256',
            "phone_number" => "788246763",
            "email" => $this->faker->email,
            "dob" => $this->faker->dateTimeBetween('-65 years', '-18 years')->format('Y-m-d'),
            "gender" => $this->faker->randomElement(["Male", "Female"]),
            "address" => $this->faker->streetAddress,
            "qualification" => "MAM",
            'primary_facility_id' => MedicalFacility::inRandomOrder()->first()->id,
            'training_institute' => 'Makerere',
            'umdp_lincense_id' => $this->faker->regexify('[A-Z]{2}-\d{5}'),
            'bio_summary' => "I'm a cardiologist with over 10 years of experience in the field. I'm currently practicing at ABC Hospital, where I specialize in diagnosing and treating cardiovascular diseases. I earned my medical degree from Stanford University School of Medicine and completed my residency and fellowship training at the Mayo Clinic. I'm board-certified in cardiology and have been recognized for my contributions to the field, including several publications in top-tier medical journals.",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
            'password' => Hash::make($password),
            'profile_status' => 1,
            'is_registered' => 1,

        ]);


        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Herman",
            "last_name" => "Asiko",
            "country_code" => '+256',
            "phone_number" => "701595288",
            "email" => $this->faker->email,
            "dob" => $this->faker->dateTimeBetween('-65 years', '-18 years')->format('Y-m-d'),
            "gender" => $this->faker->randomElement(["Male", "Female"]),
            "address" => $this->faker->streetAddress,
            "qualification" => "BMBS",
            'primary_facility_id' => MedicalFacility::inRandomOrder()->first()->id,
            'training_institute' => 'Makerere',
            'umdp_lincense_id' => $this->faker->regexify('[A-Z]{2}-\d{5}'),
            'bio_summary' => "I'm a cardiologist with over 10 years of experience in the field. I'm currently practicing at ABC Hospital, where I specialize in diagnosing and treating cardiovascular diseases. I earned my medical degree from Stanford University School of Medicine and completed my residency and fellowship training at the Mayo Clinic. I'm board-certified in cardiology and have been recognized for my contributions to the field, including several publications in top-tier medical journals.",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
            'password' => Hash::make($password),
            'profile_status' => 1,
            'is_registered' => 1,

        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "John",
            "last_name" => "Asiimwe",
            "country_code" => '+256',
            "phone_number" => "701477494",
            "email" => $this->faker->email,
            "dob" => $this->faker->dateTimeBetween('-65 years', '-18 years')->format('Y-m-d'),
            "gender" => $this->faker->randomElement(["Male", "Female"]),
            "address" => $this->faker->streetAddress,
            "qualification" => "BM",
            'primary_facility_id' => MedicalFacility::inRandomOrder()->first()->id,
            'training_institute' => 'Makerere',
            'umdp_lincense_id' => $this->faker->regexify('[A-Z]{2}-\d{5}'),
            'bio_summary' => "I'm a cardiologist with over 10 years of experience in the field. I'm currently practicing at ABC Hospital, where I specialize in diagnosing and treating cardiovascular diseases. I earned my medical degree from Stanford University School of Medicine and completed my residency and fellowship training at the Mayo Clinic. I'm board-certified in cardiology and have been recognized for my contributions to the field, including several publications in top-tier medical journals.",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
            'password' => Hash::make($password),
            'profile_status' => 1,
            'is_registered' => 1,
        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Sarah",
            "last_name" => "Turyomwe",
            "country_code" => '+256',
            "phone_number" => "774311727",
            "email" => $this->faker->email,
            "dob" => $this->faker->dateTimeBetween('-65 years', '-18 years')->format('Y-m-d'),
            "gender" => $this->faker->randomElement(["Male", "Female"]),
            "address" => $this->faker->streetAddress,
            "qualification" => "DRCOG",
            'primary_facility_id' => MedicalFacility::inRandomOrder()->first()->id,
            'training_institute' => 'Makerere',
            'umdp_lincense_id' => $this->faker->regexify('[A-Z]{2}-\d{5}'),
            'bio_summary' => "I'm a cardiologist with over 10 years of experience in the field. I'm currently practicing at ABC Hospital, where I specialize in diagnosing and treating cardiovascular diseases. I earned my medical degree from Stanford University School of Medicine and completed my residency and fellowship training at the Mayo Clinic. I'm board-certified in cardiology and have been recognized for my contributions to the field, including several publications in top-tier medical journals.",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
            'password' => Hash::make($password),
            'profile_status' => 1,
            'is_registered' => 1,

        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Annet",
            "last_name" => "Ninsiima",
            "country_code" => '+256',
            "phone_number" => "774016621",
            "email" => $this->faker->email,
            "dob" => $this->faker->dateTimeBetween('-65 years', '-18 years')->format('Y-m-d'),
            "gender" => $this->faker->randomElement(["Male", "Female"]),
            "address" => $this->faker->streetAddress,
            "qualification" => "DFFP",
            'primary_facility_id' => MedicalFacility::inRandomOrder()->first()->id,
            'training_institute' => 'Makerere',
            'umdp_lincense_id' => $this->faker->regexify('[A-Z]{2}-\d{5}'),
            'bio_summary' => "I'm a cardiologist with over 10 years of experience in the field. I'm currently practicing at ABC Hospital, where I specialize in diagnosing and treating cardiovascular diseases. I earned my medical degree from Stanford University School of Medicine and completed my residency and fellowship training at the Mayo Clinic. I'm board-certified in cardiology and have been recognized for my contributions to the field, including several publications in top-tier medical journals.",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
            'password' => Hash::make($password),
            'profile_status' => 1,
            'is_registered' => 1,
        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Fred",
            "last_name" => "Mugisha",
            "country_code" => '+256',
            "phone_number" => "705709673",
            "email" => $this->faker->email,
            "dob" => $this->faker->dateTimeBetween('-65 years', '-18 years')->format('Y-m-d'),
            "gender" => $this->faker->randomElement(["Male", "Female"]),
            "qualification" => "CME",
            'primary_facility_id' => MedicalFacility::inRandomOrder()->first()->id,
            'training_institute' => 'Makerere',
            'umdp_lincense_id' => $this->faker->regexify('[A-Z]{2}-\d{5}'),
            'bio_summary' => "I'm a cardiologist with over 10 years of experience in the field. I'm currently practicing at ABC Hospital, where I specialize in diagnosing and treating cardiovascular diseases. I earned my medical degree from Stanford University School of Medicine and completed my residency and fellowship training at the Mayo Clinic. I'm board-certified in cardiology and have been recognized for my contributions to the field, including several publications in top-tier medical journals.",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
            'password' => Hash::make($password),
            'profile_status' => 1,
            'is_registered' => 1,
        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Richard",
            "last_name" => "Bimanya",
            "country_code" => '+256',
            "phone_number" => "788446769",
            "email" => $this->faker->email,
            "dob" => $this->faker->dateTimeBetween('-65 years', '-18 years')->format('Y-m-d'),
            "gender" => $this->faker->randomElement(["Male", "Female"]),
            "address" => $this->faker->streetAddress,
            "qualification" => "BmedSci",
            'primary_facility_id' => MedicalFacility::inRandomOrder()->first()->id,
            'training_institute' => 'Makerere',
            'umdp_lincense_id' => $this->faker->regexify('[A-Z]{2}-\d{5}'),
            'bio_summary' => "I'm a cardiologist with over 10 years of experience in the field. I'm currently practicing at ABC Hospital, where I specialize in diagnosing and treating cardiovascular diseases. I earned my medical degree from Stanford University School of Medicine and completed my residency and fellowship training at the Mayo Clinic. I'm board-certified in cardiology and have been recognized for my contributions to the field, including several publications in top-tier medical journals.",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
            'password' => Hash::make($password),
            'profile_status' => 1,
            'is_registered' => 1,
        ]);


        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Peter",
            "last_name" => "Matsiko",
            "country_code" => '+256',
            "phone_number" => "791595273",
            "email" => $this->faker->email,
            "dob" => $this->faker->dateTimeBetween('-65 years', '-18 years')->format('Y-m-d'),
            "gender" => $this->faker->randomElement(["Male", "Female"]),
            "address" => $this->faker->streetAddress,
            "qualification" => "MBChB",
            'primary_facility_id' => MedicalFacility::inRandomOrder()->first()->id,
            'training_institute' => 'Makerere',
            'umdp_lincense_id' => $this->faker->regexify('[A-Z]{2}-\d{5}'),
            'bio_summary' => "I'm a cardiologist with over 10 years of experience in the field. I'm currently practicing at ABC Hospital, where I specialize in diagnosing and treating cardiovascular diseases. I earned my medical degree from Stanford University School of Medicine and completed my residency and fellowship training at the Mayo Clinic. I'm board-certified in cardiology and have been recognized for my contributions to the field, including several publications in top-tier medical journals.",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
            'password' => Hash::make($password),
            'profile_status' => 1,
            'is_registered' => 1,
        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Alfred",
            "last_name" => "Kalungi",
            "country_code" => '+256',
            "phone_number" => "700486422",
            "email" => $this->faker->email,
            "dob" => $this->faker->dateTimeBetween('-65 years', '-18 years')->format('Y-m-d'),
            "gender" => $this->faker->randomElement(["Male", "Female"]),
            "address" => $this->faker->streetAddress,
            "qualification" => "MRCGP",
            'primary_facility_id' => MedicalFacility::inRandomOrder()->first()->id,
            'training_institute' => 'Makerere',
            'umdp_lincense_id' => $this->faker->regexify('[A-Z]{2}-\d{5}'),
            'bio_summary' => "I'm a cardiologist with over 10 years of experience in the field. I'm currently practicing at ABC Hospital, where I specialize in diagnosing and treating cardiovascular diseases. I earned my medical degree from Stanford University School of Medicine and completed my residency and fellowship training at the Mayo Clinic. I'm board-certified in cardiology and have been recognized for my contributions to the field, including several publications in top-tier medical journals.",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
            'password' => Hash::make($password),
            'profile_status' => 1,
            'is_registered' => 1,
        ]);

        MedicalDoctor::create([
            "user_type_id" => $user_type_id,
            "first_name" => "Isaac",
            "last_name" => "Newton",
            "country_code" => '+256',
            "phone_number" => "786014733",
            "email" => $this->faker->email,
            "dob" => $this->faker->dateTimeBetween('-65 years', '-18 years')->format('Y-m-d'),
            "gender" => $this->faker->randomElement(["Male", "Female"]),
            "address" => $this->faker->streetAddress,
            "qualification" => "DRSH",
            'primary_facility_id' => MedicalFacility::inRandomOrder()->first()->id,
            'training_institute' => 'Makerere',
            'umdp_lincense_id' => $this->faker->regexify('[A-Z]{2}-\d{5}'),
            'bio_summary' => "I'm a cardiologist with over 10 years of experience in the field. I'm currently practicing at ABC Hospital, where I specialize in diagnosing and treating cardiovascular diseases. I earned my medical degree from Stanford University School of Medicine and completed my residency and fellowship training at the Mayo Clinic. I'm board-certified in cardiology and have been recognized for my contributions to the field, including several publications in top-tier medical journals.",
            "image" => null,
            "specialty_id" => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]),
            "service_fee" => $this->faker->numberBetween(10000, 95000),
            'password' => Hash::make($password),
            'profile_status' => 1,
            'is_registered' => 1,
        ]);
    }
}
