<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BookNewsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\AuthorController as AdminAuthorController;
use App\Http\Controllers\AuthorController;  // This is for front-end authors
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\BookNewsController as AdminBookNewsController;


// Home Route - Display all books
Route::get('/', [BookController::class, 'welcome'])->name('welcome');
Route::get('/book-news', [BookNewsController::class, 'index'])->name('book_news.index');
Route::get('/book-news/{id}', [BookNewsController::class, 'show'])->name('book_news.show');
Route::get('/full_author/{name}/{id}', [AuthorController::class, 'full_author'])->name('full_author');

// Authentication Routes (Handled by Breeze)
require __DIR__ . '/auth.php';

// Authors Management (Front-End)
Route::resource('authors', AuthorController::class);

// Categories Management (Front-End)
Route::resource('categories', CategoryController::class);

// Books Management (Front-End)
Route::resource('books', BookController::class);
Route::get('/books/{title}/{id}', [BookController::class, 'full'])->name('full');
Route::get('/full_news/{title}/{id}', [BookNewsController::class, 'full_news'])->name('full_news');

// Search Route (as per the search form in the layout)
Route::get('/search', [BookController::class, 'search'])->name('search');
Route::get('/books', [BookController::class, 'books'])->name('books');




// Cart Routes (Add 'auth' middleware to ensure only logged-in users can access the cart)

Route::middleware('auth')->group(function () {

    Route::get('/cart', [CartController::class, 'index'])->name('cart.show');
    Route::post('/cart/add/{book}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove/{book}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update/{book}', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::post('/cart/toggle', [CartController::class, 'toggle'])->name('cart.toggle');


    // Orders Routes
    Route::resource('orders', OrderController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

    // Account Routes for editing user profile
    Route::get('/account/edit', [AccountController::class, 'edit'])->name('account.edit');
    Route::post('/account/update', [AccountController::class, 'update'])->name('account.update');
    // Checkout
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::get('/order-courier/{order}', [OrderController::class, 'orderCourier'])->name('order_courier');
});




// Admin routes with admin middleware and prefix
Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {

    // Admin dashboard route
    Route::get('/', [DashboardController::class, 'index'])->name('admin');

    // Authors CRUD routes (Admin)
    Route::resource('authors', AdminAuthorController::class, ['as' => 'admin']);

    // Books CRUD routes (Admin)
    Route::resource('books', AdminBookController::class, ['as' => 'admin']);

    // Categories CRUD routes (Admin)
    Route::resource('categories', AdminCategoryController::class, ['as' => 'admin']);

    // Book News CRUD routes (Admin)
    Route::resource('book-news', AdminBookNewsController::class, ['as' => 'admin']);
    Route::post('/books/{id}/toggleVisibility', [AdminBookController::class, 'toggleVisibility'])->name('admin.books.toggleVisibility');

});
