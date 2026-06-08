<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| 1. BASIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/basic', fn () => 'GET request');
Route::post('/basic', fn () => 'POST request');
Route::put('/basic', fn () => 'PUT request');
Route::patch('/basic', fn () => 'PATCH request');
Route::delete('/basic', fn () => 'DELETE request');

Route::match(['GET', 'POST'], '/form', function (Request $request) {
    if ($request->isMethod('post')) {
        return 'Form submitted: ' . $request->input('name');
    }

    return view('form-demo');
});

Route::any('/any', fn () => 'Any HTTP method');


/*
|--------------------------------------------------------------------------
| 2. ROUTE PARAMETERS
|--------------------------------------------------------------------------
*/

Route::get('/users/{id}', function (int $id) {
    return "User ID: {$id}";
});

Route::get('/users/{id}/posts/{postId?}', function (
    int $id,
    ?int $postId = null
) {
    if ($postId) {
        return "Post #{$postId} milik User #{$id}";
    }

    return "Semua post milik User #{$id}";
});

Route::get('/orders/{id}', fn (int $id) => "Order #{$id}")
    ->whereNumber('id');

Route::get('/blog/{category}/{slug}', fn ($cat, $slug) => "{$cat}/{$slug}")
    ->whereAlpha('category')
    ->whereAlphaNumeric('slug');


/*
|--------------------------------------------------------------------------
| 3. ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'index'])
            ->name('dashboard');

        Route::get('/users', fn () => 'Admin Users')
            ->name('users');

        Route::get('/settings', fn () => 'Admin Settings')
            ->name('settings');
    });


/*
|--------------------------------------------------------------------------
| 4. PRODUCT CRUD
|--------------------------------------------------------------------------
*/

Route::resource('products', ProductController::class);


/*
|--------------------------------------------------------------------------
| 5. CATEGORY CRUD
|--------------------------------------------------------------------------
*/

Route::resource('categories', CategoryController::class)
    ->only([
        'index',
        'store',
        'destroy',
    ]);


/*
|--------------------------------------------------------------------------
| 6. ORDER CRUD
|--------------------------------------------------------------------------
*/

Route::resource('orders', OrderController::class)
    ->except([
        'create',
        'edit',
    ]);


/*
|--------------------------------------------------------------------------
| 7. NESTED RESOURCE
|--------------------------------------------------------------------------
*/

Route::resource(
    'categories.products',
    CategoryProductController::class
)->only(['index', 'create', 'store']);


/*
|--------------------------------------------------------------------------
| 8. REDIRECT & FALLBACK
|--------------------------------------------------------------------------
*/

Route::redirect('/old-url', '/new-url', 301);

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
