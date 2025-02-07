<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ProfileController;
use App\Models\Borrow;
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

Route::middleware(['auth'])->group(function(){
    Route::get('/', function(){
        return redirect()->route('books.index');
    });
    Route::resource("/books", BooksController::class)->scoped([
        'book' => 'slug',
    ]);
    Route::resource("/category", CategoryController::class)->scoped([
        'category' => 'slug',
    ]);
    Route::get('/borrow', [BorrowController::class, 'index'])->name('borrow.index'); // GET untuk menampilkan daftar peminjaman
    Route::post('/borrow', [BorrowController::class, 'index'])->name('borrow.index');
    Route::post('/borrow-store', [BorrowController::class, 'store'])->name('borrow.store');
    Route::post('/borrow-update', [BorrowController::class, 'update'])->name('borrow.update');

    Route::get('/books', [BooksController::class, 'show'])->name('books.show');
    Route::get('/books/{slug}', [BooksController::class, 'show'])->name('books.show');
    Route::get('/books', [BooksController::class, 'index'])->name('books.index');
    
    // Tambahan route untuk form peminjaman dan penyimpanan
    Route::get('/barcode/{kodePeminjaman}', [BarcodeController::class, 'saveBarcode']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
 
    Route::post('/review-store', [ReviewController::class, 'store'])->name('review.store');
    Route::get('/getwishlist', [WishlistController::class, 'index'])->name('getwishlist.index');
    Route::get('/wishlist', [BooksController::class, 'indexWishlist'])->name('wishlist.index');
    Route::post('/wishlist/{book}', [BooksController::class, 'storeWishlist'])->name('wishlist.store');

    Route::get('/history', [BorrowController::class, 'history'])->name('history.index');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/generate-pdf/{id}', [PDFController::class, 'generatePDF']);

    // Route untuk profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['guest'])->group(function(){
    Route::get('/login', function () {
        return view('login', [
            'title' => 'Login',
        ]);
    })->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', function () {
        return view('register', [
            'title' => 'Register',
        ]);
    })->name('register');
    Route::post('/register', [AuthController::class,'register']);
});
