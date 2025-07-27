<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Like\StoreLikeRequest;
use App\Services\LikeService;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use App\Http\Resources\LikeResource;

class LikeController extends Controller
{
    protected $likeService;

    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Post $post,Request $request)
    {
        $perPage = $request->query('per_page', 10); // 15 es el valor por defecto si no se envía 'per_page'
        $likes = $this->likeService->getAllPaginatedWithPost($perPage,$post->id);
        return LikeResource::collection($likes)->additional([
            'message' => 'Lista de likes por post obtenida correctamente',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLikeRequest $request)
    {
        $like = $this->likeService->create($request->validated());
        return response()->json([
            'data' => new LikeResource($like),
            'message' => 'Like creado correctamente',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Like $like)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Like $like)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Like $like)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Like $like)
    {
        //
    }
}
