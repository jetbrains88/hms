<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Get the CMO branch (must exist)
        $cmoBranch = Branch::where('type', 'CMO')->first();
        if (!$cmoBranch) {
            $this->command->error('CMO branch not found! Please run BranchSeeder first.');
            return;
        }

        // Get all roles
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $doctorRole = Role::where('name', 'doctor')->first();
        $pharmacyRole = Role::where('name', 'pharmacy')->first();
        $labRole = Role::where('name', 'lab')->first();
        $nurseRole = Role::where('name', 'nurse')->first();
        $receptionRole = Role::where('name', 'reception')->first();

        if (!$superAdminRole || !$adminRole || !$doctorRole || !$pharmacyRole || !$labRole || !$nurseRole || !$receptionRole) {
            $this->command->error('Required roles not found! Please run RolePermissionSeeder first.');
            return;
        }

        // Clear existing pivot data
        DB::table('branch_user')->truncate();
        DB::table('role_user')->truncate();

        // ============ SYSTEM SUPER ADMIN ============
        $superAdmin = User::updateOrCreate(
            ['email' => 'super.admin@hms.com'],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'System Super Administrator',
                'password' => Hash::make('SuperAdmin@123'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        DB::table('role_user')->insert(['user_id' => $superAdmin->id, 'role_id' => $superAdminRole->id]);
        DB::table('branch_user')->insert([
            'user_id' => $superAdmin->id,
            'branch_id' => $cmoBranch->id,
            'is_primary' => 1,
        ]);

        // ============ HOSPITAL ADMIN ============
        $hospitalAdmin = User::updateOrCreate(
            ['email' => 'hospital.admin@hms.com'],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'Hospital Administrator',
                'password' => Hash::make('Hospital@123'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        DB::table('role_user')->insert(['user_id' => $hospitalAdmin->id, 'role_id' => $adminRole->id]);
        DB::table('branch_user')->insert([
            'user_id' => $hospitalAdmin->id,
            'branch_id' => $cmoBranch->id,
            'is_primary' => 1,
        ]);

        // ============ DOCTOR ============
        $hospitalDoctor = User::updateOrCreate(
            ['email' => 'hospital.doctor@hms.com'],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'Dr. Ahmed Khan',
                'password' => Hash::make('HospitalDoc@123'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        DB::table('role_user')->insert(['user_id' => $hospitalDoctor->id, 'role_id' => $doctorRole->id]);
        DB::table('branch_user')->insert([
            'user_id' => $hospitalDoctor->id,
            'branch_id' => $cmoBranch->id,
            'is_primary' => 1,
        ]);

        // ============ PHARMACY ============
        $hospitalPharmacy = User::updateOrCreate(
            ['email' => 'hospital.pharmacy@hms.com'],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'Pharmacy Manager',
                'password' => Hash::make('HospitalPharm@123'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        DB::table('role_user')->insert(['user_id' => $hospitalPharmacy->id, 'role_id' => $pharmacyRole->id]);
        DB::table('branch_user')->insert([
            'user_id' => $hospitalPharmacy->id,
            'branch_id' => $cmoBranch->id,
            'is_primary' => 1,
        ]);

        // ============ LAB ============
        $hospitalLab = User::updateOrCreate(
            ['email' => 'hospital.lab@hms.com'],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'Lab Manager',
                'password' => Hash::make('HospitalLab@123'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        DB::table('role_user')->insert(['user_id' => $hospitalLab->id, 'role_id' => $labRole->id]);
        DB::table('branch_user')->insert([
            'user_id' => $hospitalLab->id,
            'branch_id' => $cmoBranch->id,
            'is_primary' => 1,
        ]);

        // ============ NURSE ============
        $hospitalNurse = User::updateOrCreate(
            ['email' => 'hospital.nurse@hms.com'],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'Senior Nurse',
                'password' => Hash::make('HospitalNurse@123'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        DB::table('role_user')->insert(['user_id' => $hospitalNurse->id, 'role_id' => $nurseRole->id]);
        DB::table('branch_user')->insert([
            'user_id' => $hospitalNurse->id,
            'branch_id' => $cmoBranch->id,
            'is_primary' => 1,
        ]);

        // ============ RECEPTION ============
        $hospitalReception = User::updateOrCreate(
            ['email' => 'hospital.reception@hms.com'],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'Head Receptionist',
                'password' => Hash::make('HospitalReception@123'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        DB::table('role_user')->insert(['user_id' => $hospitalReception->id, 'role_id' => $receptionRole->id]);
        DB::table('branch_user')->insert([
            'user_id' => $hospitalReception->id,
            'branch_id' => $cmoBranch->id,
            'is_primary' => 1,
        ]);

        // ============ LEGACY USERS (all assigned to CMO branch) ============
        $legacyUsers = [
            ['email' => 'admin@gmail.com', 'name' => 'Legacy Admin', 'password' => 'Admin@123', 'roles' => [$adminRole->id, $superAdminRole->id]],
            ['email' => 'doctor@gmail.com', 'name' => 'Dr. Legacy Doctor', 'password' => 'Doctor@123', 'roles' => [$doctorRole->id, $adminRole->id]],
            ['email' => 'pharmacy@gmail.com', 'name' => 'Legacy Pharmacy', 'password' => 'Pharmacy@123', 'roles' => [$pharmacyRole->id, $receptionRole->id]],
            ['email' => 'reception@gmail.com', 'name' => 'Legacy Reception', 'password' => 'Reception@123', 'roles' => [$receptionRole->id]],
            ['email' => 'nurse@gmail.com', 'name' => 'Legacy Nurse', 'password' => 'Nurse@123', 'roles' => [$nurseRole->id, $labRole->id]],
            ['email' => 'lab@gmail.com', 'name' => 'Legacy Lab', 'password' => 'Lab@123', 'roles' => [$labRole->id, $receptionRole->id]],
        ];

        foreach ($legacyUsers as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
            foreach ($data['roles'] as $roleId) {
                DB::table('role_user')->insert(['user_id' => $user->id, 'role_id' => $roleId]);
            }
            DB::table('branch_user')->insert([
                'user_id' => $user->id,
                'branch_id' => $cmoBranch->id,
                'is_primary' => 1,
            ]);
        }

        $this->command->info('✅ Users seeded successfully (all assigned to CMO branch).');
        $this->command->info('Login credentials: (same as before)');
    }
}
