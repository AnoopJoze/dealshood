<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\FrontEndController;

Route::get('/', [FrontEndController::class, 'home'])->name('home');
Route::get('/dealshood-ads-details/{locality}/{category}/{subcategory}/{slug}', [FrontEndController::class, 'postDetail'])->name('post-details');
Route::get('/dealshood-ads', [FrontEndController::class, 'listing'])
    ->name('posts.listing');

Route::get('/get-subcategories/{categoryId}', [FrontEndController::class, 'getSubcategories']);

    Route::post('/posts/{id}/like', [FrontEndController::class, 'like']);
    Route::post('/posts/{id}/share', [FrontEndController::class, 'share']);
    Route::post('/posts/{id}/toggle-like', [FrontEndController::class, 'toggleLike']);
    Route::get('/debug-og/{post}', function (\App\Models\Post $post) {
    $ogImage = $post->getFirstMediaUrl('posts');
    return response()->json([
        'raw_url'       => $ogImage,
        'is_empty'      => empty($ogImage),
        'is_absolute'   => str_starts_with($ogImage, 'http'),
        'is_https'      => str_starts_with($ogImage, 'https'),
        'final_url'     => str_replace('http://', 'https://', $ogImage ?: asset('frontend/img/default.jpg')),
    ]);
});
