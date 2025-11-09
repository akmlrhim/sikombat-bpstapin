<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mitra>
 */
class MitraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'nama_lengkap' => fake()->name(),
            'nms' => fake()->randomNumber(8),
            'jenis_kelamin' => fake()->randomElement(['Laki-Laki', 'Perempuan']),
            'alamat' => fake()->streetAddress()
        ];
    }
}
