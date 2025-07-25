<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Services\PostService;
use App\Http\Requests\Api\Post\StorePostRequest;
use App\Http\Requests\Api\Post\UpdatePostRequest;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10); // 15 es el valor por defecto si no se envía 'per_page'

        $posts = $this->postService->getAllPaginated($perPage);

        return PostResource::collection($posts)->additional([
            'message' => 'Lista de publicaciones obtenida correctamente',
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'total' => $posts->total(),
                'per_page' => $posts->perPage()
            ]
        ]);
    }

    public function store(StorePostRequest $request)
    {
        $post = $this->postService->create($request->validated() + [
            'images' => $request->file('images'),
        ]);

        return response()->json([
            'message' => 'Post creado correctamente',
            'data' => new PostResource($post),
        ], 201);

    }

    public function show(Post $post)
    {
        return response()->json([
            'message' => 'Post obtenido correctamente',
            'data' => new PostResource($post)
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $updated = $this->postService->update($post, $request->validated() + [
            'images' => $request->file('images'),
        ]);

        return response()->json([
            'message' => 'Post actualizado correctamente',
            'data' => new PostResource($updated),
        ]);
    }

    public function destroy(Post $post)
    {
        $this->postService->delete($post);

        return response()->json([
            'message' => 'Post eliminado correctamente'
        ]);
    }
}
