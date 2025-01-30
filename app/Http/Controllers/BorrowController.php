<?php

namespace App\Http\Controllers;

use App\Models\Books;
use App\Models\Borrow;
use App\Models\History;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BorrowController extends Controller
{
    /**
     * Tampilkan daftar peminjaman berdasarkan peran pengguna.
     */
    public function index()
    {
        $title = '';
        $borrowQuery = Borrow::query();

        if (request()->has('search')) {
            $search = request('search');
            $borrowQuery->whereHas('book', function (Builder $query) use ($search) {
                $query->where('title', 'like', "%$search%");
            })->orWhere('kode_peminjaman', 'like', "%$search%");
        }

        if (Gate::allows('isUser')) {
            $title = 'Borrowing';
            $borrowQuery->where('user_id', auth()->id());
        } elseif (Gate::allows('isAdmin')) {
            $title = 'All Borrowing';
            $borrowQuery->orderByDesc('status');
        }

        $borrows = $borrowQuery->with(['user', 'book'])->get();

        // ✅ Perbaikan: Menambahkan query untuk users dan books
        $users = \App\Models\User::all();
        $books = Books::all();

        $user = auth()->user();
        $book = null;
        if (request()->has('book_id')) {
            $book = Books::find(request('book_id'));
        }

        return view('borrow.borrow', compact('user', 'borrows', 'title', 'users', 'books', 'book'));
    }

    /**
     * Simpan data peminjaman baru.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'user_id'           => 'required|exists:users,id',
            'book_id'           => 'required|exists:books,id',
            'status'            => 'required|string',
            'denda'             => 'nullable|numeric',
            'tanggal_pinjam'    => 'required|date',
            'tanggal_kembali'   => 'required|date|after_or_equal:tanggal_pinjam',
            'kode_peminjaman'   => 'required|string|unique:borrows,kode_peminjaman',
        ]);

        $book = Books::findOrFail($request->book_id);

        if ($book->stok <= 0) {
            return back()->with('errorMessage', 'Buku tidak tersedia untuk dipinjam');
        }

        // Kurangi stok buku
        $book->decrement('stok');

        // Simpan peminjaman
        Borrow::create($validateData);

        // Simpan riwayat peminjaman
        History::create([
            'user_id'  => $validateData['user_id'],
            'book_id'  => $validateData['book_id'],
        ]);

        // Redirect back to the borrow index page
        return redirect()->route('borrow.index')->with('successMessage', 'Buku berhasil dipinjam');
    }

    /**
     * Perbarui status peminjaman (pengembalian buku).
     */
    public function update(Request $request, Borrow $borrow)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $borrow->update(['status' => $request->status]);

        if ($request->status === 'returned') {
            $book = Books::findOrFail($borrow->book_id);
            $book->increment('stok');
        }

        return back()->with('successMessage', 'Proses peminjaman diperbarui');
    }

    /**
     * Hapus data peminjaman.
     */
    public function destroy(Borrow $borrow)
    {
        if ($borrow->delete()) {
            return back()->with('successMessage', 'Peminjaman berhasil dihapus');
        }

        return back()->with('errorMessage', 'Gagal menghapus peminjaman');
    }
}
