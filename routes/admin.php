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
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AdSubmissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

    // ── Site Settings ──────────────────────────────────
    Route::get ('settings',              [SettingController::class, 'index'])      ->name('admin.settings.index');
    Route::post('settings',              [SettingController::class, 'update'])     ->name('admin.settings.update');
    Route::post('settings/remove/{key}', [SettingController::class, 'removeFile'])->name('admin.settings.remove-file');
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

    /* ── Users ─────────────────────────────────────── */
Route::prefix('users')->name('users.')->group(function () {

    // Standard CRUD (existing)
    Route::get('/',                       [UserController::class, 'index'])->name('index');
    Route::post('/data',                  [UserController::class, 'getlist'])->name('getlist');
    Route::post('/',                      [UserController::class, 'ajaxStore'])->name('ajaxStore');
    Route::get('/{id}/edit-data',         [UserController::class, 'editData'])->name('editData');
    Route::post('/{id}/ajax-update',      [UserController::class, 'ajaxUpdate'])->name('ajaxUpdate');
    Route::get('/{id}',                   [UserController::class, 'show'])->name('show');

    // Soft-delete
    Route::delete('/{id}',               [UserController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/restore',          [UserController::class, 'restore'])->name('restore');
    Route::delete('/{id}/force-delete',   [UserController::class, 'forceDelete'])->name('forceDelete');

    // Bulk
    Route::post('/bulk-trash',            [UserController::class, 'bulkTrash'])->name('bulkTrash');
    Route::post('/bulk-restore',          [UserController::class, 'bulkRestore'])->name('bulkRestore');
    Route::post('/empty-trash',           [UserController::class, 'emptyTrash'])->name('emptyTrash');
});

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

    /* ── Posts ─────────────────────────────────────── */
Route::prefix('posts')->name('posts.')->group(function () {

    // Standard CRUD (existing)
    Route::get('/',                       [PostController::class, 'index'])->name('index');
    Route::post('/data',                   [PostController::class, 'data'])->name('data');
    Route::post('/',                      [PostController::class, 'ajaxStore'])->name('ajaxStore');
    Route::get('/{post}/edit-data',       [PostController::class, 'editData'])->name('editData');
    Route::put('/{post}',                 [PostController::class, 'update'])->name('update');
    Route::post('/{post}/share',          [PostController::class, 'share'])->name('share');
    Route::get('/{post}',                 [PostController::class, 'show'])->name('show');
    Route::post('/inline-update',         [PostController::class, 'inlineUpdate'])->name('inlineUpdate');
    Route::post('/media/upload',          [PostController::class, 'mediaUpload'])->name('mediaUpload');
    Route::delete('/media/{id}',          [PostController::class, 'mediaDelete'])->name('mediaDelete');

    // Soft-delete
    Route::delete('/{post}',             [PostController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/restore',          [PostController::class, 'restore'])->name('restore');
    Route::delete('/{id}/force-delete',   [PostController::class, 'forceDelete'])->name('forceDelete');

    // Bulk
    Route::post('/bulk-trash',            [PostController::class, 'bulkTrash'])->name('bulkTrash');
    Route::post('/bulk-restore',          [PostController::class, 'bulkRestore'])->name('bulkRestore');
    Route::post('/empty-trash',           [PostController::class, 'emptyTrash'])->name('emptyTrash');
});

    // ── Roles ─────────────────────────────────────────────────────────────────────
    Route::post('roles/getlist',                  [RoleController::class, 'getList'])         ->name('roles.getlist');
    Route::post('roles/ajax-store',               [RoleController::class, 'ajaxStore'])       ->name('roles.ajaxStore');
    Route::post('roles/{id}/ajax-update',         [RoleController::class, 'ajaxUpdate'])      ->name('roles.ajaxUpdate');
    Route::get('roles/{id}/edit-data',            [RoleController::class, 'editData'])        ->name('roles.editData');
    Route::post('roles/{id}/sync-permissions',    [RoleController::class, 'syncPermissions']) ->name('roles.syncPermissions');
    Route::resource('roles', RoleController::class)->except(['create', 'edit', 'store', 'update']);

    // ── Permissions ───────────────────────────────────────────────────────────────
    Route::post('permissions/getlist',            [PermissionController::class, 'getList'])   ->name('permissions.getlist');
    Route::post('permissions/ajax-store',         [PermissionController::class, 'ajaxStore']) ->name('permissions.ajaxStore');
    Route::post('permissions/{id}/ajax-update',   [PermissionController::class, 'ajaxUpdate'])->name('permissions.ajaxUpdate');
    Route::resource('permissions', PermissionController::class)->only(['index', 'destroy']);

    Route::get('posts_reorder', [PostController::class, 'reorder'])->name('posts.reorder');
    Route::post('posts_reorder', [PostController::class, 'saveOrder'])->name('posts.saveOrder');
    Route::get('subcategories_reorder',  [SubCategoryController::class, 'reorder'])  ->name('subcategories.reorder');
    Route::post('subcategories_reorder', [SubCategoryController::class, 'saveOrder'])->name('subcategories.saveOrder');

/* ── Ad Submissions ────────────────────────────── */
Route::prefix('ad-submissions')->name('ad-submissions.')->group(function () {
    Route::get('/',                   [AdSubmissionController::class, 'index'])->name('index');
    Route::post('/data',              [AdSubmissionController::class, 'data'])->name('data');
    Route::get('/{submission}',       [AdSubmissionController::class, 'show'])->name('show');
    Route::post('/{submission}/approve', [AdSubmissionController::class, 'approve'])->name('approve');
    Route::post('/{submission}/reject',  [AdSubmissionController::class, 'reject'])->name('reject');
    Route::delete('/{submission}',    [AdSubmissionController::class, 'destroy'])->name('destroy');
});
});
