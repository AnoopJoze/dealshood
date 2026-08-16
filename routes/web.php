<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ContactController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

Route::get('/sitemap.xml', function () {
    $path = public_path('sitemap.xml');
    if (!file_exists($path)) {
        Artisan::call('sitemap:generate');
    }
    return response()->file($path, ['Content-Type' => 'application/xml']);
});
Route::get('/generate-sitemap', function () {

    $sitemap = Sitemap::create();

    Post::where('status', 'published')
        ->get()
        ->each(function ($post) use ($sitemap) {

            $sitemap->add(
                Url::create("/post/{$post->slug}")
                    ->setLastModificationDate($post->updated_at)
            );
        });

    $sitemap->writeToFile(public_path('sitemap.xml'));

    return 'Sitemap generated';
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create']);
    Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');

});

// ── routes/web.php ────────────────────────────────────
Route::get('/contact', function () {
    $categories = \App\Models\Category::withCount(['posts' => fn($q) => $q->where('status', 'published')])
        ->orderBy('id', 'asc')->get();
    return view('frontend.contact-us', compact('categories'));
})->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send')
           ->middleware('throttle:3,1');
Route::get('/login', function () {
    return view('session/login-session');
})->name('login');
