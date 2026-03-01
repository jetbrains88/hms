<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Diagnosis;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Prescription;
use App\Models\PrescriptionDispensation;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PrescriptionSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('💊 Seeding prescriptions and dispensations...');

        $branch = Branch::where('type', 'CMO')->first();
        if (!$branch) return;

        $doctor = User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->first();
        $pharmacist = User::whereHas('roles', fn($q) => $q->where('name', 'pharmacy'))->first();
        
        if (!$doctor || !$pharmacist) {
            $this->command->error('Doctor or Pharmacist not found!');
            return;
        }

        $completedVisits = Visit::where('status', 'completed')->take(50)->get();
        $medicines = Medicine::where('is_active', true)->take(20)->get();

        if ($completedVisits->isEmpty() || $medicines->isEmpty()) {
            $this->command->error('No visits or medicines found for prescription seeding!');
            return;
        }

        foreach ($completedVisits as $visit) {
            // Create a diagnosis if not exists
            $diagnosis = Diagnosis::firstOrCreate(
                ['visit_id' => $visit->id],
                [
                    'uuid' => (string) Str::uuid(),
                    'branch_id' => $branch->id,
                    'visit_id' => $visit->id,
                    'doctor_id' => $doctor->id,
                    'symptoms' => 'Fever, cough, headache',
                    'diagnosis' => 'Viral infection',
                    'doctor_notes' => 'Patient has mild symptoms.',
                    'has_prescription' => true,
                ]
            );

            // Create 1-3 prescriptions
            $count = rand(1, 3);
            for ($i = 0; $i < $count; $i++) {
                $medicine = $medicines->random();
                
                $morning = rand(0, 1);
                $evening = rand(0, 1);
                $night = rand(0, 1);
                if ($morning + $evening + $night === 0) $morning = 1;

                $days = rand(3, 14);
                $quantity = ($morning + $evening + $night) * $days;

                $status = rand(0, 100) > 30 ? 'completed' : 'pending';

                $prescription = Prescription::create([
                    'uuid' => (string) Str::uuid(),
                    'branch_id' => $branch->id,
                    'diagnosis_id' => $diagnosis->id,
                    'medicine_id' => $medicine->id,
                    'prescribed_by' => $doctor->id,
                    'dosage' => '1 tablet',
                    'frequency' => $morning + $evening + $night,
                    'morning' => $morning,
                    'evening' => $evening,
                    'night' => $night,
                    'days' => $days,
                    'quantity' => $quantity,
                    'status' => $status,
                    'instructions' => 'Take after meals.',
                ]);

                // If completed, create a dispensation
                if ($status === 'completed') {
                    $batch = MedicineBatch::where('medicine_id', $medicine->id)
                        ->where('branch_id', $branch->id)
                        ->first();

                    PrescriptionDispensation::create([
                        'uuid' => (string) Str::uuid(),
                        'prescription_id' => $prescription->id,
                        'quantity_dispensed' => $quantity,
                        'dispensed_by' => $pharmacist->id,
                        'dispensed_at' => now()->subHours(rand(1, 48)),
                        'medicine_batch_id' => $batch ? $batch->id : null,
                        'notes' => 'Successfully dispensed by ' . $pharmacist->name,
                    ]);
                }
            }
        }

        $this->command->info('✅ ' . Prescription::count() . ' prescriptions and ' . PrescriptionDispensation::count() . ' dispensations created!');
    }
}
