<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Arr;
class ProfileService
{
    /**
     * Obtener recursos paginados.
     */
    public function getAllPaginated(int $perPage = 10)
    {
        // return Model::with(...)->latest()->paginate($perPage);
    }

    /**
     * Buscar recursos por término.
     */
    public function search(string $query, int $perPage = 10)
    {
        // return Model::where(...)->paginate($perPage);
    }

    /**
     * Crear un nuevo recurso.
     */
    public function create(array $data)
    {
        // $model = new Model($data);
        // $model->user_id = Auth::id();
        // $model->save();
        // return $model;
    }

    /**
     * Actualizar un recurso existente.
     */
    public function update(User $model, array $data)
    {
        $allowedFields = ['username', 'email', 'cellphone', 'sex'];
        $filteredData = array_intersect_key($data, array_flip($allowedFields));

        $model->fill($filteredData);
        $model->save();

        return $model; // ← esto es lo importante
    }
    /**
     * Eliminar un recurso.
     */
    public function delete(User $user)
    {
        if (Auth::id() !== $user->id) {
            abort(403, 'No puedes eliminar este perfil');
        }

        // 1. Eliminar todos los posts del usuario y sus imágenes
        foreach ($user->posts as $post) {
            $this->deleteAllPostImages($post);
            $post->delete();
        }

        // 2. Eliminar imagen de perfil si existe
        if ($user->profile_photo) {
            $this->deleteImage($user->profile_photo);
        }

        // 3. Revocar todos los tokens del usuario (Sanctum)
        $user->tokens()->delete();

        // 4. Eliminar el usuario (esto eliminará automáticamente comments y likes por foreign key cascade)
        return $user->delete();
    }

    /**
     * Guardar imagen en el storage.
     */
    public function updateProfileImage(User $user, array $data): User
    {
        // Eliminar imagen anterior si existe
        if ($user->profile_photo) {
            $this->deleteImage($user->profile_photo);
        }

        // Guardar nueva imagen si se envió
        if (isset($data['profile_image'])) {
            $imagePath = $this->storeImage($data['profile_image']);
            $user->profile_photo = $imagePath;
        }

        $user->save();
        return $user;
    }

    /**
     * Guardar imagen de perfil en storage.
     */
    protected function storeImage($image): string
    {
        $name = Str::uuid() . '.' . $image->getClientOriginalExtension();
        return $image->storeAs('profile', $name, 'public');
    }

    /**
     * Eliminar imagen del storage.
     */
    protected function deleteImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    protected function deleteAllPostImages($post): void
    {
        foreach ($post->images as $image) {
            $this->deletePostImage($image->url);
            $image->delete();
        }
    }
        protected function deletePostImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}