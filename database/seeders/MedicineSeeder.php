<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\Branch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        // First, create some categories
        $categories = [
            ['name' => 'Analgesics', 'display_order' => 1],
            ['name' => 'Antibiotics', 'display_order' => 2],
            ['name' => 'Antipyretic', 'display_order' => 3],
            ['name' => 'Antihistamines', 'display_order' => 4],
            ['name' => 'Gastrointestinal', 'display_order' => 5],
            ['name' => 'Cardiovascular', 'display_order' => 6],
            ['name' => 'Respiratory', 'display_order' => 7],
            ['name' => 'Vitamins & Supplements', 'display_order' => 8],
            ['name' => 'Diabetes Care', 'display_order' => 9],
            ['name' => 'Dermatological', 'display_order' => 10],
        ];

        foreach ($categories as $category) {
            MedicineCategory::firstOrCreate(
                ['name' => $category['name']],
                [
                    'uuid' => Str::uuid(),
                    'slug' => Str::slug($category['name']),
                    'description' => 'Medicines for ' . $category['name'],
                    'display_order' => $category['display_order'],
                    'is_active' => true,
                ]
            );
        }

        // Get a branch ID to use for medicine batches
        $branchId = Branch::first()->id;

        // Now create medicines
        $this->command->info('Creating medicines...');

        $batchSize = 10;
        $totalMedicines = 100;

        for ($i = 0; $i < $totalMedicines; $i += $batchSize) {
            $currentBatch = min($batchSize, $totalMedicines - $i);

            // Create medicines in batches - we need to set a static property or use a different approach
            // Set a global static variable that the factory can access
            $GLOBALS['current_branch_id'] = $branchId;

            Medicine::factory()->count($currentBatch)->create();

            $this->command->info("Created " . ($i + $currentBatch) . " of $totalMedicines medicines...");
        }

        // Create some specific scenario medicines
        Medicine::factory()->count(50)->lowStock()->create();
        Medicine::factory()->count(20)->outOfStock()->create();
        Medicine::factory()->count(30)->expiringSoon()->create();

        // Clean up
        unset($GLOBALS['current_branch_id']);

        $this->command->info('Successfully seeded ' . Medicine::count() . ' medicines with ' . MedicineCategory::count() . ' categories.');
    }
}
