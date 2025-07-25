<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->id,
            'title'   => $this->title,
            'body' => $this->body,
            'images'  => $this->images->map(function ($image) {
                return asset('storage/' . $image->url);
            }),
            'user'    => [
                'id'    => $this->user->id,
                'name'  => $this->user->name,
                'email' => $this->user->email,
            ],
            'likes_count' => $this->likes->count(),
            'liked_by_users' => $this->likes->map(function ($like) {
                return [
                    'id'   => $like->user->id,
                    'name' => $like->user->name,
                ];
            }),
            'comments' => $this->comments->map(function ($comment) {
                return [
                    'id'      => $comment->id,
                    'body' => $comment->body,
                    'user'    => [
                        'id'    => $comment->user->id,
                        'name'  => $comment->user->name,
                        'email' => $comment->user->email,
                    ],
                    'created_at' => $comment->created_at->toDateTimeString(),
                ];
            }),
        ];
    }
}
