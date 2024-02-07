<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\AppCustomException;
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private const DEFAULT_COUNT_PER_PAGE = 10;

    public function index(): LengthAwarePaginator
    {
        return Post::with('user')
            ->orderBy('created_at', 'DESC')
            ->paginate(self::DEFAULT_COUNT_PER_PAGE);
    }

    public function commentsByPost(Post $post): LengthAwarePaginator
    {
        return Comment::where('post_id', $post->id)
            ->with('user')
            ->orderBy('created_at', 'DESC')
            ->paginate(self::DEFAULT_COUNT_PER_PAGE);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostStoreRequest $request): JsonResponse
    {
        $fields = $request->validated();
        $fields['user_id'] = auth()->id();
        $new_post = Post::create($fields);

        return response()->json($new_post);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): JsonResponse
    {
        $post->load('user');
        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, Post $post): JsonResponse
    {
        $this->assertPostsAuthor($post);

        $fields = $request->validated();

        $post->update($fields);

        return response()->json($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->assertPostsAuthor($post);

        $post->delete();

        return response()->json();
    }

    public function assertPostsAuthor(Post $post): void
    {
        $logged_user_id = auth()->id();

        if ($post->user_id === auth()->id()) {
            // goods
        } else {
            throw new AppCustomException('you_dont_have_permission_to_permform_this_action', [
                'user_id' => $logged_user_id,
                'post_id' => $post->id,
            ]);
        }
    }
}
