<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
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
    $book = Books::find($request->book_id);
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


        // Buat peminjaman baru
        Borrow::create([
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'kode_peminjaman' => hexdec(substr(uniqid(), -8)),
            'status' => 'menunggu konfirmasi',
        ]);

        return redirect()->route('borrow.index', ['book_id' => $request->book_id])->with('successMessage', 'Pinjaman berhasil diajukan.');
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
        } elseif ($request->status === 'dikembalikan') {
                $book->increment('stok');
            }

        // Cek apakah buku dikembalikan setelah tanggal kembali yang seharusnya
        $today = now(); // Tanggal hari ini
        $dueDate = \Carbon\Carbon::parse($borrow->tanggal_kembali); // Tanggal kembali yang seharusnya
        $lateDays = $today->diffInDays($dueDate, false); // Selisih hari (negative jika lebih cepat)

        if ($lateDays > 0) {
            // Jika terlambat, hitung denda
            $denda = $lateDays * 500; // Assuming 500,000 per day
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

        // Ambil semua riwayat peminjaman dengan relasi ke buku
        $history = Borrow::where('user_id', $userId)->with('book');

        // Filter berdasarkan book_id jika tersedia
        if ($request->has('book_id')) {
            $history->where('book_id', $request->book_id);
        }

        // Filter berdasarkan pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $history->whereHas('book', function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%");
            });
        }

        $history = $history->paginate(6);

        return view('borrow.history', compact('history', 'title'));
    }

    public function getNotifications()
{
    $userId = Auth::id();
    if (!$userId) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized',
            'notifications' => []
        ], 401);
    }

    $today = Carbon::today();
    $threeDaysLater = $today->copy()->addDays(3);

    try {
        // Peminjaman aktif yang akan jatuh tempo dalam 3 hari
        $upcomingReturns = Borrow::with('book')
            ->where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->whereBetween('tanggal_kembali', [$today, $threeDaysLater])
            ->get()
            ->map(function ($borrow) use ($today) {
                $returnDate = Carbon::parse($borrow->tanggal_kembali);
                $daysLeft = $today->diffInDays($returnDate, false);

                if ($daysLeft === 0) {
                    $message = 'Buku "' . $borrow->book->title . '" harus dikembalikan hari ini!';
                } elseif ($daysLeft === 1) {
                    $message = 'Buku "' . $borrow->book->title . '" kurang 1 hari jatuh tempo!';
                } else {
                    $message = 'Buku "' . $borrow->book->title . '" harus dikembalikan dalam ' . $daysLeft . ' hari';
                }

                return [
                    'type' => 'reminder',
                    'message' => $message,
                    'book_id' => $borrow->book_id,
                    'return_date' => $borrow->tanggal_kembali,
                    'days_left' => $daysLeft,
                    'created_at' => now()
                ];
            });

        // Peminjaman yang sudah lewat jatuh tempo
        $overdueReturns = Borrow::with('book')
            ->where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->whereDate('tanggal_kembali', '<', $today)
            ->get()
            ->map(function ($borrow) use ($today) {
                $daysOverdue = $today->diffInDays(Carbon::parse($borrow->tanggal_kembali));

                return [
                    'type' => 'overdue',
                    'message' => 'Buku "' . $borrow->book->title . '" sudah terlambat ' . $daysOverdue . ' hari!',
                    'book_id' => $borrow->book_id,
                    'return_date' => $borrow->tanggal_kembali,
                    'days_overdue' => $daysOverdue,
                    'created_at' => now()
                ];
            });

        $notifications = $upcomingReturns->merge($overdueReturns);

        return response()->json([
            'success' => true,
            'notifications' => $notifications->values()->all(),
            'unread_count' => $notifications->count()
        ]);

    } catch (\Exception $e) {
        \Log::error('Error fetching notifications: '.$e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error fetching notifications',
            'error' => $e->getMessage(),
            'notifications' => []
        ], 500);
    }
}

public function markAsRead()
{
    try {
        // Logic untuk menandai notifikasi sebagai sudah dibaca
        // Contoh sederhana - dalam implementasi nyata Anda mungkin perlu update database
        return response()->json([
            'success' => true,
            'message' => 'Notifications marked as read'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error marking notifications as read'
        ], 500);
    }
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
