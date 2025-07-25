<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear un único usuario si no existe
        $user = User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password'),
        ]);

        // Crear 15 posts asociados al usuario
        $posts = Post::factory()->count(15)->create(['user_id' => $user->id]);

        // Para cada post, crear de 1 a 3 imágenes asociadas
        foreach ($posts as $post) {
            Image::factory()
                ->count(rand(1, 3))
                ->create(['post_id' => $post->id]);
        }
    }
}
