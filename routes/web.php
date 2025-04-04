<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\BorrowadminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookshelvesController;
use App\Http\Controllers\ChangeController;
use App\Http\Controllers\LandingController;
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
Route::get('/', [LandingController::class, 'index'])->name('landing');



Route::get('/login', function () {
    return view('login', [
        'title' => 'Login',
    ]);
})->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('books.index');
    });

    Route::resource("/books", BooksController::class)->scoped([
        'book' => 'slug',
    ]);
    // Route lainnya tetap sama...
    Route::resource("/category", CategoryController::class)->scoped([
        'category' => 'slug',
    ]);
    Route::get('/borrow', [BorrowController::class, 'index'])->name('borrow.index');
    Route::post('/borrow-store', [BorrowController::class, 'store'])->name('borrow.store');
    Route::post('/borrow-update', [BorrowController::class, 'update'])->name('borrow.update');
    Route::post('/borrow/selectBook', [BorrowController::class, 'selectBook'])->name('borrow.selectBook');

    Route::get('/borrow/notifications', [BorrowController::class, 'getNotifications']);
    Route::post('/borrow/mark-as-read', [BorrowController::class, 'markAsRead']);

    Route::get('/borrow/konfirmasi', [BorrowadminController::class, 'konfirmasi'])->name('borrow.konfirmasi');
    Route::get('/borrow/dipinjam', [BorrowadminController::class, 'dipinjam'])->name('borrow.dipinjam');
    Route::get('/borrow/kembali', [BorrowadminController::class, 'kembali'])->name('borrow.kembali');
    Route::get('/borrow/denda', [BorrowadminController::class, 'denda'])->name('borrow.denda');
    Route::post('/borrow/update-denda', [BorrowadminController::class, 'updateDenda'])->name('borrow.updateDenda');
    Route::get('/borrow/laporan', [BorrowadminController::class, 'laporan'])->name('borrow.laporan');

    Route::post('/borrow/return/{id}', [BorrowadminController::class, 'kembalikanBuku'])->name('borrow.return');

    Route::post('/register', [AuthController::class, 'register'])->name('register');


    // Route::get('/borrow', [BorrowadminController::class, 'index'])->name('borrow.index'); // GET untuk menampilkan daftar peminjaman
    Route::post('/borrow', [BorrowadminController::class, 'index'])->name('borrow.index');
    // Route::post('/borrow-store', [BorrowadminController::class, 'store'])->name('borrow.store');
    Route::post('/borrow-update', [BorrowadminController::class, 'update'])->name('borrow.update');
    // Add this new route
    Route::post('/borrow/update-from-denda', [BorrowadminController::class, 'updateFromDenda'])->name('borrow.updateFromDenda');

    //ubah
    Route::get('/change', [ChangeController::class, 'index'])->name('change.index');
    Route::post('/change-store', [ChangeController::class, 'store'])->name('change.store');
    Route::get('/change/{id}/edit', [ChangeController::class, 'edit'])->name('change.edit');
    Route::put('/change/{id}', [ChangeController::class, 'update'])->name('change.update');
    Route::delete('/change/{id}', [ChangeController::class, 'destroy'])->name('change.destroy');
    Route::get('/change/{id}', [ChangeController::class, 'show'])->name('change.show');



    //rak
    Route::get('/bookshelves/create', [BookshelvesController::class, 'create'])->name('bookshelves.create');
Route::get('/bookshelves', [BookshelvesController::class, 'index'])->name('bookshelves.index');
Route::post('/bookshelves', [BookshelvesController::class, 'store'])->name('bookshelves.store');
Route::get('/bookshelves/{bookshelves}/edit', [BookshelvesController::class, 'edit'])->name('bookshelves.edit');

Route::put('/bookshelves/{id}', [BookshelvesController::class, 'update'])->name('bookshelves.update');
Route::delete('/bookshelves/{id}', [BookshelvesController::class, 'destroy'])->name('bookshelves.destroy');
Route::get('/bookshelves/{id}', [BookshelvesController::class, 'show'])->name('bookshelves.show');



    Route::get('/books', [BooksController::class, 'show'])->name('books.show');
    Route::get('/books/{slug}', [BooksController::class, 'show'])->name('books.show');
    Route::get('/books', [BooksController::class, 'index'])->name('books.index');
    // Route::get('/books/{slug}/detail', [BooksController::class, 'detail'])->name('books.detail');


    // Tambahan route untuk form peminjaman dan penyimpanan
    Route::get('/barcode/{kodePeminjaman}', [BarcodeController::class, 'saveBarcode']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');


    Route::post('/review-store', [ReviewController::class, 'store'])->name('review.store');
    // Route::get('/getwishlist', [WishlistController::class, 'index'])->name('getwishlist.index');
    Route::get('/getwishlist', [WishlistController::class, 'getAdminWishlist'])->name('getwishlist.index');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{slug}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');


    Route::get('/history', [BorrowController::class, 'history'])->name('history.index');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/generate-pdf/{id}', [PDFController::class, 'generatePDF']);

    // Route untuk profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/user', [AuthController::class, 'index'])->name('user.index');

Route::middleware(['guest'])->group(function () {
    // Route::get('/login', function () {
    //     return view('login', [
    //         'title' => 'Login',
    //     ]);
    // })->name('login');
    // Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', function () {
        return view('register', [
            'title' => 'Register',
        ]);
    })->name('register');
    // Route::post('/register', [AuthController::class, 'register'])->name('register');

});
