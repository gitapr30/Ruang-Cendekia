<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PDFController;
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
    Route::post('/borrow', [BorrowController::class, 'index'])->name('borrow.index'); // POST untuk menyimpan peminjaman


    
    // Tambahan route untuk form peminjaman dan penyimpanan
    Route::get('/barcode/{kodePeminjaman}', [BarcodeController::class, 'saveBarcode']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/reviews', [ReviewController::class, 'index'])->name('review.index');
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/generate-pdf/{id}', [PDFController::class, 'generatePDF']);
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
