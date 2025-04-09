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
use Carbon\Carbon;


class BorrowadminController extends Controller
{
    /**
     * Tampilkan daftar peminjaman berdasarkan status.
     */
    public function index()
{
    $title = 'All Borrowing';
    $today = now();

    // Update otomatis status jika sudah terlambat
    Borrow::where('status', 'dipinjam')
        ->whereDate('tanggal_kembali', '<', $today)
        ->update(['status' => 'terlambat']);

    // Ambil semua peminjaman yang terlambat
    $borrows = Borrow::where('status', 'terlambat')->get();

    foreach ($borrows as $borrow) {
        $dueDate = \Carbon\Carbon::parse($borrow->tanggal_kembali);
        $lateDays = max($dueDate->diffInDays($today), 0); // Pastikan tidak negatif

        if ($lateDays > 0) {
            $denda = $lateDays * 2000;

            // Pastikan denda selalu bertambah jika sudah ada sebelumnya
            if ($borrow->denda < $denda) {
                $borrow->update(['denda' => $denda]);
            }
        }
    }

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
        $borrowQuery->orderByDesc('status');
    }

    $borrows = $borrowQuery->with(['user', 'book'])->get();
    $users = \App\Models\User::all();
    $books = Books::all();

    return view('borrow.borrow', compact('borrows', 'title', 'users', 'books'));
}

    /**
     * Tampilkan daftar peminjaman yang masih menunggu konfirmasi.
     */

    public function konfirmasi()
    {
        $title = 'Menunggu Konfirmasi';
        $borrows = Borrow::where('status', 'menunggu konfirmasi')->with(['user', 'book'])->get();

        return view('borrow.konfirmasi', compact('borrows', 'title'));
    }

    /**
     * Tampilkan daftar peminjaman yang sedang dipinjam.
     */

     public function dipinjam()
     {
         $title = 'Buku Sedang Dipinjam';
         $today = now();

         $borrows = Borrow::where('status', 'dipinjam')
             ->whereDate('tanggal_kembali', '>=', $today) // Only books not yet due
             ->with(['user', 'book'])
             ->get();

         return view('borrow.dipinjam', compact('borrows', 'title'));
     }
    /**
     * Tampilkan daftar peminjaman yang sudah dikembalikan.
     */
    public function kembali()
    {
        $title = 'Buku Sudah Dikembalikan';
        $borrows = Borrow::where('status', 'dikembalikan')->with(['user', 'book'])->get();

        return view('borrow.kembali', compact('borrows', 'title'));
    }

    // BorrowadminController.php

    public function denda()
{
    $title = 'Denda Peminjaman';
    $today = now();

    // Get all fine settings from changes table
    $dendaSettings = \App\Models\Change::first();
    $dendaPerHari = ($dendaSettings->denda ?? 2000) * 1000;
    $dendaHilang = ($dendaSettings->denda_hilang ?? 0) * 1000;
    $dendaRusak = ($dendaSettings->denda_rusak ?? 0) * 1000;

    // Get borrows that:
    // 1. Have keterangan (terlambat/hilang/rusak) OR
    // 2. Are overdue (status dipinjam but past return date)
    $borrows = Borrow::where(function($query) use ($today) {
            $query->where(function($q) {
                    $q->where('keterangan', 'terlambat')
                      ->orWhere('keterangan', 'hilang')
                      ->orWhere('keterangan', 'rusak');
                })
                ->orWhere(function($q) use ($today) {
                    $q->where('status', 'dipinjam')
                      ->whereDate('tanggal_kembali', '<', $today);
                });
        })
        ->where('status', '!=', 'dikembalikan')
        ->with(['user', 'book'])
        ->get();

    // Calculate late fines for those marked as late or overdue
    foreach ($borrows as $borrow) {
        if ($borrow->keterangan == 'terlambat' ||
            ($borrow->status == 'dipinjam' && $borrow->tanggal_kembali < $today)) {

            $dueDate = \Carbon\Carbon::parse($borrow->tanggal_kembali);
            $lateDays = max($dueDate->diffInDays($today), 0);
            $dendaTerlambat = $lateDays * $dendaPerHari;

            // Update status to terlambat if it's dipinjam and overdue
            if ($borrow->status == 'dipinjam' && $borrow->tanggal_kembali < $today) {
                $borrow->update([
                    'status' => 'terlambat',
                    'keterangan' => 'terlambat'
                ]);
            }

            // Update denda in database if it's different
            if ($borrow->denda != $dendaTerlambat) {
                $borrow->update(['denda' => $dendaTerlambat]);
            }
        }
    }

    return view('borrow.denda', compact('borrows', 'title', 'dendaPerHari', 'dendaHilang', 'dendaRusak'));
}
public function updateDenda(Request $request)
{
    $borrow = Borrow::find($request->borrow_id);
    $dendaSettings = \App\Models\Change::first();

    if (!$borrow) {
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }

    $borrow->keterangan = $request->keterangan;

    if ($request->keterangan == 'terlambat') {
        $daysLate = now()->diffInDays($borrow->tanggal_kembali, false);
        $borrow->denda = $daysLate > 0 ? $daysLate * ($dendaSettings->denda * 1000) : 0;
    } elseif ($request->keterangan == 'hilang') {
        $borrow->denda = $dendaSettings->denda_hilang * 1000;
    } elseif ($request->keterangan == 'rusak') {
        $borrow->denda = $dendaSettings->denda_hilang * 1000;
    } else {
        $borrow->denda = 0;
    }

    $borrow->save();

    return redirect()->back()->with('success', 'Denda berhasil diperbarui.');
}


