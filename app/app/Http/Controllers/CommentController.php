<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Models\Comment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Exceptions\AppCustomException;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentStoreRequest $request): JsonResponse
    {
        $fields = $request->validated();
        $fields['user_id'] = auth()->id();

        $new_comment = Comment::create($fields);

        return response()->json($new_comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentUpdateRequest $request, Comment $comment): JsonResponse
    {
        $this->assertIsCommentOwner($comment);

        $fields = $request->validated();

        $comment->update($fields);

        return response()->json($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment): JsonResponse
    {
        // both post and comment owner are able to delete the comment
        $this->assertPostCommentOwner($comment);

        $comment->delete();

        return response()->json();
    }

    public function assertPostCommentOwner(Comment $comment): void
    {
        if ($this->isPostOrCommentOwner($comment)) {
            // goods
        } else {
            throw new AppCustomException('you_dont_have_permission_to_delete_this_comment', [
                'logged_user_id' => auth()->id(),
                'comment_id' => $comment->id,
                'post_id' => $comment->post_id,
            ]);
        }
    }

    public function isPostOrCommentOwner(Comment $comment): bool
    {
        $logged_user_id = auth()->id();

        $is_post_owner = $comment->post->user_id === $logged_user_id;
        $is_comment_owner = $comment->user_id === $logged_user_id;

        return $is_post_owner || $is_comment_owner;
    }

    public function assertIsCommentOwner(Comment $comment): void
    {
        $logged_user_id = auth()->id();

        if ($comment->user_id === $logged_user_id) {
            // goods
        } else {
            throw new AppCustomException('you_dont_have_permission_to_permform_this_action', [
                'logged_user_id' => $logged_user_id,
                'comment_id' => $comment->id,
            ]);
        }
    }
}
