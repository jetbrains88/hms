<?php

namespace Database\Seeders;

use App\Models\Designation;
use App\Models\Office;
use App\Models\Patient;
use App\Models\Branch;
use App\Models\EmployeeDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        // Get the CMO branch
        $cmoBranch = Branch::where('type', 'CMO')->first();
        if (!$cmoBranch) {
            $this->command->error('CMO branch not found! Please run BranchSeeder first.');
            return;
        }

        // Get all offices and designations
        $offices = Office::all()->keyBy('id');
        $designations = Designation::all()->keyBy('id');

        // Categorize designations by cadre type
        $uniformDesignations = $designations->filter(function ($designation) {
            return $designation->cadre_type === 'Uniform';
        })->values();

        $nonUniformDesignations = $designations->filter(function ($designation) {
            return $designation->cadre_type === 'Non-Uniform';
        })->values();

        // If no designations found, create some default ones with UUIDs
        if ($uniformDesignations->isEmpty() && $nonUniformDesignations->isEmpty()) {
            $this->command->warn('No designations found. Creating default designations...');

            $defaultDesignations = [
                ['title' => 'Inspector General', 'short_form' => 'IG', 'bps' => 22, 'cadre_type' => 'Uniform'],
                ['title' => 'Deputy Inspector General', 'short_form' => 'DIG', 'bps' => 20, 'cadre_type' => 'Uniform'],
                ['title' => 'Superintendent of Police', 'short_form' => 'SP', 'bps' => 18, 'cadre_type' => 'Uniform'],
                ['title' => 'Doctor', 'short_form' => 'Dr', 'bps' => 18, 'cadre_type' => 'Non-Uniform'],
                ['title' => 'Assistant', 'short_form' => 'Asst', 'bps' => 14, 'cadre_type' => 'Non-Uniform'],
                ['title' => 'Driver', 'short_form' => 'DRV', 'bps' => 7, 'cadre_type' => 'Non-Uniform'],
            ];

            foreach ($defaultDesignations as $designation) {
                Designation::firstOrCreate(
                    ['title' => $designation['title']],
                    array_merge($designation, [
                        'uuid' => (string) Str::uuid(),
                    ])
                );
            }

            // Refresh collections
            $designations = Designation::all()->keyBy('id');
            $uniformDesignations = $designations->filter(function ($d) {
                return $d->cadre_type === 'Uniform';
            })->values();
            $nonUniformDesignations = $designations->filter(function ($d) {
                return $d->cadre_type === 'Non-Uniform';
            })->values();
        }

        $firstNames = [
            'male' => [
                'Ahmed',
                'Ali',
                'Bilal',
                'Danish',
                'Faizan',
                'Hamza',
                'Imran',
                'Junaid',
                'Kamran',
                'Luqman',
                'Muhammad',
                'Noman',
                'Omar',
                'Qasim',
                'Raza',
                'Saad',
                'Tariq',
                'Usman',
                'Waqar',
                'Zain'
            ],
            'female' => [
                'Ayesha',
                'Fatima',
                'Hina',
                'Iqra',
                'Javeria',
                'Kiran',
                'Laiba',
                'Mahnoor',
                'Nida',
                'Rida',
                'Saba',
                'Sara',
                'Tuba',
                'Uzma',
                'Warda',
                'Zainab',
                'Zara',
                'Zoya',
                'Amina',
                'Sadia'
            ]
        ];

        $lastNames = [
            'Khan',
            'Ahmed',
            'Malik',
            'Ali',
            'Hussain',
            'Akhtar',
            'Raza',
            'Iqbal',
            'Butt',
            'Chaudhry',
            'Shah',
            'Mirza',
            'Farooqi',
            'Hashmi',
            'Siddiqui',
            'Sheikh',
            'Abbasi',
            'Bukhari',
            'Gardezi',
            'Qureshi'
        ];

        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

        $cities = [
            'Islamabad',
            'Rawalpindi',
            'Lahore',
            'Karachi',
            'Faisalabad',
            'Multan',
            'Quetta',
            'Peshawar',
            'Sialkot',
            'Gujranwala',
            'Sargodha',
            'Bahawalpur',
            'Sukkur',
            'Hyderabad',
            'Abbottabad'
        ];

        $sectors = ['F-6', 'F-7', 'F-8', 'F-10', 'F-11', 'G-6', 'G-7', 'G-8', 'G-9', 'G-10', 'G-11', 'H-8', 'I-8'];

        $chronicConditions = [
            'Hypertension',
            'Type 2 Diabetes',
            'Asthma',
            'Heart Disease',
            'Arthritis',
            'Thyroid Disorder',
            'Hepatitis B',
            'Hepatitis C',
            'COPD',
            'Chronic Kidney Disease',
            'Migraine',
            'Epilepsy',
            'Depression',
            'Anxiety Disorder',
            'Osteoporosis'
        ];

        $allergies = [
            'Penicillin',
            'Sulfa drugs',
            'Aspirin',
            'Ibuprofen',
            'Peanuts',
            'Shellfish',
            'Dairy products',
            'Eggs',
            'Pollen',
            'Dust mites',
            'Latex',
            'Bee stings'
        ];

        // Original patients with NHMP flag and employee details
        $originalPatients = [
            [
                'uuid' => (string) Str::uuid(),
                'branch_id' => $cmoBranch->id,
                'cnic' => '3520112345671',
                'emrn' => 'EMRN-000001',
                'name' => 'Ali Ahmed',
                'dob' => '1985-06-15',
                'gender' => 'male',
                'phone' => '03001234567',
                'address' => 'House 123, Street 45, Islamabad',
                'blood_group' => 'B+',
                'allergies' => 'Penicillin, Sulfa drugs',
                'chronic_conditions' => 'Asthma',
                'medical_history' => 'Allergic to antibiotics',
                'is_nhmp' => true,
                'designation_id' => $uniformDesignations->isNotEmpty() ? $uniformDesignations->first()->id : null,
                'office_id' => $offices->isNotEmpty() ? $offices->first()->id : null,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'branch_id' => $cmoBranch->id,
                'cnic' => '3520276543212',
                'emrn' => 'EMRN-000002',
                'name' => 'Fatima Khan',
                'dob' => '1990-03-22',
                'gender' => 'female',
                'phone' => '03007654321',
                'address' => 'Sector F-10, Islamabad',
                'blood_group' => 'O+',
                'chronic_conditions' => 'Hypertension, Type 2 Diabetes',
                'medical_history' => 'Diabetic since 2015',
                'is_nhmp' => false,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'branch_id' => $cmoBranch->id,
                'cnic' => '3520398765433',
                'emrn' => 'EMRN-000003',
                'name' => 'Bilal Raza',
                'dob' => '1978-11-08',
                'gender' => 'male',
                'phone' => '03009876543',
                'address' => 'DHA Phase 2, Rawalpindi',
                'blood_group' => 'A+',
                'medical_history' => 'Appendectomy in 2015',
                'is_nhmp' => true,
                'designation_id' => $uniformDesignations->count() > 1 ? $uniformDesignations->get(1)->id : ($uniformDesignations->isNotEmpty() ? $uniformDesignations->first()->id : null),
                'office_id' => $offices->count() > 1 ? $offices->get(1)->id : ($offices->isNotEmpty() ? $offices->first()->id : null),
            ],
            [
                'uuid' => (string) Str::uuid(),
                'branch_id' => $cmoBranch->id,
                'cnic' => '3520412345678',
                'emrn' => 'EMRN-000004',
                'name' => 'Sara Javed',
                'dob' => '1995-08-30',
                'gender' => 'female',
                'phone' => '03001122334',
                'address' => 'Gulberg, Lahore',
                'blood_group' => 'AB+',
                'allergies' => 'Peanuts',
                'is_nhmp' => false,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'branch_id' => $cmoBranch->id,
                'cnic' => '3520523456789',
                'emrn' => 'EMRN-000005',
                'name' => 'Usman Malik',
                'dob' => '1980-12-12',
                'gender' => 'male',
                'phone' => '03002233445',
                'address' => 'Cantt, Karachi',
                'blood_group' => 'O-',
                'chronic_conditions' => 'Heart Disease',
                'medical_history' => 'Heart surgery in 2020',
                'is_nhmp' => true,
                'designation_id' => $nonUniformDesignations->isNotEmpty() ? $nonUniformDesignations->first()->id : null,
                'office_id' => $offices->count() > 2 ? $offices->get(2)->id : ($offices->isNotEmpty() ? $offices->first()->id : null),
            ],
        ];

        // Start EMRN from 6
        $emrnCounter = 6;
        $generatedPatients = [];

        for ($i = 1; $i <= 100; $i++) {
            $gender = rand(0, 1) ? 'male' : 'female';
            $firstName = $firstNames[$gender][array_rand($firstNames[$gender])];
            $lastName = $lastNames[array_rand($lastNames)];
            $name = $firstName . ' ' . $lastName;

            // Generate CNIC
            $firstPart = str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $secondPart = str_pad(rand(1, 9999999), 7, '0', STR_PAD_LEFT);
            $checksum = rand(0, 9);
            $cnic = $firstPart . $secondPart . $checksum;
            if (substr($cnic, 0, 1) == '0') {
                $cnic = rand(1, 4) . substr($cnic, 1);
            }

            // Generate date of birth
            $year = rand(1954, 2006);
            $month = rand(1, 12);
            $day = rand(1, 28);
            $dob = sprintf('%04d-%02d-%02d', $year, $month, $day);

            // Phone number
            $phone = '03' . rand(0, 9) . rand(0, 9) . rand(10000000, 99999999);

            // Address
            $city = $cities[array_rand($cities)];
            if ($city == 'Islamabad') {
                $address = 'House ' . rand(1, 500) . ', Street ' . rand(1, 50) . ', ' . $sectors[array_rand($sectors)] . ', ' . $city;
            } else {
                $address = 'House ' . rand(1, 500) . ', Street ' . rand(1, 50) . ', ' . ['Gulberg', 'Model Town', 'Cantt', 'DHA', 'Saddar', 'Township'][array_rand(['Gulberg', 'Model Town', 'Cantt', 'DHA', 'Saddar', 'Township'])] . ', ' . $city;
            }

            // Determine if NHMP patient (about 60% chance)
            $isNhmp = rand(1, 100) <= 60;

            $patient = [
                'uuid' => (string) Str::uuid(),
                'branch_id' => $cmoBranch->id,
                'cnic' => $cnic,
                'emrn' => 'EMRN-' . str_pad($emrnCounter++, 6, '0', STR_PAD_LEFT),
                'name' => $name,
                'dob' => $dob,
                'gender' => $gender,
                'phone' => $phone,
                'address' => $address,
                'blood_group' => $bloodGroups[array_rand($bloodGroups)],
                // NHMP-related fields will be stored separately
                'is_nhmp' => $isNhmp,
            ];

            // Optional fields
            if (rand(1, 100) <= 30) {
                $allergyCount = rand(1, 3);
                $allergyKeys = (array) array_rand(array_flip($allergies), min($allergyCount, count($allergies)));
                $patient['allergies'] = implode(', ', $allergyKeys);
            }
            if (rand(1, 100) <= 25) {
                $conditionCount = rand(1, 2);
                $conditionKeys = (array) array_rand(array_flip($chronicConditions), min($conditionCount, count($chronicConditions)));
                $patient['chronic_conditions'] = implode(', ', $conditionKeys);
            }
            if (rand(1, 100) <= 20) {
                $medicalEvents = ['surgery', 'hospitalization', 'major illness', 'accident'];
                $yearsAgo = rand(1, 20);
                $event = $medicalEvents[array_rand($medicalEvents)];
                $patient['medical_history'] = ucfirst($event) . ' in ' . (date('Y') - $yearsAgo);
            }

            // For NHMP patients, pick random designation/office (if available)
            if ($isNhmp) {
                // Safely pick from collections, fallback to null if empty
                $isUniform = rand(0, 1);
                if ($isUniform && $uniformDesignations->isNotEmpty()) {
                    $designation = $uniformDesignations->random();
                } elseif ($nonUniformDesignations->isNotEmpty()) {
                    $designation = $nonUniformDesignations->random();
                } else {
                    $designation = null;
                }
                $patient['designation'] = $designation;
                $patient['office'] = $offices->isNotEmpty() ? $offices->random() : null;
            }

            // Ensure unique CNIC
            $allExistingCnics = collect($originalPatients)->pluck('cnic')->toArray();
            $allExistingCnics = array_merge($allExistingCnics, collect($generatedPatients)->pluck('cnic')->toArray());

            $originalCnic = $cnic;
            while (in_array($cnic, $allExistingCnics)) {
                $firstPart = str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
                $secondPart = str_pad(rand(1, 9999999), 7, '0', STR_PAD_LEFT);
                $checksum = rand(0, 9);
                $cnic = $firstPart . $secondPart . $checksum;
                if (substr($cnic, 0, 1) == '0') {
                    $cnic = rand(1, 4) . substr($cnic, 1);
                }
            }
            if ($cnic != $originalCnic) {
                $patient['cnic'] = $cnic;
            }

            $generatedPatients[] = $patient;
        }

        $allPatients = array_merge($originalPatients, $generatedPatients);

        \DB::beginTransaction();

        try {
            foreach ($allPatients as $patientData) {
                // Extract NHMP related fields and remove from patient array
                $isNhmp = $patientData['is_nhmp'] ?? false;
                $designationId = $patientData['designation_id'] ?? ($patientData['designation']->id ?? null);
                $officeId = $patientData['office_id'] ?? ($patientData['office']->id ?? null);
                $rank = $patientData['rank'] ?? null;

                // Remove fields that are not in patients table
                unset($patientData['is_nhmp'], $patientData['designation_id'], $patientData['office_id'], $patientData['rank'], $patientData['designation'], $patientData['office']);

                // Create patient
                $patient = Patient::updateOrCreate(
                    ['cnic' => $patientData['cnic']],
                    $patientData
                );

                // Create employee_details for NHMP patients
                if ($isNhmp) {
                    EmployeeDetail::updateOrCreate(
                        ['patient_id' => $patient->id],
                        [
                            'is_nhmp' => true,
                            'designation_id' => $designationId,
                            'office_id' => $officeId,
                            'rank' => $rank,
                        ]
                    );
                }
            }

            \DB::commit();

            $this->command->info('✅ Created ' . count($allPatients) . ' patients successfully!');
            $this->command->info('   - Original patients: ' . count($originalPatients));
            $this->command->info('   - Generated patients: ' . count($generatedPatients));

            $nhmpCount = EmployeeDetail::count();
            $this->command->info('   - NHMP Staff with employee details: ' . $nhmpCount);
        } catch (\Exception $e) {
            \DB::rollBack();
            $this->command->error('❌ Failed to seed patients: ' . $e->getMessage());
            throw $e;
        }
    }
}