public function laporan()
{
    $title = 'Laporan Peminjaman';
    $today = now();
    $dendaSettings = \App\Models\Change::first();

    // Ambil semua data peminjaman
    $borrows = Borrow::with(['user', 'book'])->get();

    // Perhitungan denda jika status masih dipinjam dan sudah melewati tanggal kembali
    foreach ($borrows as $borrow) {
        if ($borrow->status === 'dipinjam' || $borrow->status === 'terlambat') {
            $dueDate = \Carbon\Carbon::parse($borrow->tanggal_kembali);
            $lateDays = max($dueDate->diffInDays($today), 0);

            // Calculate fine based on keterangan
            if ($borrow->keterangan == 'terlambat') {
                $denda = $lateDays * ($dendaSettings->denda * 1000);
            } elseif ($borrow->keterangan == 'hilang') {
                $denda = $dendaSettings->denda_hilang * 1000;
            } elseif ($borrow->keterangan == 'rusak') {
                $denda = $dendaSettings->denda_rusak * 1000;
            } else {
                $denda = $lateDays * ($dendaSettings->denda * 1000); // Default to late fine
            }

            if ($borrow->denda < $denda) {
                $borrow->update(['denda' => $denda]);
            }
        }
    }

    return view('borrow.laporan', compact('borrows', 'title'));
}

public function kembalikanBuku($id)
{
    $borrow = Borrow::findOrFail($id);
    $book = Books::findOrFail($borrow->book_id);

    // Pastikan status saat ini adalah "dipinjam" atau "terlambat"
    if ($borrow->status !== 'dipinjam' && $borrow->status !== 'terlambat') {
        return back()->with('errorMessage', 'Buku ini tidak dalam status dipinjam.');
    }

    // Menghitung denda jika ada keterlambatan
    $today = now();
    $dueDate = \Carbon\Carbon::parse($borrow->tanggal_kembali);
    $lateDays = $today->diffInDays($dueDate, false);
    $denda = $lateDays > 0 ? $lateDays * 2000 : 0;

    // Perbarui status peminjaman
    $borrow->update([
        'status' => 'dikembalikan',
        'denda' => $denda
    ]);

    // Tambahkan stok buku kembali
    $book->increment('stok');

    return back()->with('successMessage', 'Buku berhasil dikembalikan dan stok diperbarui.');
}

public function returnBook(Request $request, Borrow $borrow)
{
    if ($borrow->status !== 'dikembalikan') {
        $borrow->denda = $borrow->calculated_denda;
        $borrow->status = 'dikembalikan';
        $borrow->save();
    }

    return redirect()->route('laporan.index')->with('success', 'Buku berhasil dikembalikan');
}


    /**
     * Perbarui status peminjaman.
     */
    // Add this new method for handling returns from denda page
    public function updateFromDenda(Request $request)
{
    $request->validate([
        'borrow_id' => 'required|exists:borrows,id',
        'status' => 'required|string',
        'keterangan' => 'required|string'
    ]);

    $borrow = Borrow::findOrFail($request->borrow_id);
    $book = Books::findOrFail($borrow->book_id);
    $dendaSettings = \App\Models\Change::first();

    // Calculate fine based on keterangan
    $denda = 0;
    $status = 'dikembalikan';
    $tanggal_dikembalikan = now(); // Tanggal pengembalian aktual

    switch ($request->keterangan) {
        case 'terlambat':
            $lateDays = max(now()->diffInDays(\Carbon\Carbon::parse($borrow->tanggal_kembali)), 0);
            $denda = $lateDays * ($dendaSettings->denda * 1000);
            break;
        case 'hilang':
            $denda = $dendaSettings->denda_hilang * 1000;
            break;
        case 'rusak':
            $denda = $dendaSettings->denda_rusak * 1000;
            break;
    }

    // Update book stock and borrow status
    if ($request->keterangan != 'hilang') {
        $book->increment('stok');
    }

    $borrow->update([
        'status' => $status,
        'keterangan' => $request->keterangan,
        'denda' => $denda,
        'tanggal_dikembalikan' => $tanggal_dikembalikan // Simpan tanggal pengembalian
    ]);

    return back()->with('successMessage', 'Buku berhasil dikembalikan dengan status ' . $request->keterangan);
}

// Modify the existing update method for regular returns
public function update(Request $request)
{
    $request->validate([
        'borrow_id' => 'required|exists:borrows,id',
        'status' => 'required|string',
        'keterangan' => 'sometimes|string'
    ]);

    $borrow = Borrow::findOrFail($request->borrow_id);
    $book = Books::findOrFail($borrow->book_id);

    if ($request->status === 'dipinjam') {
        if ($book->stok <= 0) {
            return back()->with('errorMessage', 'Stok buku habis.');
        }
        $book->decrement('stok');
    } elseif ($request->status === 'dikembalikan') {
        // For regular returns (not from denda page)
        $book->increment('stok');
        $borrow->update([
            'status' => 'dikembalikan',
            'keterangan' => 'dikembalikan',
            'denda' => 0
        ]);
        return back()->with('successMessage', 'Buku berhasil dikembalikan.');
    }

    $borrow->update(['status' => $request->status]);
    return back()->with('successMessage', 'Status peminjaman diperbarui.');
}

    public function getCalculatedDendaAttribute()
{
    if ($this->status === 'dikembalikan') {
        return $this->denda; // Gunakan denda terakhir yang tersimpan
    }

    $tanggal_kembali = Carbon::parse($this->tanggal_kembali);
    $hari_terlambat = max(0, now()->diffInDays($tanggal_kembali, false)); // Hitung hanya jika terlambat
    $tarif_denda = 1000; // Sesuaikan dengan tarif per hari

    return $hari_terlambat * $tarif_denda;
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
