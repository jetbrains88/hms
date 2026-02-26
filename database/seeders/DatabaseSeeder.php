<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🏥 Starting Multi-Tenant Hospital Management System Seeding...');
        $this->command->info('============================================');
        $this->command->info('');

        // PHASE 1: CORE CONFIGURATION
        $this->command->info('🔧 PHASE 1: Core Configuration Data');
        $this->command->info('--------------------------------------------');
        $this->call([
            OfficeSeeder::class,           // NHMP offices (needed for branches)
            BranchSeeder::class,           // Multi-tenant branches (now only CMO)
            DesignationSeeder::class,       // Designations
            RolePermissionSeeder::class,    // Enhanced RBAC
        ]);

        // PHASE 2: MASTER DATA
        $this->command->info('');
        $this->command->info('📚 PHASE 2: Master Reference Data');
        $this->command->info('--------------------------------------------');
        $this->call([
            LaboratorySeeder::class,
        ]);

        // PHASE 3: TEST DATA (all tied to CMO branch)
        $this->command->info('');
        $this->command->info('👥 PHASE 3: Test/Development Data');
        $this->command->info('--------------------------------------------');
        $this->call([
            UserSeeder::class,              // Multi-tenant users (assigned to CMO)
            PatientSeeder::class,            // Patients (all at CMO)
            MedicineSeeder::class,           // Medicines and batches (CMO)
            VisitSeeder::class,               // Visits (CMO)
            LabReportSeeder::class,           // Lab orders (CMO)
        ]);

        // PHASE 4: NOTIFICATIONS
        $this->command->info('');
        $this->command->info('🔔 PHASE 4: Notifications');
        $this->command->info('--------------------------------------------');
        $this->call([
            NotificationSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('============================================');
        $this->command->info('✅ Multi-Tenant Hospital database seeded successfully!');

        $this->displayMultiTenantSummary();
    }

    private function displayMultiTenantSummary(): void
    {
        $branches = \App\Models\Branch::all();
        $users = \App\Models\User::all();
        $roles = \App\Models\Role::all();

        $this->command->info('');
        $this->command->info('📊 MULTI-TENANT SUMMARY:');
        $this->command->info('============================================');
        $this->command->info('🏪 Branches: ' . $branches->count());
        $this->command->info('   • CMO: ' . $branches->where('type', 'CMO')->count());
        $this->command->info('   • RMO: ' . $branches->where('type', 'RMO')->count());
        $this->command->info('');
        $this->command->info('👥 Users: ' . $users->count());
        $this->command->info('🔐 Roles: ' . $roles->count());
    }
}
