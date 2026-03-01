<?php

namespace Database\Factories;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\MedicineCategory;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MedicineFactory extends Factory
{
    protected $model = Medicine::class;

    protected static array $stockStorage = [];

    public function definition(): array
    {
        $medicineNames = [
            'Paracetamol',
            'Ibuprofen',
            'Aspirin',
            'Naproxen',
            'Diclofenac',
            'Acetaminophen',
            'Celecoxib',
            'Tramadol',
            'Codeine',
            'Morphine',
            'Amoxicillin',
            'Azithromycin',
            'Ciprofloxacin',
            'Doxycycline',
            'Erythromycin',
            'Penicillin',
            'Cephalexin',
            'Clarithromycin',
            'Metronidazole',
            'Vancomycin',
            'Cetirizine',
            'Loratadine',
            'Fexofenadine',
            'Diphenhydramine',
            'Chlorpheniramine',
            'Desloratadine',
            'Levocetirizine',
            'Bilastine',
            'Omeprazole',
            'Pantoprazole',
            'Ranitidine',
            'Famotidine',
            'Loperamide',
            'Domperidone',
            'Metoclopramide',
            'Simethicone',
            'Amlodipine',
            'Atorvastatin',
            'Metoprolol',
            'Losartan',
            'Valsartan',
            'Hydrochlorothiazide',
            'Furosemide',
            'Warfarin',
            'Salbutamol',
            'Montelukast',
            'Fluticasone',
            'Budesonide',
            'Theophylline',
            'Ipratropium',
            'Formoterol',
            'Salmeterol',
            'Vitamin C',
            'Vitamin D',
            'Vitamin B Complex',
            'Calcium',
            'Iron Supplement',
            'Magnesium',
            'Zinc',
            'Omega-3',
            'Metformin',
            'Insulin',
            'Levothyroxine',
            'Prednisone',
            'Diazepam',
            'Fluoxetine',
            'Sertraline',
            'Gabapentin',
        ];

        $brands = [
            'Pfizer',
            'GlaxoSmithKline',
            'Novartis',
            'Roche',
            'Merck',
            'Johnson & Johnson',
            'Sanofi',
            'AbbVie',
            'Bayer',
            'AstraZeneca',
            'Bristol-Myers Squibb',
            'Eli Lilly',
            'Amgen',
            'Gilead',
            'Teva',
            'Mylan',
            'Sun Pharma',
            'Dr. Reddy\'s',
            'Cipla',
            'Lupin',
            'Aurobindo',
            'Torrent',
            'Zydus',
            'Cadila',
        ];

        $forms = ['tablet', 'capsule', 'syrup', 'injection', 'ointment', 'cream', 'drops', 'inhaler', 'powder', 'gel'];
        $units = ['pcs', 'tablets', 'capsules', 'ml', 'mg', 'g', 'tubes', 'bottles', 'vials', 'ampoules'];
        $strengths = ['100mg', '250mg', '500mg', '1g', '5mg', '10mg', '20mg', '50mg', '100ml', '200ml', '500ml'];

        $name = $this->faker->randomElement($medicineNames);
        $brand = $this->faker->randomElement($brands);
        $form = $this->faker->randomElement($forms);
        $strength = $this->faker->randomElement($strengths);
        $unit = $this->faker->randomElement($units);

        $category = MedicineCategory::inRandomOrder()->first();
        if (!$category) {
            $category = MedicineCategory::factory()->create();
        }

        $form = \App\Models\MedicineForm::inRandomOrder()->first();

        return [
            'uuid' => Str::uuid(),
            'name' => $name,
            'generic_name' => $name,
            'brand' => $brand,
            'manufacturer' => $brand,
            'form_id' => $form?->id,
            'strength_value' => $this->extractStrengthValue($strength),
            'strength_unit' => $this->extractStrengthUnit($strength),
            'unit' => $unit,
            'category_id' => $category->id,
            'description' => $this->faker->paragraph(),
            'reorder_level' => $this->faker->numberBetween(10, 500),
            'is_active' => $this->faker->boolean(85),
            'requires_prescription' => $this->faker->boolean(60),
            'branch_id' => null,
            'is_global' => true,
        ];
    }

    public function configure(): static
    {
        return $this
            ->afterMaking(function (Medicine $medicine) {
                if ($medicine->offsetExists('_stock')) {
                    $stockValue = $medicine->getAttribute('_stock');
                    $key = (string) $medicine->uuid; // Ensure string for array key
                    self::$stockStorage[$key] = $stockValue;
                    $medicine->offsetUnset('_stock');
                }
            })
            ->afterCreating(function (Medicine $medicine) {
                $branchId = $GLOBALS['current_branch_id'] ?? Branch::inRandomOrder()->first()->id;
                $key = (string) $medicine->uuid;
                $stock = self::$stockStorage[$key] ?? $this->faker->numberBetween(0, 5000);

                // Clean up
                unset(self::$stockStorage[$key]);

                MedicineBatch::create([
                    'uuid' => Str::uuid(),
                    'branch_id' => $branchId,
                    'medicine_id' => $medicine->id,
                    'batch_number' => 'BATCH-' . strtoupper($this->faker->bothify('??####')) . '-' . $medicine->id,
                    'rc_number' => 'RC-' . strtoupper($this->faker->bothify('##??##')),
                    'expiry_date' => $this->faker->dateTimeBetween('+6 months', '+3 years')->format('Y-m-d'),
                    'unit_price' => $this->faker->randomFloat(2, 0.5, 50),
                    'sale_price' => $this->faker->randomFloat(2, 1, 100),
                    'remaining_quantity' => $stock,
                    'is_active' => $stock > 0,
                ]);
            });
    }

    private function extractStrengthValue(string $strength): ?float
    {
        preg_match('/^(\d+(?:\.\d+)?)/', $strength, $matches);
        return isset($matches[1]) ? (float) $matches[1] : null;
    }

    private function extractStrengthUnit(string $strength): ?string
    {
        preg_match('/[a-zA-Z]+$/', $strength, $matches);
        return $matches[0] ?? null;
    }

    public function lowStock(): static
    {
        return $this->state(function () {
            return ['_stock' => $this->faker->numberBetween(1, 50)];
        })->afterMaking(function (Medicine $medicine) {
            $medicine->reorder_level = $this->faker->numberBetween(50, 100);
        });
    }

    public function outOfStock(): static
    {
        return $this->state(function () {
            return ['_stock' => 0];
        });
    }

    public function expiringSoon(): static
    {
        return $this->afterCreating(function (Medicine $medicine) {
            $batch = $medicine->batches()->first();
            if ($batch) {
                $batch->update([
                    'expiry_date' => $this->faker->dateTimeBetween('+1 week', '+1 month')->format('Y-m-d'),
                ]);
            }
        });
    }
}
