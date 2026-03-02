<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // First, clear existing pivot data to avoid conflicts
        DB::table('permission_role')->truncate();

        // Create ALL specified roles
        $roles = [
            ['name' => 'super_admin', 'display_name' => 'System Super Administrator'],
            ['name' => 'admin', 'display_name' => 'Administrator'],
            ['name' => 'doctor', 'display_name' => 'Doctor'],
            ['name' => 'pharmacy', 'display_name' => 'Pharmacy Staff'],
            ['name' => 'reception', 'display_name' => 'Receptionist'],
            ['name' => 'nurse', 'display_name' => 'Nurse'],
            ['name' => 'lab', 'display_name' => 'Lab Technician'],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name']],
                [
                    'uuid' => (string) Str::uuid(),
                    'display_name' => $roleData['display_name'],
                ]
            );
        }

        // Create Comprehensive Permissions based on routes
        $permissions = [
            // ============ DASHBOARD PERMISSIONS ============
            ['name' => 'view_dashboard', 'group' => 'dashboard', 'display_name' => 'View Dashboard'],
            ['name' => 'view_analytics', 'group' => 'dashboard', 'display_name' => 'View Analytics'],
            ['name' => 'view_realtime_data', 'group' => 'dashboard', 'display_name' => 'View Realtime Data'],

            // ============ USER MANAGEMENT ============
            ['name' => 'view_users', 'group' => 'users', 'display_name' => 'View Users'],
            ['name' => 'create_users', 'group' => 'users', 'display_name' => 'Create Users'],
            ['name' => 'edit_users', 'group' => 'users', 'display_name' => 'Edit Users'],
            ['name' => 'delete_users', 'group' => 'users', 'display_name' => 'Delete Users'],
            ['name' => 'toggle_user_status', 'group' => 'users', 'display_name' => 'Toggle User Status'],
            ['name' => 'reset_user_password', 'group' => 'users', 'display_name' => 'Reset User Password'],
            ['name' => 'view_user_permissions', 'group' => 'users', 'display_name' => 'View User Permissions'],
            ['name' => 'bulk_user_actions', 'group' => 'users', 'display_name' => 'Bulk User Actions'],

            // ============ ROLE MANAGEMENT ============
            ['name' => 'view_roles', 'group' => 'roles', 'display_name' => 'View Roles'],
            ['name' => 'create_roles', 'group' => 'roles', 'display_name' => 'Create Roles'],
            ['name' => 'edit_roles', 'group' => 'roles', 'display_name' => 'Edit Roles'],
            ['name' => 'delete_roles', 'group' => 'roles', 'display_name' => 'Delete Roles'],

            // ============ PERMISSION MANAGEMENT ============
            ['name' => 'view_permissions', 'group' => 'permissions', 'display_name' => 'View Permissions'],
            ['name' => 'create_permissions', 'group' => 'permissions', 'display_name' => 'Create Permissions'],
            ['name' => 'edit_permissions', 'group' => 'permissions', 'display_name' => 'Edit Permissions'],
            ['name' => 'delete_permissions', 'group' => 'permissions', 'display_name' => 'Delete Permissions'],

            // ============ OFFICE MANAGEMENT ============
            ['name' => 'view_offices', 'group' => 'offices', 'display_name' => 'View Offices'],
            ['name' => 'create_offices', 'group' => 'offices', 'display_name' => 'Create Offices'],
            ['name' => 'edit_offices', 'group' => 'offices', 'display_name' => 'Edit Offices'],
            ['name' => 'delete_offices', 'group' => 'offices', 'display_name' => 'Delete Offices'],

            // ============ DESIGNATION MANAGEMENT ============
            ['name' => 'view_designations', 'group' => 'designations', 'display_name' => 'View Designations'],
            ['name' => 'create_designations', 'group' => 'designations', 'display_name' => 'Create Designations'],
            ['name' => 'edit_designations', 'group' => 'designations', 'display_name' => 'Edit Designations'],
            ['name' => 'delete_designations', 'group' => 'designations', 'display_name' => 'Delete Designations'],

            // ============ BRANCH MANAGEMENT ============
            ['name' => 'view_branches', 'group' => 'branches', 'display_name' => 'View Branches'],
            ['name' => 'create_branches', 'group' => 'branches', 'display_name' => 'Create Branches'],
            ['name' => 'edit_branches', 'group' => 'branches', 'display_name' => 'Edit Branches'],
            ['name' => 'delete_branches', 'group' => 'branches', 'display_name' => 'Delete Branches'],
            ['name' => 'toggle_branch_status', 'group' => 'branches', 'display_name' => 'Toggle Branch Status'],

            // ============ AUDIT TRAILS ============
            ['name' => 'view_audit_logs', 'group' => 'audit', 'display_name' => 'View Audit Logs'],
            ['name' => 'export_audit_logs', 'group' => 'audit', 'display_name' => 'Export Audit Logs'],

            // ============ PATIENT MANAGEMENT ============
            ['name' => 'view_patients', 'group' => 'patients', 'display_name' => 'View Patients'],
            ['name' => 'create_patients', 'group' => 'patients', 'display_name' => 'Create Patients'],
            ['name' => 'edit_patients', 'group' => 'patients', 'display_name' => 'Edit Patients'],
            ['name' => 'delete_patients', 'group' => 'patients', 'display_name' => 'Delete Patients'],
            ['name' => 'export_patients', 'group' => 'patients', 'display_name' => 'Export Patients'],
            ['name' => 'bulk_upload_patients', 'group' => 'patients', 'display_name' => 'Bulk Upload Patients'],
            ['name' => 'view_patient_medical_history', 'group' => 'patients', 'display_name' => 'View Patient Medical History'],
            ['name' => 'view_patient_visit_history', 'group' => 'patients', 'display_name' => 'View Patient Visit History'],

            // ============ VISIT MANAGEMENT ============
            ['name' => 'view_visits', 'group' => 'visits', 'display_name' => 'View Visits'],
            ['name' => 'create_visits', 'group' => 'visits', 'display_name' => 'Create Visits'],
            ['name' => 'edit_visits', 'group' => 'visits', 'display_name' => 'Edit Visits'],
            ['name' => 'complete_visits', 'group' => 'visits', 'display_name' => 'Complete Visits'],
            ['name' => 'cancel_visits', 'group' => 'visits', 'display_name' => 'Cancel Visits'],
            ['name' => 'view_waiting_queue', 'group' => 'visits', 'display_name' => 'View Waiting Queue'],
            ['name' => 'update_visit_status', 'group' => 'visits', 'display_name' => 'Update Visit Status'],
            ['name' => 'view_visit_vitals', 'group' => 'visits', 'display_name' => 'View Visit Vitals'],

            // ============ PRESCRIPTION MANAGEMENT ============
            ['name' => 'view_prescriptions', 'group' => 'prescriptions', 'display_name' => 'View Prescriptions'],
            ['name' => 'create_prescriptions', 'group' => 'prescriptions', 'display_name' => 'Create Prescriptions'],
            ['name' => 'edit_prescriptions', 'group' => 'prescriptions', 'display_name' => 'Edit Prescriptions'],
            ['name' => 'dispense_prescriptions', 'group' => 'prescriptions', 'display_name' => 'Dispense Prescriptions'],
            ['name' => 'print_prescriptions', 'group' => 'prescriptions', 'display_name' => 'Print Prescriptions'],

            // ============ VITALS MANAGEMENT ============
            ['name' => 'view_vitals', 'group' => 'vitals', 'display_name' => 'View Vitals'],
            ['name' => 'record_vitals', 'group' => 'vitals', 'display_name' => 'Record Vitals'],
            ['name' => 'edit_vitals', 'group' => 'vitals', 'display_name' => 'Edit Vitals'],

            // ============ MEDICINE MANAGEMENT ============
            ['name' => 'view_medicines', 'group' => 'medicines', 'display_name' => 'View Medicines'],
            ['name' => 'create_medicines', 'group' => 'medicines', 'display_name' => 'Create Medicines'],
            ['name' => 'edit_medicines', 'group' => 'medicines', 'display_name' => 'Edit Medicines'],
            ['name' => 'delete_medicines', 'group' => 'medicines', 'display_name' => 'Delete Medicines'],
            ['name' => 'view_inventory', 'group' => 'medicines', 'display_name' => 'View Inventory'],
            ['name' => 'update_stock', 'group' => 'medicines', 'display_name' => 'Update Stock'],
            ['name' => 'view_stock_alerts', 'group' => 'medicines', 'display_name' => 'View Stock Alerts'],
            ['name' => 'resolve_stock_alerts', 'group' => 'medicines', 'display_name' => 'Resolve Stock Alerts'],
            ['name' => 'view_dispense_history', 'group' => 'medicines', 'display_name' => 'View Dispense History'],

            // ============ LAB MANAGEMENT ============
            ['name' => 'view_lab_dashboard', 'group' => 'lab', 'display_name' => 'View Lab Dashboard'],
            ['name' => 'view_lab_reports', 'group' => 'lab', 'display_name' => 'View Lab Reports'],
            ['name' => 'create_lab_reports', 'group' => 'lab', 'display_name' => 'Create Lab Reports'],
            ['name' => 'edit_lab_reports', 'group' => 'lab', 'display_name' => 'Edit Lab Reports'],
            ['name' => 'delete_lab_reports', 'group' => 'lab', 'display_name' => 'Delete Lab Reports'],
            ['name' => 'update_lab_report_status', 'group' => 'lab', 'display_name' => 'Update Lab Report Status'],
            ['name' => 'submit_lab_results', 'group' => 'lab', 'display_name' => 'Submit Lab Results'],
            ['name' => 'verify_lab_reports', 'group' => 'lab', 'display_name' => 'Verify Lab Reports'],
            ['name' => 'print_lab_reports', 'group' => 'lab', 'display_name' => 'Print Lab Reports'],
            ['name' => 'download_lab_pdf', 'group' => 'lab', 'display_name' => 'Download Lab PDF'],
            ['name' => 'export_lab_reports', 'group' => 'lab', 'display_name' => 'Export Lab Reports'],
            ['name' => 'view_lab_statistics', 'group' => 'lab', 'display_name' => 'View Lab Statistics'],
            ['name' => 'notify_doctor_lab_results', 'group' => 'lab', 'display_name' => 'Notify Doctor of Lab Results'],

            // ============ CONSULTATION MANAGEMENT ============
            ['name' => 'view_consultations', 'group' => 'consultation', 'display_name' => 'View Consultations'],
            ['name' => 'start_consultation', 'group' => 'consultation', 'display_name' => 'Start Consultation'],
            ['name' => 'complete_consultation', 'group' => 'consultation', 'display_name' => 'Complete Consultation'],
            ['name' => 'cancel_consultation', 'group' => 'consultation', 'display_name' => 'Cancel Consultation'],
            ['name' => 'view_consultation_stats', 'group' => 'consultation', 'display_name' => 'View Consultation Statistics'],
            ['name' => 'start_teleconsultation', 'group' => 'consultation', 'display_name' => 'Start Teleconsultation'],

            // ============ DIAGNOSIS MANAGEMENT ============
            ['name' => 'view_diagnoses', 'group' => 'diagnosis', 'display_name' => 'View Diagnoses'],
            ['name' => 'create_diagnoses', 'group' => 'diagnosis', 'display_name' => 'Create Diagnoses'],
            ['name' => 'edit_diagnoses', 'group' => 'diagnosis', 'display_name' => 'Edit Diagnoses'],

            // ============ REPORTS ============
            ['name' => 'view_reports', 'group' => 'reports', 'display_name' => 'View Reports'],
            ['name' => 'generate_reports', 'group' => 'reports', 'display_name' => 'Generate Reports'],
            ['name' => 'download_reports', 'group' => 'reports', 'display_name' => 'Download Reports'],
            ['name' => 'view_pharmacy_reports', 'group' => 'reports', 'display_name' => 'View Pharmacy Reports'],
            ['name' => 'view_consultation_reports', 'group' => 'reports', 'display_name' => 'View Consultation Reports'],

            // ============ SETTINGS ============
            ['name' => 'view_settings', 'group' => 'settings', 'display_name' => 'View Settings'],
            ['name' => 'update_settings', 'group' => 'settings', 'display_name' => 'Update Settings'],

            // ============ NOTIFICATIONS ============
            ['name' => 'view_notifications', 'group' => 'notifications', 'display_name' => 'View Notifications'],
            ['name' => 'mark_notifications_read', 'group' => 'notifications', 'display_name' => 'Mark Notifications Read'],

            // ============ API ACCESS ============
            ['name' => 'access_api', 'group' => 'api', 'display_name' => 'Access API'],
            ['name' => 'view_api_lab_reports', 'group' => 'api', 'display_name' => 'View API Lab Reports'],
        ];

        foreach ($permissions as $permissionData) {
            Permission::firstOrCreate(
                ['name' => $permissionData['name']],
                [
                    'uuid' => (string) Str::uuid(),
                    'group' => $permissionData['group'],
                    'display_name' => $permissionData['display_name'],
                ]
            );
        }

        // Get all roles
        $superAdmin = Role::where('name', 'super_admin')->first();
        $admin = Role::where('name', 'admin')->first();
        $doctor = Role::where('name', 'doctor')->first();
        $pharmacy = Role::where('name', 'pharmacy')->first();
        $reception = Role::where('name', 'reception')->first();
        $nurse = Role::where('name', 'nurse')->first();
        $lab = Role::where('name', 'lab')->first();

        // ============ SUPER ADMIN - ALL PERMISSIONS ============
        if ($superAdmin) {
            $superAdmin->permissions()->sync(Permission::all());
        }

        // ============ ADMIN - Full system access except super admin specific ============
        if ($admin) {
            $adminPermissions = Permission::whereIn('name', [
                // Dashboard
                'view_dashboard',
                'view_analytics',
                'view_realtime_data',

                // User Management
                'view_users',
                'create_users',
                'edit_users',
                'delete_users',
                'toggle_user_status',
                'reset_user_password',
                'view_user_permissions',
                'bulk_user_actions',

                // Role Management
                'view_roles',
                'create_roles',
                'edit_roles',
                'delete_roles',

                // Permission Management
                'view_permissions',
                'create_permissions',
                'edit_permissions',
                'delete_permissions',

                // Office Management
                'view_offices',
                'create_offices',
                'edit_offices',
                'delete_offices',

                // Designation Management
                'view_designations',
                'create_designations',
                'edit_designations',
                'delete_designations',

                // Branch Management
                'view_branches',
                'create_branches',
                'edit_branches',
                'delete_branches',
                'toggle_branch_status',

                // Audit Logs
                'view_audit_logs',
                'export_audit_logs',

                // Patient Management
                'view_patients',
                'create_patients',
                'edit_patients',
                'delete_patients',
                'export_patients',
                'bulk_upload_patients',
                'view_patient_medical_history',
                'view_patient_visit_history',

                // Visit Management
                'view_visits',
                'create_visits',
                'edit_visits',
                'complete_visits',
                'cancel_visits',
                'view_waiting_queue',
                'update_visit_status',
                'view_visit_vitals',

                // Prescription Management
                'view_prescriptions',
                'create_prescriptions',
                'edit_prescriptions',
                'dispense_prescriptions',
                'print_prescriptions',

                // Vitals Management
                'view_vitals',
                'record_vitals',
                'edit_vitals',

                // Medicine Management
                'view_medicines',
                'create_medicines',
                'edit_medicines',
                'delete_medicines',
                'view_inventory',
                'update_stock',
                'view_stock_alerts',
                'resolve_stock_alerts',
                'view_dispense_history',

                // Lab Management
                'view_lab_dashboard',
                'view_lab_reports',
                'create_lab_reports',
                'edit_lab_reports',
                'delete_lab_reports',
                'update_lab_report_status',
                'submit_lab_results',
                'verify_lab_reports',
                'print_lab_reports',
                'download_lab_pdf',
                'export_lab_reports',
                'view_lab_statistics',
                'notify_doctor_lab_results',

                // Consultation
                'view_consultations',
                'view_consultation_stats',

                // Diagnosis
                'view_diagnoses',

                // Reports
                'view_reports',
                'generate_reports',
                'download_reports',
                'view_pharmacy_reports',
                'view_consultation_reports',

                // Settings
                'view_settings',
                'update_settings',

                // Notifications
                'view_notifications',
                'mark_notifications_read',

                // API
                'access_api',
                'view_api_lab_reports',
            ])->get();
            $admin->permissions()->sync($adminPermissions);
        }

        // ============ DOCTOR ============
        if ($doctor) {
            $doctorPermissions = Permission::whereIn('name', [
                // Dashboard
                'view_dashboard',
                'view_analytics',
                'view_realtime_data',

                // Patient Management
                'view_patients',
                'create_patients',
                'edit_patients',
                'view_patient_medical_history',
                'view_patient_visit_history',

                // Visit Management
                'view_visits',
                'create_visits',
                'edit_visits',
                'complete_visits',
                'cancel_visits',
                'view_waiting_queue',
                'view_visit_vitals',

                // Consultation
                'view_consultations',
                'start_consultation',
                'complete_consultation',
                'cancel_consultation',
                'view_consultation_stats',
                'start_teleconsultation',

                // Prescription Management
                'view_prescriptions',
                'create_prescriptions',
                'edit_prescriptions',
                'print_prescriptions',

                // Vitals Management
                'view_vitals',
                'record_vitals',
                'edit_vitals',

                // Diagnosis
                'view_diagnoses',
                'create_diagnoses',
                'edit_diagnoses',

                // Lab Management
                'view_lab_reports',
                'view_lab_dashboard',
                'view_lab_statistics',

                // Reports
                'view_reports',
                'download_reports',
                'view_consultation_reports',

                // Notifications
                'view_notifications',
                'mark_notifications_read',

                // API
                'access_api',
            ])->get();
            $doctor->permissions()->sync($doctorPermissions);
        }

        // ============ PHARMACY ============
        if ($pharmacy) {
            $pharmacyPermissions = Permission::whereIn('name', [
                // Dashboard
                'view_dashboard',

                // Patient Management (limited)
                'view_patients',

                // Medicine Management
                'view_medicines',
                'create_medicines',
                'edit_medicines',
                'view_inventory',
                'update_stock',
                'view_stock_alerts',
                'resolve_stock_alerts',
                'view_dispense_history',

                // Prescription Management
                'view_prescriptions',
                'dispense_prescriptions',
                'print_prescriptions',

                // Reports
                'view_reports',
                'view_pharmacy_reports',

                // Notifications
                'view_notifications',
                'mark_notifications_read',

                // API
                'access_api',
            ])->get();
            $pharmacy->permissions()->sync($pharmacyPermissions);
        }

        // ============ RECEPTION ============
        if ($reception) {
            $receptionPermissions = Permission::whereIn('name', [
                // Dashboard
                'view_dashboard',

                // Patient Management
                'view_patients',
                'create_patients',
                'edit_patients',
                'export_patients',
                'bulk_upload_patients',
                'view_patient_medical_history',
                'view_patient_visit_history',

                // Visit Management
                'view_visits',
                'create_visits',
                'edit_visits',
                'view_waiting_queue',
                'update_visit_status',
                'view_visit_vitals',

                // Notifications
                'view_notifications',
                'mark_notifications_read',

                // API
                'access_api',
            ])->get();
            $reception->permissions()->sync($receptionPermissions);
        }

        // ============ NURSE ============
        if ($nurse) {
            $nursePermissions = Permission::whereIn('name', [
                // Dashboard
                'view_dashboard',

                // Patient Management
                'view_patients',
                'edit_patients',
                'view_patient_medical_history',

                // Visit Management
                'view_visits',
                'edit_visits',
                'view_waiting_queue',
                'view_visit_vitals',

                // Vitals Management
                'view_vitals',
                'record_vitals',
                'edit_vitals',

                // Notifications
                'view_notifications',
                'mark_notifications_read',

                // API
                'access_api',
            ])->get();
            $nurse->permissions()->sync($nursePermissions);
        }

        // ============ LAB ============
        if ($lab) {
            $labPermissions = Permission::whereIn('name', [
                // Dashboard
                'view_dashboard',
                'view_lab_dashboard',

                // Patient Management (limited)
                'view_patients',

                // Visit Management (limited)
                'view_visits',

                // Lab Management - Full
                'view_lab_reports',
                'create_lab_reports',
                'edit_lab_reports',
                'update_lab_report_status',
                'submit_lab_results',
                'verify_lab_reports',
                'print_lab_reports',
                'download_lab_pdf',
                'export_lab_reports',
                'view_lab_statistics',
                'notify_doctor_lab_results',

                // Reports
                'view_reports',

                // Notifications
                'view_notifications',
                'mark_notifications_read',

                // API
                'access_api',
                'view_api_lab_reports',
            ])->get();
            $lab->permissions()->sync($labPermissions);
        }

        $this->command->info('✅ Role and permissions seeded successfully!');
    }
}
