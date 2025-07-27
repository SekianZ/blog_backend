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
    public function create(array $data)
    {
        $Like = new Like($data);
        $Like->user_id = Auth::id();
        $Like->save();
        return $Like;
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