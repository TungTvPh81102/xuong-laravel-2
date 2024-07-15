<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Image;
use App\Models\Rating;
use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = Article::all();

        foreach ($articles as $article) {
            for ($i = 0; $i < 5; $i++) {
                Rating::create([
                    'user_id' => rand(1, 10),
                    'rating' => rand(1, 5),
                    'rateable_id' => $article->id,
                    'rateable_type' => Article::class
                ]);
            }
        }

        $videos = Video::all();
        foreach ($videos as $video) {
            for ($i = 0; $i < 5; $i++) {
                Rating::create([
                    'user_id' => rand(1, 10),
                    'rating' => rand(1, 5),
                    'rateable_id' => $video->id,
                    'rateable_type' => Video::class
                ]);
            }
        }

        $images = Image::all();
        foreach ($images as $image) {
            for ($i = 0; $i < 5; $i++) {
                Rating::create([
                    'user_id' => rand(1, 10),
                    'rating' => rand(1, 5),
                    'rateable_id' => $image->id,
                    'rateable_type' => Video::class
                ]);
            }
        }
    }
}
