<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Tests\TestCase;

class PostTest extends TestCase
{
    public function test_user_can_create_post(): void
    {
        $user = User::factory()->create();

        $payload = [
            'title' => 'Sample Title',
            'content' => 'just a content for test'
        ];

        // send it to backend
        $response = $this->actingAs($user)
            ->postJson('/api/posts', $payload);

        // make sure you got succes response code 200
        $response->assertStatus(200);

        // make sure it actually saved to database records
        $this->assertDatabaseHas('posts', $payload);
    }

    public function test_user_can_update_post(): void
    {
        $user = User::factory()->create();

        // creates a post using factory factory
        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $url = '/api/posts/' . $post->id;

        $update_payload = [
            'title' => 'updated title',
            'content' => 'updated_content',
        ];

        $response = $this->actingAs($user)->putJson($url, $update_payload);

        $response->assertStatus(200);

        // assert database table posts has beed updated

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => $update_payload['title'],
            'content' => $update_payload['content']
        ]);
    }

    public function test_user_can_fetch_posts(): void
    {
        $user = User::factory()->create();

        // creates a post using factory factory
        $posts = Post::factory(10)->create([
            'user_id' => $user->id,
        ]);


        $post_to_check = $posts->first()->toArray();
        $response = $this->actingAs($user)->getJson('/api/posts');
        // check post exists in json response
        $response->assertJsonFragment($post_to_check);
    }

    public function test_user_can_delete_post(): void
    {
        $user = User::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        // check the post created in factory exists in database posts table
        $post_to_check = $post->toArray();
        $this->assertDatabaseHas('posts', $post_to_check);

        // delete the post
        $url_endpoint = '/api/posts/' . $post->id;
        $response = $this->actingAs($user)->deleteJson($url_endpoint);

        $response->assertStatus(200);

        // make sure that the record is no longer exist in records
        $this->assertDatabaseMissing('posts', $post_to_check);
    }
}
