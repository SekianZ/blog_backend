<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Post\SearchPostsRequest;
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

    public function search(SearchPostsRequest $request)
    {
        $validated = $request->validated();
        $perPage = $validated['per_page'] ?? 10;

        $posts = $this->postService->search($validated['text'], $perPage);

        return PostResource::collection($posts)->additional([
            'message' => 'Lista de publicaciones obtenida correctamente',
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'total' => $posts->total(),
                'per_page' => $posts->perPage(),
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

    public function getPostsByAuthenticatedUser(Request $request)
    {
        $perPage = $request->query('per_page', 5);
        $posts = $this->postService->getPostsByUser( auth()->id(),$perPage);

        if ($posts->isEmpty()) {
            return response()->json([
                'message' => 'No tienes publicaciones aún',
            ]);
        }

        return PostResource::collection($posts)->additional([
            'message' => 'Publicaciones obtenidas correctamente',
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'total' => $posts->total(),
                'per_page' => $posts->perPage()
            ]
        ]);
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
        $validated = $request->validated();

        if ($request->hasFile('images')) {
            $validated['images'] = $request->file('images');
        }

        $updated = $this->postService->update($post, $validated);

        return response()->json([
            'message' => 'Post actualizado correctamente',
            'data' => new PostResource($updated),
        ]);
    }

    public function destroy(Post $post)
    {  
        if ($post->user_id !== auth()->id()) {
            return response()->json(['message' => 'No tienes permiso para eliminar este post'], 403);
        }
        $this->postService->delete($post);

        return response()->json([
            'message' => 'Post eliminado correctamente'
        ]);
    }
}
