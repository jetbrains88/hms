<?php

namespace Database\Seeders;

use App\Models\LabOrder;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🔔 Seeding notifications...');

        // Get all users by role
        $doctor = User::where('email', 'hospital.doctor@hms.com')->first()
            ?? User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->first();

        $pharmacy = User::where('email', 'hospital.pharmacy@hms.com')->first()
            ?? User::whereHas('roles', fn($q) => $q->where('name', 'pharmacy'))->first();

        $reception = User::where('email', 'hospital.reception@hms.com')->first()
            ?? User::whereHas('roles', fn($q) => $q->where('name', 'reception'))->first();

        $nurse = User::where('email', 'hospital.nurse@hms.com')->first()
            ?? User::whereHas('roles', fn($q) => $q->where('name', 'nurse'))->first();

        $lab = User::where('email', 'hospital.lab@hms.com')->first()
            ?? User::whereHas('roles', fn($q) => $q->where('name', 'lab'))->first();

        if (!$doctor || !$pharmacy || !$reception || !$lab) {
            $this->command->error('Required users not found! Please run UserSeeder first.');
            return;
        }

        // Get sample data
        $patients = Patient::all();
        $visits = Visit::all();
        $prescriptions = Prescription::where('status', 'pending')->take(3)->get();
        $labOrders = LabOrder::where('status', 'completed')->take(3)->get();

        $this->command->info('   Creating notifications for all workflows...');

        // Clear existing notifications
        DB::table('notifications')->truncate();

        // 1. RECEPTION → DOCTOR: New patient waiting
        $this->seedPatientWaitingNotifications($reception, $doctor, $patients, $visits);

        // 2. DOCTOR → PHARMACY: New prescriptions
        $this->seedPrescriptionNotifications($doctor, $pharmacy, $prescriptions);

        // 3. LAB → DOCTOR: Completed lab reports
        $this->seedLabCompletedNotifications($lab, $doctor, $labOrders);

        // 4. PHARMACY → DOCTOR: Prescription dispensed
        $this->seedDispensedNotifications($pharmacy, $doctor, $prescriptions);

        // 5. SYSTEM: Stock alerts for pharmacy
        $this->seedStockAlertNotifications($pharmacy);

        // 6. NURSE → DOCTOR: Vitals recorded
        $this->seedVitalsNotifications($nurse, $doctor, $visits);

        $this->command->info('✅ Notifications seeded successfully!');
        $this->command->info('   Total: ' . DB::table('notifications')->count() . ' notifications created');
    }

    private function seedPatientWaitingNotifications($reception, $doctor, $patients, $visits): void
    {
        $this->command->info('   📋 Creating patient waiting notifications...');

        foreach ($patients->take(3) as $index => $patient) {
            $visit = $visits->where('patient_id', $patient->id)->first();

            DB::table('notifications')->insert([
                'uuid' => (string) Str::uuid(),
                'user_id' => $doctor->id,
                'triggered_by' => $reception->id,
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $doctor->id,
                'related_type' => 'App\\Models\\Patient',
                'related_id' => $patient->id,
                'type' => 'App\\Notifications\\PatientWaiting',
                'title' => 'New Patient Waiting',
                'body' => "Patient {$patient->name} is waiting for consultation",
                'action_url' => '/doctor/consultation/queue',
                'action_text' => 'View Queue',
                'read_at' => rand(0, 1) ? now()->subMinutes(rand(1, 10)) : null,
                'clicked_at' => null,
                'created_at' => now()->subMinutes(rand(5, 30)),
                'updated_at' => now()->subMinutes(rand(5, 30)),
            ]);
        }
    }

    private function seedPrescriptionNotifications($doctor, $pharmacy, $prescriptions): void
    {
        $this->command->info('   💊 Creating prescription notifications...');

        foreach ($prescriptions as $prescription) {
            $diagnosis = $prescription->diagnosis;
            $visit = $diagnosis?->visit;
            $patient = $visit?->patient;

            if (!$patient) continue;

            DB::table('notifications')->insert([
                'uuid' => (string) Str::uuid(),
                'user_id' => $pharmacy->id,
                'triggered_by' => $doctor->id,
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $pharmacy->id,
                'related_type' => 'App\\Models\\Prescription',
                'related_id' => $prescription->id,
                'type' => 'App\\Notifications\\NewPrescription',
                'title' => 'New Prescription Ready',
                'body' => "Dr. {$doctor->name} prescribed {$prescription->quantity} units of medicine for {$patient->name}",
                'action_url' => "/pharmacy/prescriptions/{$prescription->id}",
                'action_text' => 'Dispense Medicine',
                'read_at' => rand(0, 1) ? now()->subHours(rand(1, 12)) : null,
                'clicked_at' => null,
                'created_at' => now()->subHours(rand(1, 24)),
                'updated_at' => now()->subHours(rand(1, 24)),
            ]);
        }
    }

    private function seedLabCompletedNotifications($lab, $doctor, $labOrders): void
    {
        $this->command->info('   🧪 Creating lab completed notifications...');

        foreach ($labOrders as $labOrder) {
            $patient = $labOrder->patient;
            $testType = DB::table('lab_test_types')
                ->join('lab_order_items', 'lab_test_types.id', '=', 'lab_order_items.lab_test_type_id')
                ->where('lab_order_items.lab_order_id', $labOrder->id)
                ->first();

            DB::table('notifications')->insert([
                'uuid' => (string) Str::uuid(),
                'user_id' => $doctor->id,
                'triggered_by' => $lab->id,
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $doctor->id,
                'related_type' => 'App\\Models\\LabOrder',
                'related_id' => $labOrder->id,
                'type' => 'App\\Notifications\\LabResultReady',
                'title' => 'Lab Report Completed',
                'body' => "Lab report for {$patient->name} ({$labOrder->lab_number}) is ready",
                'action_url' => "/doctor/lab-reports/{$labOrder->id}",
                'action_text' => 'View Report',
                'read_at' => rand(0, 1) ? now()->subHours(rand(1, 24)) : null,
                'clicked_at' => null,
                'created_at' => $labOrder->reporting_date ?? now()->subHours(rand(1, 48)),
                'updated_at' => $labOrder->reporting_date ?? now()->subHours(rand(1, 48)),
            ]);
        }
    }

    private function seedDispensedNotifications($pharmacy, $doctor, $prescriptions): void
    {
        $this->command->info('   💊 Creating dispensed notifications...');

        foreach ($prescriptions->take(2) as $prescription) {
            $diagnosis = $prescription->diagnosis;
            $patient = $diagnosis?->visit?->patient;

            if (!$patient) continue;

            DB::table('notifications')->insert([
                'uuid' => (string) Str::uuid(),
                'user_id' => $doctor->id,
                'triggered_by' => $pharmacy->id,
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $doctor->id,
                'related_type' => 'App\\Models\\Prescription',
                'related_id' => $prescription->id,
                'type' => 'App\\Notifications\\PrescriptionDispensed',
                'title' => 'Prescription Dispensed',
                'body' => "Prescription for {$patient->name} has been dispensed",
                'action_url' => "/doctor/prescriptions/{$prescription->id}",
                'action_text' => 'View Details',
                'read_at' => null,
                'clicked_at' => null,
                'created_at' => now()->subHours(rand(1, 12)),
                'updated_at' => now()->subHours(rand(1, 12)),
            ]);
        }
    }

    private function seedStockAlertNotifications($pharmacy): void
    {
        $this->command->info('   ⚠️ Creating stock alert notifications...');

        $medicines = DB::table('medicines')
            ->whereIn('name', ['Ibuprofen', 'Multivitamin'])
            ->get();

        foreach ($medicines as $medicine) {
            DB::table('notifications')->insert([
                'uuid' => (string) Str::uuid(),
                'user_id' => $pharmacy->id,
                'triggered_by' => null,
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $pharmacy->id,
                'related_type' => 'App\\Models\\Medicine',
                'related_id' => $medicine->id,
                'type' => 'App\\Notifications\\StockAlert',
                'title' => $medicine->name == 'Ibuprofen' ? 'Low Stock Alert' : 'Out of Stock Alert',
                'body' => $medicine->name == 'Ibuprofen'
                    ? 'Ibuprofen 400mg is low on stock (50 units). Reorder level: 100'
                    : 'Multivitamin is out of stock. Please reorder immediately.',
                'action_url' => '/pharmacy/medicines',
                'action_text' => 'View Inventory',
                'read_at' => null,
                'clicked_at' => null,
                'created_at' => now()->subHours(rand(2, 48)),
                'updated_at' => now()->subHours(rand(2, 48)),
            ]);
        }
    }

    private function seedVitalsNotifications($nurse, $doctor, $visits): void
    {
        $this->command->info('   📊 Creating vitals notifications...');

        foreach ($visits->take(2) as $visit) {
            $patient = $visit->patient;

            DB::table('notifications')->insert([
                'uuid' => (string) Str::uuid(),
                'user_id' => $doctor->id,
                'triggered_by' => $nurse->id,
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $doctor->id,
                'related_type' => 'App\\Models\\Visit',
                'related_id' => $visit->id,
                'type' => 'App\\Notifications\\VitalsRecorded',
                'title' => 'Vitals Recorded',
                'body' => "Vitals recorded for patient {$patient->name}",
                'action_url' => "/doctor/visits/{$visit->id}",
                'action_text' => 'Review Vitals',
                'read_at' => null,
                'clicked_at' => null,
                'created_at' => now()->subHours(rand(1, 6)),
                'updated_at' => now()->subHours(rand(1, 6)),
            ]);
        }
    }
}
