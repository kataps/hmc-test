<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Constants\AppConstant;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostCommentSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', AppConstant::APP_DEFAULT_USER_EMAIL)->first();

        $posts = Post::factory(6)->create([
            'user_id' => $user->id,
        ]);

        $posts->each(function (Post $post) use ($user){
            Comment::factory(1)->create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
        });
    }
}
