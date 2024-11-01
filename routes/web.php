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
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\AdminPublisherController;
use App\Http\Controllers\Publisher\PublisherBookController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Publisher\PublisherAuthorController;
use App\Http\Controllers\Publisher\PublisherAccountController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\AuthorController as AdminAuthorController;
use App\Http\Controllers\AuthorController;  // This is for front-end authors
use App\Http\Controllers\Admin\BookNewsController as AdminBookNewsController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\TbcCheckoutController;



// Home Route - Display all books
Route::get('/', [BookController::class, 'welcome'])->name('welcome');
Route::get('/book-news', [BookNewsController::class, 'index'])->name('book_news.index');
Route::get('/book-news/{id}', [BookNewsController::class, 'show'])->name('book_news.show');
Route::get('/full_author/{name}/{id}', [AuthorController::class, 'full_author'])->name('full_author');
Route::get('/terms_conditions', [BookNewsController::class, 'terms'])->name('terms_conditions');


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


//TBC E-COMEERCE ROUTES

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [TbcCheckoutController::class, 'showCheckoutPage'])->name('checkout');
    Route::post('/checkout/create', [TbcCheckoutController::class, 'createOrder'])->name('checkout.create');
    Route::get('/tbc/callback', [TbcCheckoutController::class, 'handleCallback'])->name('tbc.callback');
     Route::post('/checkout', [TbcCheckoutController::class, 'createOrder'])->name('checkout');
    Route::get('/callback', [TbcCheckoutController::class, 'handleCallback'])->name('callback');

});


// Cart Routes (Add 'auth' middleware to ensure only logged-in users can access the cart)

Route::middleware('auth')->group(function () {

    Route::get('/cart', [CartController::class, 'index'])->name('cart.show');
    Route::post('/cart/add/{book}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove/{book}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update/{book}', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::post('/cart/toggle', [CartController::class, 'toggle'])->name('cart.toggle');
    Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');


    // Orders Routes
    Route::resource('orders', OrderController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

    // Account Routes for editing user profile
    Route::get('/account/edit', [AccountController::class, 'edit'])->name('account.edit');
    Route::post('/account/update', [AccountController::class, 'update'])->name('account.update');
    // Checkout
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::get('/order-courier/{order}', [OrderController::class, 'orderCourier'])->name('order_courier');
});



// Publisher routes with 'publisher' role middleware
Route::middleware(['auth', 'role:publisher'])->group(function () {
    
    // Publisher dashboard
    Route::get('/publisher/dashboard', function () {
        return view('publisher.dashboard');
    })->name('publisher.dashboard');

    Route::get('/publisher/account/edit', [PublisherAccountController::class, 'edit'])->name('publisher.account.edit');
    Route::match(['put', 'post'], '/publisher/account/update', [PublisherAccountController::class, 'update'])->name('publisher.account.update');
    Route::get('/publisher/my-books', [PublisherBookController::class, 'myBooks'])->name('publisher.my_books');

    
    // Routes for Publisher's Book Upload
    Route::resource('publisher/books', PublisherBookController::class)->only(['create', 'store'])->names([
        'create' => 'publisher.books.create',
        'store' => 'publisher.books.store',
    ]);

    // Routes for Publisher's Author Management
    Route::get('/publisher/authors/create', [PublisherAuthorController::class, 'create'])->name('publisher.authors.create');
    Route::post('/publisher/authors', [PublisherAuthorController::class, 'store'])->name('publisher.authors.store');
});

// Publisher registration and login routes
Route::get('/register/publisher', [RegisteredUserController::class, 'createPublisherForm'])->name('register.publisher.form');
Route::post('/register/publisher', [RegisteredUserController::class, 'storePublisher'])->name('register.publisher');
Route::get('/login/publisher', [AuthenticatedSessionController::class, 'createPublisherLoginForm'])->name('login.publisher.form');
Route::post('/login/publisher', [AuthenticatedSessionController::class, 'storePublisherLogin'])->name('login.publisher');

// Publisher dashboard and book upload routes (restricted to publisher role)
Route::middleware(['auth', 'role:publisher'])->group(function () {
    Route::get('/publisher/dashboard', function () {
        return view('publisher.dashboard');
    })->name('publisher.dashboard');

    Route::resource('publisher/books', PublisherBookController::class)->only(['create', 'store'])->names([
        'create' => 'publisher.books.create', // Route for publisher book creation
        'store' => 'publisher.books.store',
    ]);
});

// Admin routes with admin middleware and prefix
Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {

   // Admin dashboard route
   Route::get('/', [DashboardController::class, 'index'])->name('admin');

   // Publishers Activity Route
   Route::get('/publishers/activity', [AdminPublisherController::class, 'activity'])->name('admin.publishers.activity');

   // Authors CRUD routes (Admin)
   Route::resource('authors', AdminAuthorController::class, ['as' => 'admin']);

   // Books CRUD routes (Admin)
   Route::resource('books', AdminBookController::class, ['as' => 'admin']);

   // Categories CRUD routes (Admin)
   Route::resource('categories', AdminCategoryController::class, ['as' => 'admin']);

   // Book News CRUD routes (Admin)
   Route::resource('book-news', AdminBookNewsController::class, ['as' => 'admin']);
   Route::post('/books/{id}/toggleVisibility', [AdminBookController::class, 'toggleVisibility'])->name('admin.books.toggleVisibility');


   // FOR PUBLISHERS TO ALLOW HIDE/SHOW
   Route::post('/books/{id}/toggle-visibility', [AdminPublisherController::class, 'toggleVisibility'])->name('books.toggleVisibility');

});


Route::get('/test-role-middleware', function () {
    if (Auth::check() && strtolower(Auth::user()->role) === 'publisher') {
        return 'Access granted to publisher';
    } else {
        return redirect()->route('login.publisher.form')->withErrors(['access' => 'Only publishers can access this page.']);
    }
});

