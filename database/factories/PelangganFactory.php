<?php

namespace Database\Factories;

use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pelanggan>
 */
class PelangganFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pelanggan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cities = ['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Semarang', 'Medan', 'Palembang', 'Makassar'];

        return [
            'kode_pelanggan' => 'P-' . str_pad(fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'nama' => fake()->name(),
            'nomor_telepon' => '08' . fake()->numberBetween(100000000, 999999999),
            'alamat' => fake()->streetAddress() . ', ' . fake()->randomElement($cities),
            'status' => fake()->boolean(80), // 80% chance of being active
        ];
    }

    /**
     * Indicate that the customer is active.
     */
    public function aktif(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => true,
        ]);
    }

    /**
     * Indicate that the customer is inactive.
     */
    public function nonaktif(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => false,
        ]);
    }
}
