<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\FrontEndController;
use App\Http\Controllers\Frontend\RegisterController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');
 
// Handle the signed verification link clicked from email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();           // marks email_verified_at
    return redirect()->route('home')->with('success', 'Email verified! Welcome to DealsHood.');
})->middleware(['auth', 'signed'])->name('verification.verify');
 
// Resend verification email
Route::post('/email/verification-notification', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return redirect()->route('home');
    }
    $request->user()->sendEmailVerificationNotification();
    return back()->with('resent', true);
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware('guest')->group(function () {
    Route::get('/register',  [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});
Route::get('/offline', fn() => view('frontend.offline'))->name('offline');

Route::get('/favourites', [FrontendController::class, 'favourites'])
        ->name('favourites');
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
