<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate([
            'name'     => 'User',
            'email'    => 'user@example.com',
            'password' => bcrypt('user123'),
        ]);

        Category::factory(5)->create();
        Tag::factory(10)->create();   // ← tags disponibles en los formularios
        Post::factory(15)->create();
    }
}
