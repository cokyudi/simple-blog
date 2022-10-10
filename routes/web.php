<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/detail/{id}', [App\Http\Controllers\HomeController::class, 'detail'])->name('detail');

Route::group(['middleware' => 'auth', 'user-access:user', 'user-access:admin', 'prefix' => 'post'], function () {
    Route::post('create_post', [App\Http\Controllers\PostController::class, 'createPost']);
    Route::get('get_user_posts', [App\Http\Controllers\PostController::class, 'getUserPosts']);
    Route::get('get_post_by_id/{id}', [App\Http\Controllers\PostController::class, 'getPostById']);
    Route::post('edit_post', [App\Http\Controllers\PostController::class, 'editPost']);
    Route::post('delete_post', [App\Http\Controllers\PostController::class, 'deletePost']);
});

Route::group(['middleware' => 'auth', 'user-access:user', 'user-access:admin'], function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/get-loggedin-user', [App\Http\Controllers\DashboardController::class, 'getLoggedInUser']);
    Route::get('/dashboard/{any}', [App\Http\Controllers\DashboardController::class, 'index'])->where('any','.*');
});

Route::group(['middleware' => 'auth', 'user-access:admin'], function () {
    Route::post('/dashboard/get-users', [App\Http\Controllers\DashboardController::class, 'getAllUser']);
    Route::post('/dashboard/get-user-by-id', [App\Http\Controllers\DashboardController::class, 'getUserById']);
    Route::post('/dashboard/create-user', [App\Http\Controllers\DashboardController::class, 'createUser']);
    Route::post('/dashboard/edit-user', [App\Http\Controllers\DashboardController::class, 'editUser']);
    Route::post('/dashboard/delete-user', [App\Http\Controllers\DashboardController::class, 'deleteUser']);
});

Route::get('/post/get_all', [App\Http\Controllers\PostController::class, 'getAllPosts']);
Route::get('/post/get_post_by_id/{id}', [App\Http\Controllers\PostController::class, 'getPostById']);
