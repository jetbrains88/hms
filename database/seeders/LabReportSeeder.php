<?php

namespace Database\Seeders;

use App\Models\LabOrder;
use App\Models\LabOrderItem;
use App\Models\LabResult;
use App\Models\Patient;
use App\Models\User;
use App\Models\Visit;
use App\Models\Branch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LabReportSeeder extends Seeder
{
    private $labNumberCounter = [];

    public function run(): void
    {
        $this->command->info('🧪 Seeding laboratory orders and results...');

        // Get CMO branch
        $branch = Branch::where('type', 'CMO')->first();
        if (!$branch) {
            $this->command->error('CMO branch not found!');
            return;
        }

        $patients = Patient::all();
        $doctor = User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->first();
        $technician = User::whereHas('roles', fn($q) => $q->where('name', 'lab'))->first();

        if (!$patients->count()) {
            $this->command->warn('No patients found. Please seed patients first.');
            return;
        }

        if (!$doctor) {
            $this->command->error('No doctor found! Please run UserSeeder first.');
            return;
        }

        // Get all test types
        $testTypes = DB::table('lab_test_types')->get();

        if ($testTypes->isEmpty()) {
            $this->command->error('No lab test types found! Please run LaboratorySeeder first.');
            return;
        }

        $testTypesArray = $testTypes->toArray();

        foreach ($patients as $patient) {
            // Get or create a visit
            $visit = Visit::where('patient_id', $patient->id)->first();

            if (!$visit) {
                $visit = Visit::create([
                    'uuid' => (string) Str::uuid(),
                    'branch_id' => $branch->id,
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctor->id,
                    'queue_token' => 'TKN-' . str_pad($patient->id, 3, '0', STR_PAD_LEFT),
                    'visit_type' => 'routine',
                    'status' => 'completed',
                    'complaint' => 'Routine checkup',
                ]);
            }

            // Create 1-3 lab orders per patient
            $orderCount = rand(1, 2);

            for ($i = 0; $i < $orderCount; $i++) {
                // Choose a random test type for this order (could be any)
                $randomTestType = $testTypesArray[array_rand($testTypesArray)];
                $status = $this->getRandomStatus();
                $isCompleted = $status === 'completed';

                // Create the lab order (without lab_test_type_id)
                $labOrder = LabOrder::create([
                    'uuid' => (string) Str::uuid(),
                    'branch_id' => $branch->id,
                    'patient_id' => $patient->id,
                    'visit_id' => $visit->id,
                    'doctor_id' => $doctor->id,
                    'collection_date' => $isCompleted ? now()->subDays(rand(1, 30)) : null,
                    'reporting_date' => $isCompleted ? now()->subDays(rand(0, 2)) : null,
                    'lab_number' => $this->generateUniqueLabNumber(),
                    'status' => $status,
                    'priority' => rand(0, 1) ? 'normal' : 'urgent',
                    'is_verified' => $isCompleted,
                    'verified_by_user_id' => $isCompleted && $technician ? $technician->id : null,
                    'verified_at' => $isCompleted ? now()->subDays(rand(0, 2)) : null,
                    'device_name' => $this->getRandomDevice(),
                    'comments' => $this->getRandomComment($randomTestType->name),
                ]);

                // Create one lab order item for the selected test type
                $labOrderItem = LabOrderItem::create([
                    'uuid' => (string) Str::uuid(),
                    'lab_order_id' => $labOrder->id,
                    'lab_test_type_id' => $randomTestType->id,
                    'technician_id' => $isCompleted && $technician ? $technician->id : null,
                    'status' => $status,
                ]);

                // Get parameters for this test type
                $parameters = DB::table('lab_test_parameters')
                    ->where('lab_test_type_id', $randomTestType->id)
                    ->get();

                // Create results for completed orders only
                if ($isCompleted && $parameters->isNotEmpty()) {
                    foreach ($parameters as $parameter) {
                        $resultValue = $this->generateResultValue($parameter);

                        LabResult::create([
                            'uuid' => (string) Str::uuid(),
                            'lab_order_item_id' => $labOrderItem->id,
                            'lab_test_parameter_id' => $parameter->id,
                            'value_type' => $this->determineValueType($parameter),
                            'numeric_value' => is_numeric($resultValue) ? $resultValue : null,
                            'text_value' => !is_numeric($resultValue) ? $resultValue : null,
                            'boolean_value' => in_array($resultValue, ['true', 'false', 1, 0]) ? (bool)$resultValue : null,
                            'is_abnormal' => $this->isAbnormal($resultValue, $parameter),
                            'remarks' => $this->getRemarks($parameter, $resultValue),
                        ]);
                    }

                    // Create a notification for this completed order
                    DB::table('notifications')->insert([
                        'uuid' => (string) Str::uuid(),
                        'user_id' => $doctor->id,
                        'triggered_by' => $technician?->id,
                        'notifiable_type' => 'App\\Models\\User',
                        'notifiable_id' => $doctor->id,
                        'related_type' => 'App\\Models\\LabOrder',
                        'related_id' => $labOrder->id,
                        'type' => 'App\\Notifications\\LabResultReady',
                        'title' => 'Lab Report Completed',
                        'body' => "Lab report for {$patient->name} ({$labOrder->lab_number}) is ready",
                        'action_url' => "/doctor/lab-orders/{$labOrder->id}",
                        'action_text' => 'View Report',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        $this->command->info('✅ Laboratory data seeded successfully!');
        $this->command->info("   Total Lab Orders: " . LabOrder::count());
        $this->command->info("   Total Lab Order Items: " . LabOrderItem::count());
        $this->command->info("   Total Lab Results: " . LabResult::count());
    }

    private function generateUniqueLabNumber(): string
    {
        $date = date('Ymd');

        if (!isset($this->labNumberCounter[$date])) {
            $this->labNumberCounter[$date] = 1;
        } else {
            $this->labNumberCounter[$date]++;
        }

        return 'LAB-' . $date . '-' . str_pad($this->labNumberCounter[$date], 4, '0', STR_PAD_LEFT);
    }

    private function getRandomStatus(): string
    {
        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        $weights = [20, 30, 40, 10];

        $total = array_sum($weights);
        $random = rand(1, $total);

        foreach ($weights as $index => $weight) {
            $random -= $weight;
            if ($random <= 0) {
                return $statuses[$index];
            }
        }

        return 'pending';
    }

    private function getRandomDevice(): string
    {
        $devices = [
            'Mindray BC-5000',
            'Sysmex XN-1000',
            'Cobas 6000',
            'Architect ci8200',
            'VITROS 3600',
        ];
        return $devices[array_rand($devices)];
    }

    private function getRandomComment(string $testType): string
    {
        $comments = [
            "Routine {$testType}",
            "Follow-up {$testType}",
            "Pre-operative screening",
            "Annual health checkup",
            "Patient complaint investigation",
        ];
        return $comments[array_rand($comments)];
    }

    private function determineValueType($parameter): string
    {
        if ($parameter->input_type === 'number' || $parameter->min_range !== null) {
            return 'numeric';
        }
        if ($parameter->input_type === 'boolean') {
            return 'boolean';
        }
        return 'text';
    }

    private function generateResultValue($parameter)
    {
        $paramName = strtolower($parameter->name);

        // For numeric parameters with ranges
        if ($parameter->min_range !== null && $parameter->max_range !== null) {
            if (rand(1, 100) <= 80) {
                // Normal value
                $value = $parameter->min_range + (($parameter->max_range - $parameter->min_range) * 0.5);
                $value += ($parameter->max_range - $parameter->min_range) * (rand(-20, 20) / 100);
            } else {
                // Abnormal value
                if (rand(0, 1)) {
                    $value = $parameter->min_range * (rand(50, 80) / 100); // low
                } else {
                    $value = $parameter->max_range * (rand(120, 200) / 100); // high
                }
            }
            return round($value, 2);
        }

        // For non-numeric parameters
        if (str_contains($paramName, 'color') || str_contains($paramName, 'appearance')) {
            return rand(0, 10) <= 8 ? 'Yellow' : 'Cloudy';
        }

        if (str_contains($paramName, 'turbidity')) {
            return rand(0, 10) <= 9 ? 'Clear' : 'Turbid';
        }

        if (
            str_contains($paramName, 'glucose') || str_contains($paramName, 'protein') ||
            str_contains($paramName, 'ketones') || str_contains($paramName, 'blood')
        ) {
            return rand(0, 10) <= 9 ? 'Negative' : 'Trace';
        }

        if (str_contains($paramName, 'bacteria') || str_contains($paramName, 'casts')) {
            return rand(0, 10) <= 9 ? 'Nil' : 'Present';
        }

        return 'Normal';
    }

    private function isAbnormal($value, $parameter): bool
    {
        if (!is_numeric($value) || $parameter->min_range === null || $parameter->max_range === null) {
            return false;
        }

        $numValue = (float) $value;
        return $numValue < $parameter->min_range || $numValue > $parameter->max_range;
    }

    private function getRemarks($parameter, $value): string
    {
        if (!$this->isAbnormal($value, $parameter)) {
            return 'Within normal limits';
        }

        if (!is_numeric($value) || $parameter->min_range === null || $parameter->max_range === null) {
            return 'Abnormal finding';
        }

        $numValue = (float) $value;
        if ($numValue < $parameter->min_range) {
            return 'Below reference range';
        }

        return 'Above reference range';
    }
}
