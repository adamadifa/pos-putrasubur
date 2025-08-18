<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $companyTypes = ['PT', 'CV', 'UD', 'Firma'];
        $companyType = $this->faker->randomElement($companyTypes);

        return [
            'kode_supplier' => 'SUP' . $this->faker->unique()->numberBetween(1000, 9999),
            'nama' => $companyType . ' ' . $this->faker->company(),
            'alamat' => $this->faker->address(),
            'telepon' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail(),
            'keterangan' => $this->faker->optional()->sentence(),
            'status' => $this->faker->boolean(80), // 80% chance of being active
        ];
    }

    /**
     * Indicate that the supplier is active.
     */
    public function aktif(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => true,
        ]);
    }

    /**
     * Indicate that the supplier is inactive.
     */
    public function nonaktif(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => false,
        ]);
    }
}
