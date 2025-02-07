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


class BorrowadminController extends Controller
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
     * Simpan data peminjaman baru dengan status "Menunggu Konfirmasi".
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'user_id'           => 'required|exists:users,id',
            'book_id'           => 'required|exists:books,id',
            'tanggal_pinjam'    => 'required|date',
            'tanggal_kembali'   => 'required|date|after_or_equal:tanggal_pinjam',
        ]);
    
        $book = Books::findOrFail($request->book_id);
    
        if ($book->stok <= 0) {
            return back()->with('errorMessage', 'Buku tidak tersedia untuk dipinjam');
        }
    
        $kode_peminjaman = hexdec(substr(uniqid(), -8));
    
        // Simpan peminjaman dengan status awal "Menunggu Konfirmasi"
        $borrow = Borrow::create([
            'user_id'         => $validateData['user_id'],
            'book_id'         => $validateData['book_id'],
            'tanggal_pinjam'  => $validateData['tanggal_pinjam'],
            'tanggal_kembali' => $validateData['tanggal_kembali'],
            'kode_peminjaman' => $kode_peminjaman,
            
            'status'          => 'menunggu konfirmasi',
        ]);
    
        return redirect()->route('history.index')->with('successMessage', 'Pinjaman sedang menunggu konfirmasi. Silakan cek di history.');
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
        $book->decrement('stok');
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
    
    // Ambil ID pengguna dari cookie
    $userId = Auth::user()->id;

    // // Pastikan ID pengguna ada dan valid
    if ($userId) {
        // Ambil history peminjaman berdasarkan user ID yang diambil dari cookie
        $history = Borrow::where('user_id', $userId)->with('book')->get();
    } else {
        // Jika cookie tidak ada, bisa redirect atau beri pesan error
        return redirect()->route('login')->with('errorMessage', 'Anda harus login terlebih dahulu.');
    }

    $history = Borrow::with('book')->where('user_id', auth()->id())->paginate(6);
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
