<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use App\Models\Post;
use App\Models\Image;


class PostService
{
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Post::with(['user', 'images'])->latest()->paginate($perPage);
    }

    public function getPostsByUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Post::where('user_id', $userId)
            ->with(['user:id,username,email', 'images:id,post_id,url'])
            ->latest()
            ->paginate($perPage);
    }

    public function search(string $query, int $perPage = 10): LengthAwarePaginator
    {
        return Post::query()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%$query%")
                ->orWhere('body', 'like', "%$query%");
            })
            ->with('user')
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): Post
    {
        $post = new Post([
            'title' => $data['title'],
            'body' => $data['body'],
        ]);
        $post->user_id = Auth::id();
        $post->save();

        if (isset($data['images'])) {
            $this->storeImages($post, $data['images']);
        }

        return $post;
    }

    public function update(Post $post, array $data): Post
    {
        if (Auth::id() !== $post->user_id) {
            abort(403, 'No puedes modificar este post');
        }

        // Solo actualiza los campos que estén presentes
        $post->fill([
            'title' => $data['title'] ?? $post->title,
            'body' => $data['body'] ?? $post->body,
        ]);
        $post->save();

        if (isset($data['images'])) {
            $this->deleteAllImages($post);
            $this->storeImages($post, $data['images']);
        }

        return $post;
    }

    public function delete(Post $post): bool
    {
        $this->deleteAllImages($post);
        return $post->delete();
    }

    protected function storeImage($image): string
    {
        $name = Str::uuid() . '.' . $image->getClientOriginalExtension();
        return $image->storeAs('posts', $name, 'public');
    }

    protected function deleteImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    protected function storeImages(Post $post, array $images): void
    {
        foreach ($images as $image) {
            $path = $this->storeImage($image);

            Image::create([
                'post_id' => $post->id,
                'url' => $path,
            ]);
        }
    }

    protected function deleteAllImages(Post $post): void
    {
        foreach ($post->images as $image) {
            $this->deleteImage($image->url);
            $image->delete();
        }
    }
}
