<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\PostCommentSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PostCommentSeeder::class,
        ]);
    }
}
