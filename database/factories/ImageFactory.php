<?php

namespace Database\Factories;
use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */

use Illuminate\Support\Facades\Storage;

class ImageFactory extends Factory
{
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
        $filename = 'fake_' . Str::random(10) . '.jpg';

        // Ruta relativa (en storage/app/public/posts)
        $relativePath = 'posts/' . $filename;

        // Ruta absoluta (en disco local)
        $fullPath = storage_path('app/public/' . $relativePath);

        // Asegurarse de que el directorio exista
        $directory = dirname($fullPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Crear la imagen con fondo gris y texto
        $randomColor = sprintf("#%06x", mt_rand(0, 0xFFFFFF));
        $image = $manager->create(640, 480)->fill($randomColor);

        // Guardar la imagen en el storage público
        $image->save($fullPath, quality: 80);

        return [
            'url' => $relativePath,
        ];
    }
}
