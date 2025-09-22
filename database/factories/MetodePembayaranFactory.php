<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MetodePembayaran>
 */
class MetodePembayaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $metodePembayaran = [
            ['kode' => 'OVO', 'nama' => 'OVO', 'icon' => 'ti-device-mobile'],
            ['kode' => 'DANA', 'nama' => 'DANA', 'icon' => 'ti-device-mobile'],
            ['kode' => 'GOPAY', 'nama' => 'GoPay', 'icon' => 'ti-device-mobile'],
            ['kode' => 'SHOPEEPAY', 'nama' => 'ShopeePay', 'icon' => 'ti-device-mobile'],
            ['kode' => 'LINKAJA', 'nama' => 'LinkAja', 'icon' => 'ti-device-mobile'],
        ];

        $metode = $this->faker->randomElement($metodePembayaran);

        return [
            'kode' => $metode['kode'],
            'nama' => $metode['nama'],
            'deskripsi' => $this->faker->sentence(),
            'icon' => $metode['icon'],
            'status' => $this->faker->boolean(80), // 80% chance of being active
            'urutan' => $this->faker->numberBetween(1, 100),
        ];
    }

    /**
     * Indicate that the metode pembayaran is active.
     */
    public function aktif(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => true,
        ]);
    }

    /**
     * Indicate that the metode pembayaran is inactive.
     */
    public function nonaktif(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => false,
        ]);
    }
}
