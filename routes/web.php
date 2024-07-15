<?php

use App\Models\Article;
use App\Models\Comment;
use App\Models\Image;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $articles = Article::query()->paginate(5);

    $videos = Video::query()->paginate(5);

    $users = User::query()->paginate(5);
    return view('welcome', compact('articles', 'videos', 'users'));
});

Route::get('/article-comments/{article}', function ($id) {
    $article = Article::query()->findOrFail($id);
    echo 'All bình luận của bài viết số: ' . $id;
    dd($article->comments->toArray());
})->name('article.comments');

Route::get('/video-ratings/{video}', function ($id) {
    $video = Video::query()->findOrFail($id);
    echo 'Lượt đánh giá của video số: ' . $id;
    dd($video->ratings->toArray());
})->name('video.ratings');

Route::get('/user-comments/{user}', function (User $user) {
    echo 'All bình luận người dùng số: ' . $user->id;
    $comments = $user->comments;
    dd($comments->toArray());
})->name('user.comments');

Route::get('/article-rating/{article}', function (Article $article) {
    echo 'Đánh giá trung bình bài viết số: ' . $article->id;
    $article = Article::query()->findOrFail($article->id);
    dd(number_format($article->ratings()->avg('rating'), 2));
})->name('article.rating');

Route::get('/top-rated-articles', function () {
    echo 'Top bài viết có lượt đánh giá trung bình cao nhất';
    $topRatedArticles = DB::table('articles')
        ->leftJoin('ratings', function ($join) {
            $join->on('articles.id', '=', 'ratings.rateable_id')
                ->where('ratings.rateable_type', '=', 'App\Models\Article');
        })
        ->select('articles.*', DB::raw('AVG(ratings.rating) as average_rating'))
        ->groupBy('articles.id')
        ->orderByDesc('average_rating')
        ->take(5)
        ->get();
    dd($topRatedArticles->toArray());
})->name('article.top.rated');

Route::get('/top-rated-videos', function () {
    echo 'Top video có lượt đánh giá trung bình cao nhất';
    $topRatedVideos = DB::table('videos')
        ->leftJoin('ratings', function ($join) {
            $join->on('videos.id', '=', 'ratings.rateable_id')
                ->where('ratings.rateable_type', '=', 'App\Models\Video');
        })
        ->select('videos.*', DB::raw('AVG(ratings.rating) as average_rating'))
        ->groupBy('videos.id')
        ->orderByDesc('average_rating')
        ->take(5)
        ->get();
    dd($topRatedVideos->toArray());
})->name('video.top.rated');

Route::get('/top-rated-images', function () {
    echo 'Top hình ảnh lượt đánh giá trung bình cao nhất';
    $topRatedImages = DB::table('images')
        ->leftJoin('ratings', function ($join) {
            $join->on('images.id', '=', 'ratings.rateable_id')
                ->where('ratings.rateable_type', '=', 'App\Models\Image');
        })
        ->select('images.*', DB::raw('AVG(ratings.rating) as average_rating'))
        ->groupBy('images.id')
        ->orderByDesc('average_rating')
        ->take(5)
        ->get();
    dd($topRatedImages->toArray());
})->name('image.top.rated');

Route::get('/user-comments-info/{user}', function (User $user) {
    echo 'Thông tin hình ảnh, bài viết, video mà người dùng bình luận: ' . $user->id;
    $comments = $user->comments;

    $articleIds  = $comments->where('commentable_type', Article::class)->pluck('commentable_id')->toArray();
    $articels = Article::query()->whereIn('id', $articleIds)->get();

    $videoIds  = $comments->where('commentable_type', Video::class)->pluck('commentable_id')->toArray();
    $videos = Video::query()->whereIn('id', $videoIds)->get();
  
    $imageIds = $comments->where('commentable_type', Image::class)->pluck('commentable_id')->toArray();
    $images = Image::whereIn('id', $imageIds)->get();

    dd($articels->toArray(), $videos->toArray(), $images->toArray());
})->name('user.comments-info');
