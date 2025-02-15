<?php

namespace App\Http\Controllers;

use App\Models\Books;
use App\Models\Borrow;
use App\Models\History;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;


class BorrowController extends Controller
{
    /**
     * Tampilkan daftar peminjaman berdasarkan peran pengguna.
     */
    public function index(Request $request)
{
    $books = Books::all();
    $selectedBook = $request->book_id ? Books::find($request->book_id) : null;

    return view('borrow.borrow', compact('books', 'selectedBook'));
}


public function selectBook(Request $request)
{
    $book = Book::find($request->book_id);
    return response()->json(['book' => $book]);
}

    /**1
     * Simpan data peminjaman baru dengan status "Menunggu Konfirmasi".
     */
    public function store(Request $request)
    {
        // Validasi input dengan pesan error khusus
        $request->validate([
            'user_id' => [
                'required',
                'exists:users,id'
            ],
            'book_id' => [
                'required',
                'exists:books,id'
            ],
            'tanggal_pinjam' => [
                'required',
                'date'
            ],
            'tanggal_kembali' => [
                'required',
                'date',
                'after_or_equal:tanggal_pinjam'
            ],
        ], [
            'user_id.required' => 'User wajib dipilih.',
            'user_id.exists' => 'User tidak ditemukan dalam sistem.',
            'book_id.required' => 'Buku wajib dipilih.',
            'book_id.exists' => 'Buku tidak ditemukan dalam sistem.',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tanggal_pinjam.date' => 'Tanggal pinjam harus dalam format tanggal yang valid.',
            'tanggal_kembali.required' => 'Tanggal kembali wajib diisi.',
            'tanggal_kembali.date' => 'Tanggal kembali harus dalam format tanggal yang valid.',
            'tanggal_kembali.after_or_equal' => 'Tanggal kembali harus setelah atau sama dengan tanggal pinjam.',
        ]);

        // Cek stok buku sebelum meminjam
        $book = Books::findOrFail($request->book_id);
        if ($book->stok <= 0) {
            return back()->with('errorMessage', 'Buku tidak tersedia atau stok habis.');
        }

        // Kurangi stok buku
        $book->decrement('stok');

        // Buat peminjaman baru
        Borrow::create([
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'kode_peminjaman' => hexdec(substr(uniqid(), -8)),
            'status' => 'menunggu konfirmasi',
        ]);

        return redirect()->route('borrow.index')->with('successMessage', 'Pinjaman berhasil diajukan.');
    }

    /**
     * Perbarui status peminjaman:
     * - "Menunggu Konfirmasi" → "Dipinjam" (Admin mengonfirmasi, stok buku berkurang)
     * - "Dipinjam" → "Dikembalikan" (Admin mengembalikan, stok buku bertambah)
     */
    public function update(Request $request)
    {
        $request->validate([
            'borrow_id' => 'required|exists:borrows,id',
            'status' => 'required|string'
        ]);

        $borrow = Borrow::findOrFail($request->borrow_id);
        $book = Books::findOrFail($borrow->book_id);

        if ($request->status === 'dipinjam') {
            if ($book->stok <= 0) {
                return back()->with('errorMessage', 'Stok buku habis.');
            }
            if ($request->status === 'dikembalikan') {
                $book->increment('stok');
            }

        }

        // Cek apakah buku dikembalikan setelah tanggal kembali yang seharusnya
        $today = now(); // Tanggal hari ini
        $dueDate = \Carbon\Carbon::parse($borrow->tanggal_kembali); // Tanggal kembali yang seharusnya
        $lateDays = $today->diffInDays($dueDate, false); // Selisih hari (negative jika lebih cepat)

        if ($lateDays > 0) {
            // Jika terlambat, hitung denda
            $denda = $lateDays * 500000; // Assuming 500,000 per day
            $borrow->update(['denda' => $denda]);
        } else {
            // No fine if returned on time
            $borrow->update(['denda' => 0]);
        }

        $book->increment('stok');


        $borrow->update(['status' => $request->status]);

        return back()->with('successMessage', 'Status peminjaman diperbarui.');
    }

    /**
     * Menampilkan history peminjaman berdasarkan pengguna.
     */
    public function history(Request $request)
    {
        $title = 'History Borrowing';
        $userId = Auth::id();

        if (!$userId) {
            return redirect()->route('login')->with('errorMessage', 'Anda harus login terlebih dahulu.');
        }

        $history = Borrow::where('user_id', $userId)->with('book');

        if (request()->has('book_id')) {
            $history->where('book_id', request('book_id'));
        }

        $history = $history->paginate(6);

        return view('borrow.history', compact('history', 'title'));
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
