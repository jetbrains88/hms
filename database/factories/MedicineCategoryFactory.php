<?php

namespace Database\Factories;

use App\Models\MedicineCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicineCategoryFactory extends Factory
{
    protected $model = MedicineCategory::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement([
            'Analgesics', 'Antibiotics', 'Antipyretics', 'Antihistamines',
            'Antacids', 'Antidepressants', 'Antidiabetics', 'Antihypertensives',
            'Cardiovascular', 'Dermatologicals', 'Gastrointestinal', 'Hormones',
            'Muscle Relaxants', 'Neurological', 'Ophthalmic', 'Respiratory',
            'Vitamins & Supplements', 'Pain Management', 'Infections',
            'Allergy & Sinus', 'Mental Health', 'Diabetes Care',
            'Heart & Blood Pressure', 'Skin Care', 'Digestive Health',
            'Women\'s Health', 'Men\'s Health', 'Children\'s Medicine',
            'First Aid', 'Herbal & Natural'
        ]);

        return [
            'name' => $name,
            'slug' => \Str::slug($name),
            'description' => $this->faker->paragraph(),
            'display_order' => $this->faker->numberBetween(1, 100),
            'is_active' => $this->faker->boolean(90), // 90% active
        ];
    }
}
