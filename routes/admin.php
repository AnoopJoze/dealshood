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
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'home'])->name('admin.home');
	Route::get('dashboard', function () {
		return view('dashboard');
	})->name('admin.dashboard');

	Route::get('billing', function () {
		return view('billing');
	})->name('admin.billing');

	Route::get('profile', function () {
		return view('profile');
	})->name('admin.profile');

	Route::get('rtl', function () {
		return view('rtl');
	})->name('admin.rtl');

	Route::get('user-management', function () {
		return view('laravel-examples/user-management');
	})->name('admin.user-management');

	Route::get('tables', function () {
		return view('tables');
	})->name('admin.tables');

    Route::get('virtual-reality', function () {
		return view('virtual-reality');
	})->name('admin.virtual-reality');

    Route::get('static-sign-in', function () {
		return view('static-sign-in');
	})->name('admin.sign-in');

    Route::get('static-sign-up', function () {
		return view('static-sign-up');
	})->name('admin.sign-up');

    Route::get('/logout', [SessionsController::class, 'destroy']);
	Route::get('/user-profile', [InfoUserController::class, 'create'])->name('admin.user-profile');
	Route::post('/user-profile', [InfoUserController::class, 'store'])->name('admin.user-profile-post');
    Route::get('/login', function () {
		return view('dashboard');
	})->name('sign-up');


	//Users

    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('users/{param}', [UserController::class, 'show'])->name('users.show');
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/{param}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::get('users/{param}/destroy', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('users/store', [UserController::class, 'store'])->name('users.store');
    Route::patch('users/{param}/update', [UserController::class, 'update'])->name('users.update');
    Route::post('users/getlist', [UserController::class, 'getlist'])->name('users.getlist');


    // Localities
    Route::post('localities/inline-update', [LocalityController::class, 'inlineUpdate'])
    ->name('localities.inlineUpdate');Route::post(
    'localities/ajax-store',
    [LocalityController::class, 'ajaxStore']
)->name('localities.ajaxStore');
    Route::post('localities/data', [LocalityController::class, 'data'])->name('localities.data');
    Route::resource('localities', LocalityController::class);

    // Categories
    Route::post('categories/inline-update', [CategoryController::class, 'inlineUpdate'])
    ->name('categories.inlineUpdate');
    Route::post('categories/ajax-store', [CategoryController::class, 'ajaxStore'])
    ->name('categories.ajaxStore');
    Route::post('categories/data', [CategoryController::class, 'data'])->name('categories.data');
    Route::resource('categories', CategoryController::class);

    // Subcategories

    // Subcategories
Route::post('subcategories/inline-update', [SubcategoryController::class, 'inlineUpdate'])
    ->name('subcategories.inlineUpdate');
    Route::post(
    'subcategories/ajax-store',
    [SubcategoryController::class, 'ajaxStore']
)->name('subcategories.ajaxStore');
    Route::post('subcategories/data', [SubcategoryController::class, 'data'])->name('subcategories.data');
    Route::resource('subcategories', SubcategoryController::class);

Route::get(
    'get-subcategories/{id}',
    [SubcategoryController::class, 'getByCategory']
)->name('subcategories.byCategory');
Route::post(
    'posts/upload-image',
    [PostController::class, 'uploadImage']
)->name('posts.uploadImage');
Route::post(
    'posts/media-upload',
    [PostController::class, 'mediaUpload']
)->name('posts.mediaUpload');
Route::delete(
    'posts/media-delete/{id}',
    [PostController::class, 'mediaDelete']
)->name('posts.mediaDelete');
    // Subcategories
Route::post('posts/inline-update', [PostController::class, 'inlineUpdate'])
    ->name('posts.inlineUpdate');
    Route::post(
    'posts/ajax-store',
    [PostController::class, 'ajaxStore']
)->name('posts.ajaxStore');
    Route::post('posts/data', [PostController::class, 'data'])->name('posts.data');
    Route::resource('posts', PostController::class);
});
