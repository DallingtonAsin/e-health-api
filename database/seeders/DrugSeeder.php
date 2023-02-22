<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Drug;

class DrugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Drug::create([
            'name' => 'Benylin with codeine',
            'description' => 'Benylin Codeine Syrup helps you to clear dry and stubborn coughs, providing relief from persistant coughing',
            'price' => 30000,
            'image' => 'https://www.rockethealth.shop/wp-content/uploads/2019/08/Benylin-with-codeine.jpg'
        ]);

        Drug::create([
            'name' => 'Valupak Vitamin C Effervescent Tablets 1000mg 1 tin',
            'description' => 'AvaCare’s Simple Health Vitamin C contains 20 Orange Flavoured Effervescent Tablets. AvaCare’s Effervescent Vitamin C has been designed so you get a massive boost of well-needed Vitamin C as vitamin C helps support the normal function of your Immune and Nervous Systems, giving your immune system an immediate boost. It can also help reduce the effects of Tiredness and Fatigue',
            'price' => 28000,
            'image' => 'https://www.rockethealth.shop/wp-content/uploads/2020/08/DSC_4597.jpg'
        ]);


        Drug::create([
            'name' => 'Midazolam 7.5mg Dormicum tablets 10’s',
            'description' => 'Midazolam Tablet is a prescription medicine used as a sedative or for conscious sedation before diagnostic or therapeutic procedures. It relieves anxiety and muscle tensions, thus making the person comfortable before investigations or minor surgical or dental procedures.Some common side effects of this medicine include memory impairment, tiredness, depression, and confusion. It may cause dizziness',
            'price' => 4500,
            'image' => 'hhttps://www.rockethealth.shop/wp-content/uploads/2020/12/DORMICUM-3.jpg'
        ]);

        Drug::create([
            'name' => 'Diazepam 10mg Valium Tablets',
            'description' => 'Diazepam tablets is used to treat anxiety, alcohol withdrawal and seizures. it is used to relieve muscle spasms and to provide sedation before medical procedures.',
            'price' => 2500,
            'image' => 'https://www.rockethealth.shop/wp-content/uploads/2020/12/Valium-10MG-1.jpg'
        ]);

        Drug::create([
            'name' => 'COVIDEX (Pack of 5 bottles)',
            'description' => 'Made by Jena Herbals Uganda Limited, Covidex is approved by NDA to be used as a supportive treatment for COVID-19 in Uganda',
            'price' => 65000,
            'image' => 'https://www.rockethealth.shop/wp-content/uploads/2021/06/COVIDEX-On-Sale-In-KampalaA-Pack-of-5-Bottles-3.jpg'
        ]);


        Drug::create([
            'name' => 'Sildenafil 50mg PENEGRA 50 Tablet 4’s',
            'description' => 'Penegra is used to treat erectile dysfunction (impotence) in men. This helps men to get or maintain an erection. It belongs to a group of medicines known as phosphodiesterase type 5 (PDE 5) inhibitors. It works by increasing blood flow to the penis.',
            'price' => 1200,
            'image' => 'https://www.rockethealth.shop/wp-content/uploads/2020/12/PENEGRA-50-1.jpg'
        ]);

        Drug::create([
            'name' => 'Paracetamol 500mg Tablet Panadol Advance 10’s',
            'description' => 'Panadol Advance is used to relieve fever and mild to moderate pain such as muscle ache, headache, toothache, and backache. This medicine should be used with caution in patients with liver diseases due to the increased risk of severe adverse effects.',
            'price' => 3000,
            'image' => 'https://www.rockethealth.shop/wp-content/uploads/2019/10/RocketHealthShop39-6-1.jpg'
        ]);

        Drug::create([
            'name' => 'Paracetamol/Caffeine Tablet Panadol Extra',
            'description' => 'Panadol Extra is used to relieve fever and mild to moderate pain such as muscle ache, headache, toothache, backache, menstrual period pains and cold/flu aches and pains. This medicine should be used with caution in patients with liver diseases due to the increased risk of severe adverse effects.',
            'price' => 300,
            'image' => 'https://www.rockethealth.shop/wp-content/uploads/2019/09/Panado-Extra.jpg'
        ]);




        Drug::create([
            'name' => 'Prednisolone 5mg DAWASOLONETablet 10’s',
            'description' => 'Prednisolone is used for treatment of severe allergic reactions, asthma, rheumatic disorders, and skin and eye disorders. It is a steroid that provides relief by preventing the release of substances that cause inflammation and by suppressing the immune system. The most common side effect of prolonged use of this medicine is reduction in bone density, weight gain, mood changes, stomach upset and behavioural changes.',
            'price' => 100,
            'image' => 'https://www.rockethealth.shop/wp-content/uploads/2020/12/Prednisolone-5mg-DAWASOLONETablet-10s.jpg'
        ]);

        Drug::create([
            'name' => 'Codeine Phosphate 30mg CodeineUK Tablets 7’s',
            'description' => 'Codeine phosphate is used to treat mild to moderatley severe pain',
            'price' => 1500,
            'image' => 'https://www.rockethealth.shop/wp-content/uploads/2020/12/CodeineUK-1.jpg'
        ]);

        Drug::create([
            'name' => 'Alprazolam 0.5mg XANAX Pfizer Tablet 10’s',
            'description' => 'Alprazolam is used to treat anxiety disorders and panic disorders. It belongs to a class of medications called benzodiazepines which act on the brain and nerves (central nervous system) to produce a calming effect',
            'price' => 3000,
            'image' => 'https://www.rockethealth.shop/wp-content/uploads/2020/12/XANAX-Pfizer-0.5MG-2.jpg'
        ]);
    }
}
