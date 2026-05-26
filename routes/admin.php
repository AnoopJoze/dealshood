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
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocalityController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

    // ── Core pages ────────────────────────────────────────────────────────────
    Route::get('/', [HomeController::class, 'home'])->name('admin.home');
    Route::get('dashboard', [HomeController::class, 'home'])->name('admin.dashboard');
    Route::get('billing',          fn() => view('billing'))           ->name('admin.billing');
    Route::get('profile',          fn() => view('profile'))           ->name('admin.profile');
    Route::get('rtl',              fn() => view('rtl'))               ->name('admin.rtl');
    Route::get('user-management',  fn() => view('laravel-examples/user-management'))->name('admin.user-management');
    Route::get('tables',           fn() => view('tables'))            ->name('admin.tables');
    Route::get('virtual-reality',  fn() => view('virtual-reality'))   ->name('admin.virtual-reality');
    Route::get('static-sign-in',   fn() => view('static-sign-in'))    ->name('admin.sign-in');
    Route::get('static-sign-up',   fn() => view('static-sign-up'))    ->name('admin.sign-up');
    Route::get('/logout',          [SessionsController::class, 'destroy'])->name('logout');
    Route::get('/user-profile',    [InfoUserController::class, 'create'])->name('admin.user-profile');
    Route::post('/user-profile',   [InfoUserController::class, 'store'])->name('admin.user-profile-post');
    Route::get('/login',           fn() => view('dashboard'))         ->name('sign-up');

    // AJAX store (modal create)
    Route::post('users/ajax-store',           [UserController::class, 'ajaxStore'])  ->name('users.ajaxStore');
    
    // AJAX update (modal edit)
    Route::post('users/{id}/ajax-update',     [UserController::class, 'ajaxUpdate']) ->name('users.ajaxUpdate');
    
    // JSON data for edit modal   GET admin/users/{id}/edit-data
    Route::get('users/{id}/edit-data',        [UserController::class, 'editData'])   ->name('users.editData');
    // ── Users ─────────────────────────────────────────────────────────────────
    Route::get('users/create',              [UserController::class, 'create']) ->name('users.create');
    Route::get('users/{param}',             [UserController::class, 'show'])   ->name('users.show');
    Route::get('users',                     [UserController::class, 'index'])  ->name('users.index');
    Route::get('users/{param}/edit',        [UserController::class, 'edit'])   ->name('users.edit');
    Route::get('users/{param}/destroy',     [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('users/store',              [UserController::class, 'store'])  ->name('users.store');
    Route::patch('users/{param}/update',    [UserController::class, 'update']) ->name('users.update');
    Route::post('users/getlist',            [UserController::class, 'getlist'])->name('users.getlist');

    // ── Localities ────────────────────────────────────────────────────────────
    Route::post('localities/data',          [LocalityController::class, 'data'])        ->name('localities.data');
    Route::post('localities/ajax-store',    [LocalityController::class, 'ajaxStore'])   ->name('localities.ajaxStore');
    Route::post('localities/inline-update', [LocalityController::class, 'inlineUpdate'])->name('localities.inlineUpdate');
    Route::resource('localities', LocalityController::class);

    // ── Categories ────────────────────────────────────────────────────────────
    Route::post('categories/data',          [CategoryController::class, 'data'])        ->name('categories.data');
    Route::post('categories/ajax-store',    [CategoryController::class, 'ajaxStore'])   ->name('categories.ajaxStore');
    Route::post('categories/inline-update', [CategoryController::class, 'inlineUpdate'])->name('categories.inlineUpdate');
    Route::resource('categories', CategoryController::class);

    // ── Subcategories ─────────────────────────────────────────────────────────
    Route::post('subcategories/data',          [SubCategoryController::class, 'data'])        ->name('subcategories.data');
    Route::post('subcategories/ajax-store',    [SubCategoryController::class, 'ajaxStore'])   ->name('subcategories.ajaxStore');
    Route::post('subcategories/inline-update', [SubCategoryController::class, 'inlineUpdate'])->name('subcategories.inlineUpdate');
    Route::resource('subcategories', SubCategoryController::class);

    // Returns subcategories for a given category (used by the modal cascade)
    Route::get('get-subcategories/{id}', [SubCategoryController::class, 'getByCategory'])
         ->name('subcategories.byCategory');

    // ── Posts – custom routes MUST come BEFORE Route::resource ───────────────

    // DataTables feed
    Route::post('posts/data',               [PostController::class, 'data'])        ->name('posts.data');

    // AJAX create
    Route::post('posts/ajax-store',         [PostController::class, 'ajaxStore'])   ->name('posts.ajaxStore');

    // Inline field update (status / is_featured / is_active)
    Route::post('posts/inline-update',      [PostController::class, 'inlineUpdate'])->name('posts.inlineUpdate');

    // JSON data for the edit modal   GET admin/posts/{post}/edit-data
    Route::get('posts/{post}/edit-data',    [PostController::class, 'editData'])    ->name('posts.editData');

    // Spatie media upload (Dropzone)
    Route::post('posts/media-upload',       [PostController::class, 'mediaUpload']) ->name('posts.mediaUpload');

    // Delete a single Spatie media item   DELETE admin/posts/media/{id}
    Route::delete('posts/media/{id}',       [PostController::class, 'mediaDelete']) ->name('posts.mediaDelete');

    // Resource routes  (index, create, store, show, edit, update, destroy)
    // update  → PUT  /admin/posts/{post}   (posts.update)
    // destroy → DELETE /admin/posts/{post} (posts.destroy)
    Route::post('posts/{post}',               [PostController::class, 'show'])        ->name('posts.show');

    Route::resource('posts', PostController::class);

});