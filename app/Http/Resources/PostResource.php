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
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'images' => $this->images->map(fn($image) => asset('storage/' . $image->url)),
            'user' => $this->whenLoaded('user', [
                'id' => $this->user?->id,
                'username' => $this->user?->username,
                'email' => $this->user?->email,
            ]),
        ];
    }
}
