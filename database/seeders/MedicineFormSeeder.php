<?php

namespace Database\Seeders;

use App\Models\MedicineForm;
use Illuminate\Database\Seeder;

class MedicineFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $forms = [
            'Tablet',
            'Capsule',
            'Syrup',
            'Injection',
            'Ointment',
            'Cream',
            'Drops',
            'Inhaler',
            'Powder',
            'Suspension',
            'Gel',
            'Spray',
            'Lotion',
            'Lozenge',
            'Other'
        ];

        foreach ($forms as $form) {
            MedicineForm::firstOrCreate(['name' => $form]);
        }
    }
}
