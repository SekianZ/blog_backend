<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Like;

class LikeService
{
    /**
     * Obtener recursos paginados.
     */
    public function getAllPaginatedWithPost(int $perPage = 10, int $postid)
    {
        return Like::with('user')
        ->where('post_id', $postid)
        ->latest()
        ->paginate($perPage);
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
    public function alternate(array $data, $post_id)
    {
        $userId = Auth::id();

        // Verificar si ya existe un like de este usuario para este post
        $existingLike = Like::where('user_id', $userId)
                            ->where('post_id', $post_id)
                            ->first();

        if ($existingLike) {
            // Ya le dio like: lo quitamos
            $existingLike->delete();
            return [
                'liked' => false,
                'like' => null,
            ];
        } else {
            // No le dio like: lo agregamos
            $like = new Like($data);
            $like->user_id = $userId;
            $like->post_id = $post_id;
            $like->save();

            return [
                'liked' => true,
                'like' => $like,
            ];
        }
    }

    /**
     * Actualizar un recurso existente.
     */
    public function update($model, array $data)
    {
        // $model->fill($data);
        // $model->save();
        // return $model;
    }

    /**
     * Eliminar un recurso.
     */
    public function delete($model)
    {
        // $model->delete();
    }
}