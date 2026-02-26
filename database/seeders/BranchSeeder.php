<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Office;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        if (Branch::count() > 0) {
            $this->command->info('Branches already seeded.');
            return;
        }

        // Create CMO branch (main functional branch)
        $cmo = Branch::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'CMO - PLHQ CPO Islamabad',
            'type' => 'CMO',
            'location' => 'Sector G-8, Islamabad',
            'office_id' => 18, // "CMO HQs" from OfficeSeeder
            'is_active' => true,
        ]);
        $this->command->info('✅ Created CMO branch: ' . $cmo->name);

        // Get all zone offices
        $zoneOffices = Office::where('type', 'Zone')->get();

        if ($zoneOffices->isEmpty()) {
            $this->command->warn('No Zone offices found. Skipping RMO branches.');
            return;
        }

        $rmoCount = 0;
        foreach ($zoneOffices as $office) {
            Branch::create([
                'uuid' => (string) Str::uuid(),
                'name' => 'RMO - ' . $office->name,
                'type' => 'RMO',
                'location' => $office->name . ' (Zone)',
                'office_id' => $office->id,
                'is_active' => true,
            ]);
            $rmoCount++;
        }

        $this->command->info('✅ Created ' . $rmoCount . ' RMO branches (one per zone).');
    }
}
