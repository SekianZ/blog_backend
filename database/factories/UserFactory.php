<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Crear el manager con el driver gd
        $manager = new ImageManager(new Driver);

        // Nombre único para la imagen
        $filename = 'profile_' . Str::random(10) . '.jpg';
        $relativePath = 'profiles/' . $filename;
        $fullPath = storage_path('app/public/' . $relativePath);

        // Crear carpeta si no existe
        $directory = dirname($fullPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Crear imagen avatar
        $color = sprintf("#%06x", mt_rand(0, 0xFFFFFF));
        $avatar = $manager->create(200, 200)->fill($color);

        // Guardar imagen
        $avatar->save($fullPath, quality: 80);
        return [
            'username' => $this->faker->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'profile_photo' => 'storage/' . $relativePath, // <- aquí
            'cellphone' => $this->faker->phoneNumber(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
