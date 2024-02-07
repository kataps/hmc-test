<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_comment_to_a_post(): void
    {
        $user = User::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $payload = [
            'post_id' => $post->id,
            'content' => 'Be the first comment to your own post'
        ];

        // user added a comment on his post
        $response = $this->actingAs($user)->postJson('/api/comments', $payload);
        $response->assertSuccessful();

        $exist_in_record = $this->checkRecordExistInDatabase($payload, $user);
        $this->assertTrue($exist_in_record);
    }

    public function test_user_can_add_comment_to_other_post(): void
    {
        $user = User::factory()->create();

        $other_user = User::factory()->create();
        $other_post = Post::factory()->create([
            'user_id' => $other_user->id
        ]);

        $payload = [
            'post_id' => $other_post->id,
            'content' => 'Nice post!',
        ];

        $response = $this->actingAs($user)->postJson('/api/comments', $payload);
        $response->assertSuccessful();

        $exist_in_record = $this->checkRecordExistInDatabase($payload, $user);
        $this->assertTrue($exist_in_record);
    }

    public function test_user_can_update_his_or_her_comment(): void
    {
        $user = User::factory()->create();

        // user created a post
        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        // user commented his/her post
        $payload = [
            'post_id' => $post->id,
            'content' => 'My first comment'
        ];

        $response = $this->actingAs($user)->postJson('/api/comments', $payload);
        $response->assertSuccessful();

        $exist_in_record = $this->checkRecordExistInDatabase($payload, $user);
        $this->assertTrue($exist_in_record);
    }

    public function test_user_can_delete_his_or_her_comment(): void
    {
        $user = User::factory()->create();

        // user created a post
        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => 'just a content, it ill be deleted'
        ]);

        $uri = '/api/comments/' . $comment->id;
        $response  = $this->actingAs($user)->deleteJson($uri);
        $response->assertSuccessful();
    }

    private function checkRecordExistInDatabase(array $payload, User $user)
    {
        // check if the comment is in database
        $payload_to_check = $payload;
        $payload_to_check['user_id'] = $user->id;
        $this->assertDatabaseHas('comments', $payload_to_check);

        return true;
    }
}
