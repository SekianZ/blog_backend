<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CommentService
{
    /**
     * Obtener recursos paginados.
     */
    public function getAllPaginated(int $perPage = 10)
    {
        return Comment::latest()->paginate($perPage);
    }

    public function getCommentsPost(int $post_id,int $perPage=10){
        return Comment::with('user')
        ->where('post_id', $post_id)
        ->latest()
        ->paginate($perPage);
    }

    /**
     * Buscar recursos por término.
     */
    public function search(string $query, int $perPage = 10)
    {
        return Comment::where('post_id', $query)->paginate($perPage);
    }

    /**
     * Crear un nuevo recurso.
     */
    public function create(array $data)
    {
        $Comment = new Comment($data);
        $Comment->user_id = Auth::id();
        $Comment->save();
        return $Comment;
    }

    /**
     * Actualizar un recurso existente.
     */
    public function update($Comment, array $data)
    {
        $Comment->fill($data);
        $Comment->save();
        return $Comment;
    }

    /**
     * Eliminar un recurso.
     */
    public function delete($Comment)
    {
        $Comment->delete();
    }

}