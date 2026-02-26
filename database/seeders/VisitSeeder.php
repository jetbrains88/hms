<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use App\Models\Visit;
use App\Models\Vital;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VisitSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample visits for patients
        $patients = Patient::all();
        $doctor = User::whereHas('roles', function ($query) {
            $query->where('name', 'doctor');
        })->first();

        $nurse = User::whereHas('roles', function ($query) {
            $query->where('name', 'nurse');
        })->first();

        // If no nurse found, use doctor as fallback
        if (!$nurse) {
            $nurse = $doctor;
        }

        // Get a branch ID (use first branch or head office)
        $branch = \App\Models\Branch::first();
        if (!$branch) {
            $this->command->error('No branches found! Please run BranchSeeder first.');
            return;
        }

        foreach ($patients as $index => $patient) {
            $visitTypes = ['routine', 'emergency', 'followup'];
            $statuses = ['waiting', 'in_progress', 'completed'];

            $visit = Visit::firstOrCreate(
                ['queue_token' => 'TKN-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT)],
                [
                    'uuid' => (string) Str::uuid(),
                    'branch_id' => $branch->id,
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctor->id,
                    'visit_type' => $visitTypes[$index % count($visitTypes)],
                    'status' => $statuses[$index % count($statuses)],
                    'complaint' => 'Fever and headache',
                    'notes' => 'Patient presented with flu-like symptoms',
                ]
            );

            // Create vitals for completed visits
            if ($visit->status === 'completed') {
                // Calculate BMI based on height and weight
                $height = rand(150, 185); // cm
                $weight = rand(50, 90); // kg
                $heightInMeters = $height / 100;
                $bmi = $weight / ($heightInMeters * $heightInMeters);

                Vital::firstOrCreate(
                    ['visit_id' => $visit->id],
                    [
                        'uuid' => (string) Str::uuid(),
                        'branch_id' => $branch->id,
                        'patient_id' => $patient->id,
                        'visit_id' => $visit->id,
                        'recorded_by' => $nurse->id,
                        'recorded_at' => now(),
                        'temperature' => rand(97, 101) + (rand(0, 9) / 10),
                        'pulse' => rand(60, 100),
                        'respiratory_rate' => rand(12, 20),
                        'blood_pressure_systolic' => rand(110, 130),
                        'blood_pressure_diastolic' => rand(70, 85),
                        'oxygen_saturation' => rand(95, 100),
                        'pain_scale' => rand(0, 5),
                        'height' => $height,
                        'weight' => $weight,
                        'bmi' => round($bmi, 1),
                        'blood_glucose' => rand(70, 120) + (rand(0, 9) / 10),
                        'heart_rate' => rand(60, 100),
                        'notes' => 'Normal vitals recorded during routine checkup',
                    ]
                );
            }
        }

        $this->command->info('✅ Visits seeded successfully!');
    }
}
