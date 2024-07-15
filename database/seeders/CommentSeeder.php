<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = Article::all();

        foreach ($articles as $article) {
            for ($i = 0; $i < 5; $i++) {
                Comment::create([
                    'user_id' => rand(1, 10),
                    'content' => fake()->paragraph(),
                    'commentable_id' => $article->id,
                    'commentable_type' => Article::class
                ]);
            }
        }

        $views = Video::all();
        foreach ($views as $view) {
            for ($i = 0; $i < 5; $i++) {
                Comment::create([
                    'user_id' => rand(1, 10),
                    'content' => fake()->paragraph(),
                    'commentable_id' => $view->id,
                    'commentable_type' => Video::class
                ]);
            }
        }

        $images = Image::all();
        foreach ($images as $image) {
            for ($i = 0; $i < 5; $i++) {
                Comment::create([
                    'user_id' => rand(1, 10),
                    'content' => fake()->paragraph(),
                    'commentable_id' => $image->id,
                    'commentable_type' => Image::class
                ]);
            }
        }
    }
}
