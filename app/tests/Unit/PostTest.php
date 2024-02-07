<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Tests\TestCase;

class PostTest extends TestCase
{
    public function test_post_store_validation_is_working(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/posts', []);
        $response->assertUnprocessable();
    }

    public function test_post_update_validation_is_working(): void
    {
        $user = User::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $url = '/api/posts/' . $post->id;

        $response = $this->actingAs($user)->putJson($url, []);
        $response->assertUnprocessable();
    }

    public function test_user_can_only_update_his_or_her_post(): void
    {
        // scenario, user alvin and a user with name 'anonymous'
        $user_alvin = User::factory()->create();
        $user_anonymous = User::factory()->create();

        // alvin created a post
        $user_alvin = Post::factory()->create([
            'user_id' => $user_alvin->id,
        ]);

        // anonymous user crafted a request and attempted to update alvin's posts
        $uri = '/api/posts/' . $user_alvin->id;
        $response = $this->actingAs($user_anonymous)->putJson($uri, [
            'title' => 'I got your post!',
            'content' => 'This website is not secured!'
        ]);

        // but unfortunately, alvin knows this kind of vulnerability
        // exception_message: you_dont_have_permission_to_permform_this_action
        $response->assertStatus(500);
    }

    public function test_user_can_only_delete_his_or_her_post(): void
    {
        $user_alvin = User::factory()->create();
        $user_anonymous = User::factory()->create();

        $user_alvin = Post::factory()->create([
            'user_id' => $user_alvin->id,
        ]);

        $uri = '/api/posts/' . $user_alvin->id;
        $response = $this->actingAs($user_anonymous)->deleteJson($uri);

        $response->assertStatus(500);
    }
}
