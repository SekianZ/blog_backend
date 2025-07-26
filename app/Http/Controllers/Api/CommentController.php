<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Comment\StoreCommentRequest;
use App\Http\Requests\Api\Comment\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10); // 15 es el valor por defecto si no se envía 'per_page'
        $comments = $this->commentService->getAllPaginated($perPage);
        return CommentResource::collection($comments)->additional([
            'message' => 'Lista de comentarios obtenida correctamente',
        ]);
    }


    public function getCommentsPost(Post $post, Request $request)
    {
        $post_id=$post->id;
        $perPage = $request->query('per_page', 10); // 15 es el valor por defecto si no se envía 'per_page'
        $comments= $this->commentService->getCommentsPost($post_id,$perPage);
        return CommentResource::collection($comments)->additional([
            'message' => 'Lista de comentarios obtenida correctamente',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        $comment = $this->commentService->create($request->validated());
        return response()->json([
            'message' => 'Comentario creado correctamente',
            'data' => new CommentResource($comment),
        ],201);
    }

    /**
     * Display the specified resource.
     */ 
    public function show(Comment $comment)
    {
        return response()->json([
            'message' => 'Comentario obtenido correctamente',
            'data' => new CommentResource($comment),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $updated = $this->commentService->update($comment, $request->validated());
        return response()->json([
            'message' => 'Comentario actualizado correctamente',
            'data' => new CommentResource($updated),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json([
            'message' => 'Comentario eliminado correctamente'
        ]);
    }
}
