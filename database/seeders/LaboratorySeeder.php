<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LaboratorySeeder extends Seeder
{
    public function run(): void
    {
        // ---------------------------------------------------------
        // 1. BLOOD COMPLETE PICTURE (Page 3)
        // ---------------------------------------------------------
        $cbcId = $this->getOrCreateTestType('BLOOD COMPLETE PICTURE', 'Hematology', 'Blood');

        $cbcParams = [
            ['HgB', null, 'gm/dl', '11.0 - 16.0', 11.0, 16.0],
            ['HCT', null, '%', '37.0 - 54.0', 37.0, 54.0],
            ['MCV', null, 'f L', '80.0 - 100.0', 80.0, 100.0],
            ['MCH', null, 'Pg', '27.0 - 34.0', 27.0, 34.0],
            ['MCHC', null, 'g/L', '320 - 360', 320, 360],
            ['RDW-CV', null, null, '0.110 - 0.160', 0.110, 0.160],
            ['RDW-SD', null, '10^9/L', '35.0 - 56.0', 35.0, 56.0],
            ['PLATELETS', null, '10^9/L', '150 - 450', 150, 450],
            ['MPV', null, 'f L', '7.0 - 11.0', 7.0, 11.0],
            ['PDW', null, null, '9.0 - 17.0', 9.0, 17.0],
            ['PCT', null, 'MI/L', '1.08 - 2.82', 1.08, 2.82],
            ['WBC', null, '10^9/L', '4 - 10', 4, 10],
            ['Neu#', null, '10^9/L', '2 - 7', 2, 7],
            ['LYM#', null, '10^9/L', '0.80 - 4.00', 0.80, 4.00],
            ['Mon#', null, '10^9/L', '0.12 - 1.20', 0.12, 1.20],
            ['Eos#', null, '10^9/L', '0.02 - 0.50', 0.02, 0.50],
            ['Bas#', null, '10^9/L', '0.00 - 0.10', 0.00, 0.10],
            ['Neu%', null, '%', '50.0 - 70.0', 50.0, 70.0],
            ['LYM%', null, '%', '20.0 - 40.0', 20.0, 40.0],
            ['Mon%', null, '%', '3.0 - 12.0', 3.0, 12.0],
            ['Eos%', null, '%', '0.5 - 5.0', 0.5, 5.0],
            ['Bas%', null, '%', '0.0 - 1.0', 0.0, 1.0],
            ['RBC', null, '10^12/L', '4.00 - 6.10', 4.00, 6.10],
        ];
        $this->insertParams($cbcId, $cbcParams);

        // ---------------------------------------------------------
        // 2. Special Chemistry (Page 4)
        // ---------------------------------------------------------
        $specialId = $this->getOrCreateTestType('Special Chemistry', 'Special Chemistry', 'Serum');

        $specialParams = [
            ['B.HCG', null, 'IU/L', "Non Pregnant < 1 - 3.1 IU/L\nPregnant 6 - 10 W = 5391 - 189226 IU/L\n11 - 15 w, 22397 - 187824 IU/L\n16 - 22 w, 10032 - 76108 IU/L\n23 - 40 w, 1162 - 73999 IU/L\nHealthy Non Pregnant < 5 IU/L\nEarly Pregnancy = 5 - 251IU/L", null, null],
            ['CRP', null, 'mg/L', '< 5.12 mg/L', 0, 5.12],
            ['cTnl', null, 'ng/ml', '<0.040 ng/ml', 0, 0.040],
            ['HbSag', null, 'iu/ml', '<0.50iu/ml', 0, 0.50],
            ['Anti HCV', null, null, 'Non-Reactive', null, null],
            ['HIV', null, null, 'Non-Reactive', null, null],
            ['Ferittin', null, 'ng/mL', "Male 46.53 - 439.27 ng/mL\nFemale 13.66 - 156.38 ng/mL", 13.66, 439.27],
            ['B-12', null, "pmol/mL\npg/mL", "152 - 565pmol/mL\n206 - 765 pg/mL", 152, 765],
            ['Folate', null, 'nmol/L', '7.82 - 45.77nmol/L', 7.82, 45.77],
            ['Vit-D', null, 'ng/mL', "Severe Deficiency <10ng/mL\nDeficient <20ng/mL\nInsufficient 20 - 30ng/mL\nSufficient > 30ng/mL", null, null],
            ['T3', null, 'pmol/L', '3.30 - 7.30 pmol/L', 3.30, 7.30],
            ['T4', null, 'nmol/L', '60.72 - 170 nmol/L', 60.72, 170.0],
            ['TSH', null, 'ulU/mL', '0.3 - 4.3 ulU/mL', 0.3, 4.3],
        ];
        $this->insertParams($specialId, $specialParams);

        // ---------------------------------------------------------
        // 3. HbA1c (Page 6)
        // ---------------------------------------------------------
        $hba1cId = $this->getOrCreateTestType('Glycosylated Hb (HbA1C)', 'Special Chemistry', 'Blood');

        $hba1cParams = [
            ['HbA1c', null, '%', "Non-Diabetic: 4.0 - 5.6%\nPrediabetic: 5.7% - 6.4%\nDiabetes Mellitus: >6.5%\nTherapeutic goal for most adults with diabetes Mellitus: < 7.0%", 4.0, 5.6],
        ];
        $this->insertParams($hba1cId, $hba1cParams);

        // ---------------------------------------------------------
        // 4. BLOOD CHEMISTRY (Page 5)
        // ---------------------------------------------------------
        $chemId = $this->getOrCreateTestType('BLOOD CHEMISTRY', 'Biochemistry', 'Blood');

        $chemParams = [
            ['Blood Glucose (R)', 'BLOOD CHEMISTRY', 'mg/dl', "F. 75 --- to --- 115 mg/dl\nR. 80 --- to --- 160 mg/dl", 75, 160],
            ['SGPT (ALT)', 'LIVER FUNCTION TEST', 'U/L', "9---to---43 U/L (Male)\n9---to---36 U/L (Female)", 9, 43],
            ['SGOT (AST)', 'LIVER FUNCTION TEST', 'U/L', "Up to 35 U/L (Male)\nUp to 31 U/L (Female)", 0, 35],
            ['ALP', 'LIVER FUNCTION TEST', 'U/L', "80---to---270 U/L(Male)\n65---to---240 U/L(Female)", 65, 270],
            ['T.Bilirubin', 'LIVER FUNCTION TEST', 'mg/dl', '0.1---to1.2 mg/dl', 0.1, 1.2],
            ['Urea', 'KIDNEY FUNCTION TESTS', 'Mg/dl', '10---to---50mg/dl', 10, 50],
            ['Creatinine', 'KIDNEY FUNCTION TESTS', 'mg/dl', "0.7 --- to --- 1.2 mg/dl (Male)\n0.6---to---0.9Mg/dl (Female)", 0.6, 1.2],
            ['Uric Acid', 'KIDNEY FUNCTION TESTS', 'mg/dl', "3.5 --- to --- 7.2 mg/dl (Male)\n2.6 --- to --- 6.0 mg/dl (Female)", 2.6, 7.2],
            ['Cholesterol', 'LIPID PROFILE', 'mg/dl', '<200 mg/dl', 0, 200],
            ['Triglyceride', 'LIPID PROFILE', 'mg/dl', '<150 mg/dl', 0, 150],
        ];
        $this->insertParams($chemId, $chemParams);

        // ---------------------------------------------------------
        // 5. URINE ROUTINE EXAMINATION (Page 7)
        // ---------------------------------------------------------
        $urineId = $this->getOrCreateTestType('Urine Routine Examination', 'Pathology', 'Urine');

        $urineParams = [
            // Physical
            ['Physical Appearance (Color)', 'PHYSICAL EXAMINATION', null, 'Yellow', null, null],
            ['Turbidity', 'PHYSICAL EXAMINATION', null, 'Clear', null, null],

            // Chemical
            ['Specific Gravity', 'CHEMICAL EXAMINATION', null, '1.005 - 1.030', 1.005, 1.030],
            ['pH', 'CHEMICAL EXAMINATION', null, '4.5 - 7.5', 4.5, 7.5],
            ['Glucose', 'CHEMICAL EXAMINATION', null, 'Negative', null, null],
            ['Protein', 'CHEMICAL EXAMINATION', null, 'Negative', null, null],
            ['Ketones', 'CHEMICAL EXAMINATION', null, 'Negative', null, null],
            ['Blood/Hb', 'CHEMICAL EXAMINATION', null, 'Negative', null, null],
            ['Leukocytes', 'CHEMICAL EXAMINATION', null, 'Negative', null, null],

            // Microscopic
            ['Pus Cells', 'MICROSCOPIC EXAMINATION', null, '0 - 5', 0, 5],
            ['RBC (Erythrocytes)', 'MICROSCOPIC EXAMINATION', null, 'Nil', 0, 0],
            ['Epithelial Cells', 'MICROSCOPIC EXAMINATION', null, 'Nil', 0, 0],
            ['Bacteria', 'MICROSCOPIC EXAMINATION', null, 'Nil', null, null],
            ['Casts', 'MICROSCOPIC EXAMINATION', null, 'Nil', null, null],
            ['Ca Oxalates', 'MICROSCOPIC EXAMINATION', null, 'Nil', null, null],
            ['Amorphous Urates', 'MICROSCOPIC EXAMINATION', null, 'Not Present', null, null],
            ['Amorphous Phosphates', 'MICROSCOPIC EXAMINATION', null, 'Not Present', null, null],
        ];
        $this->insertParams($urineId, $urineParams);
    }

    private function getOrCreateTestType($name, $dept, $sample)
    {
        $type = DB::table('lab_test_types')->where('name', $name)->first();

        if ($type) {
            return $type->id;
        }

        return DB::table('lab_test_types')->insertGetId([
            'uuid' => (string) Str::uuid(), // ADD THIS LINE
            'name' => $name,
            'department' => $dept,
            'sample_type' => $sample,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // Helper function to insert parameters efficiently
    private function insertParams($testTypeId, $params): void
    {
        $order = 1;
        foreach ($params as $p) {
            // Format reference_range to ensure line breaks are stored properly
            $referenceRange = $p[3];
            if ($referenceRange) {
                // Ensure we store literal \n for database
                $referenceRange = str_replace("\n", '\n', $referenceRange);
            }

            DB::table('lab_test_parameters')->updateOrInsert(
                ['lab_test_type_id' => $testTypeId, 'name' => $p[0]],
                [
                    'uuid' => (string) Str::uuid(), // ADD THIS LINE
                    'group_name' => $p[1],
                    'unit' => $p[2],
                    'reference_range' => $referenceRange,
                    'min_range' => $p[4],
                    'max_range' => $p[5],
                    'order' => $order++,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
