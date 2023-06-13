<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ICDCode;

class Icd10CodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ICDCode::create(['code' => 'A00-B99', 'description' =>  'Certain infectious and parasitic diseases']);
        ICDCode::create(['code' => 'C00-D49', 'description' =>  'Neoplasms']);
        ICDCode::create(['code' => 'D50-D89', 'description' =>  'Diseases of the blood and blood-forming organs']);
        ICDCode::create(['code' => 'E00-E89', 'description' =>  'Endocrine, nutritional and metabolic diseases']);
        ICDCode::create(['code' => 'F01-F99', 'description' =>  'Mental, Behavioral and Neurodevelopmental disorders']);
        ICDCode::create(['code' => 'G00-G99', 'description' =>  'Diseases of the nervous system']);
        ICDCode::create(['code' => 'H00-H59', 'description' =>  'Diseases of the eye and adnexa']);
        ICDCode::create(['code' => 'H60-H95', 'description' =>  'Diseases of the ear and mastoid process']);
        ICDCode::create(['code' => 'I00-I99', 'description' =>  'Diseases of the circulatory system']);
        ICDCode::create(['code' => 'J00-J99', 'description' =>  'Diseases of the respiratory system']);
        ICDCode::create(['code' => 'K00-K95', 'description' =>  'Diseases of the digestive system']);
        ICDCode::create(['code' => 'L00-L99', 'description' =>  'Diseases of the skin and subcutaneous tissue']);
        ICDCode::create(['code' => 'M00-M99', 'description' =>  'Diseases of the musculoskeletal system and connective tissue']);
        ICDCode::create(['code' => 'N00-N99', 'description' =>  'Diseases of the genitourinary system']);
        ICDCode::create(['code' => 'O00-O9A', 'description' =>  'Pregnancy, childbirth and the puerperium']);
        ICDCode::create(['code' => 'P00-P96', 'description' =>  'Certain conditions originating in the perinatal period']);
        ICDCode::create(['code' => 'Q00-Q99', 'description' =>  'Congenital malformations, deformations and chromosomal abnormalities']);
        ICDCode::create(['code' => 'R00-R99', 'description' =>  'Symptoms, signs and abnormal clinical and laboratory findings, not elsewhere classified']);
        ICDCode::create(['code' => 'S00-T88', 'description' =>  'Injury, poisoning and certain other consequences of external causes']);
        ICDCode::create(['code' => 'U00-U85', 'description' =>  'Codes for special purposes']);
        ICDCode::create(['code' => 'V00-Y99', 'description' =>  'External causes of morbidity']);
        ICDCode::create(['code' => 'Z00-Z99', 'description' =>  'Factors influencing health status and contact with health services']);
    }
}
