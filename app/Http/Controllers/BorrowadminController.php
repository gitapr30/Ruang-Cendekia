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

        // Hitung denda untuk setiap peminjaman yang terlambat
        foreach ($borrows as $borrow) {
            $dueDate = \Carbon\Carbon::parse($borrow->tanggal_kembali);
            $lateDays = max($dueDate->diffInDays($today), 0); // Pastikan tidak negatif

            if ($lateDays > 0) {
                $denda = $lateDays * 2000;

                // Update denda jika lebih besar dari denda sebelumnya
                if ($borrow->denda < $denda) {
                    $borrow->update(['denda' => $denda]);
                }
            }
        }

        $borrowQuery = Borrow::query();

        // Filter berdasarkan pencarian
        if (request()->has('search')) {
            $search = request('search');
            $borrowQuery->whereHas('book', function (Builder $query) use ($search) {
                $query->where('title', 'like', "%$search%");
            })->orWhere('kode_peminjaman', 'like', "%$search%");
        }

        // Filter berdasarkan role user
        if (Gate::allows('isUser')) {
            $title = 'Borrowing';
            $borrowQuery->where('user_id', auth()->id());
        } elseif (Gate::allows('isAdmin')) {
            $borrowQuery->orderByDesc('status');
        }

        // Ambil data peminjaman dengan relasi user dan book
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
        // Ambil data peminjaman dengan status menunggu konfirmasi
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

        // Ambil data peminjaman yang belum jatuh tempo
        $borrows = Borrow::where('status', 'dipinjam')
            ->whereDate('tanggal_kembali', '>=', $today)
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
        // Ambil data peminjaman dengan status dikembalikan
        $borrows = Borrow::where('status', 'dikembalikan')->with(['user', 'book'])->get();

        return view('borrow.kembali', compact('borrows', 'title'));
    }

    /**
     * Tampilkan daftar denda peminjaman.
     */
    public function denda()
    {
        $title = 'Denda Peminjaman';
        $today = now();

        // Ambil setting denda dari database
        $dendaSettings = \App\Models\Change::first();
        $dendaPerHari = ($dendaSettings->denda ?? 2000) * 1000;
        $dendaHilang = ($dendaSettings->denda_hilang ?? 0) * 1000;
        $dendaRusak = ($dendaSettings->denda_hilang ?? 0) * 1000;

        // Ambil data peminjaman yang memiliki denda
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

        // Hitung denda untuk setiap peminjaman
        foreach ($borrows as $borrow) {
            if ($borrow->keterangan == 'terlambat' ||
                ($borrow->status == 'dipinjam' && $borrow->tanggal_kembali < $today)) {

                $dueDate = \Carbon\Carbon::parse($borrow->tanggal_kembali);
                $lateDays = max($dueDate->diffInDays($today), 0);
                $dendaTerlambat = $lateDays * $dendaPerHari;

                // Update status jika terlambat
                if ($borrow->status == 'dipinjam' && $borrow->tanggal_kembali < $today) {
                    $borrow->update([
                        'status' => 'terlambat',
                        'keterangan' => 'terlambat'
                    ]);
                }

                // Update denda jika berbeda
                if ($borrow->denda != $dendaTerlambat) {
                    $borrow->update(['denda' => $dendaTerlambat]);
                }
            }
        }

        return view('borrow.denda', compact('borrows', 'title', 'dendaPerHari', 'dendaHilang', 'dendaRusak'));
    }

    /**
     * Update denda peminjaman.
     */
    public function updateDenda(Request $request)
    {
        $borrow = Borrow::find($request->borrow_id);
        $dendaSettings = \App\Models\Change::first();

        if (!$borrow) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Set keterangan dan hitung denda berdasarkan jenis
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

    /**
     * Tampilkan laporan peminjaman.
     */
    public function laporan()
    {
        $title = 'Laporan Peminjaman';
        $today = now();
        $dendaSettings = \App\Models\Change::first();

        // Ambil semua data peminjaman
        $borrows = Borrow::with(['user', 'book'])->get();

        // Hitung denda untuk setiap peminjaman
        foreach ($borrows as $borrow) {
            if ($borrow->status === 'dipinjam' || $borrow->status === 'terlambat') {
                $dueDate = \Carbon\Carbon::parse($borrow->tanggal_kembali);
                $lateDays = max($dueDate->diffInDays($today), 0);

                // Hitung denda berdasarkan keterangan
                if ($borrow->keterangan == 'terlambat') {
                    $denda = $lateDays * ($dendaSettings->denda * 1000);
                } elseif ($borrow->keterangan == 'hilang') {
                    $denda = $dendaSettings->denda_hilang * 1000;
                } elseif ($borrow->keterangan == 'rusak') {
                    $denda = $dendaSettings->denda_rusak * 1000;
                } else {
                    $denda = $lateDays * ($dendaSettings->denda * 1000); // Default denda terlambat
                }

                // Update denda jika lebih besar
                if ($borrow->denda < $denda) {
                    $borrow->update(['denda' => $denda]);
                }
            }
        }

        return view('borrow.laporan', compact('borrows', 'title'));
    }

    /**
     * Proses pengembalian buku.
     */
    public function kembalikanBuku($id)
    {
        $borrow = Borrow::findOrFail($id);
        $book = Books::findOrFail($borrow->book_id);

        // Validasi status peminjaman
        if ($borrow->status !== 'dipinjam' && $borrow->status !== 'terlambat') {
            return back()->with('errorMessage', 'Buku ini tidak dalam status dipinjam.');
        }

        // Hitung denda jika terlambat
        $today = now();
        $dueDate = \Carbon\Carbon::parse($borrow->tanggal_kembali);
        $lateDays = $today->diffInDays($dueDate, false);
        $denda = $lateDays > 0 ? $lateDays * 2000 : 0;

        // Update status peminjaman
        $borrow->update([
            'status' => 'dikembalikan',
            'denda' => $denda
        ]);

        // Tambah stok buku
        $book->increment('stok');

        return back()->with('successMessage', 'Buku berhasil dikembalikan dan stok diperbarui.');
    }

    /**
     * Proses pengembalian buku (alternatif).
     */
    public function returnBook(Request $request, Borrow $borrow)
    {
        // Validasi status sebelum pengembalian
        if ($borrow->status !== 'dikembalikan') {
            $borrow->denda = $borrow->calculated_denda;
            $borrow->status = 'dikembalikan';
            $borrow->save();
        }

        return redirect()->route('laporan.index')->with('success', 'Buku berhasil dikembalikan');
    }

    /**
     * Update status peminjaman dari halaman denda.
     */
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

        // Hitung denda berdasarkan keterangan
        $denda = 0;
        $status = 'dikembalikan';
        $tanggal_dikembalikan = now();

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

        // Update stok buku jika tidak hilang
        if ($request->keterangan != 'hilang') {
            $book->increment('stok');
        }

        // Update data peminjaman
        $borrow->update([
            'status' => $status,
            'keterangan' => $request->keterangan,
            'denda' => $denda,
            'tanggal_dikembalikan' => $tanggal_dikembalikan
        ]);

        return back()->with('successMessage', 'Buku berhasil dikembalikan dengan status ' . $request->keterangan);
    }

    /**
     * Update status peminjaman.
     */
    public function update(Request $request)
    {
        $request->validate([
            'borrow_id' => 'required|exists:borrows,id',
            'status' => 'required|string',
            'keterangan' => 'sometimes|string'
        ]);

        $borrow = Borrow::findOrFail($request->borrow_id);
        $book = Books::findOrFail($borrow->book_id);

        // Proses peminjaman
        if ($request->status === 'dipinjam') {
            if ($book->stok <= 0) {
                return back()->with('errorMessage', 'Stok buku habis.');
            }
            $book->decrement('stok');
        }
        // Proses pengembalian
        elseif ($request->status === 'dikembalikan') {
            $book->increment('stok');
            $borrow->update([
                'status' => 'dikembalikan',
                'keterangan' => 'dikembalikan',
                'denda' => 0
            ]);
            return back()->with('successMessage', 'Buku berhasil dikembalikan.');
        }

        // Update status peminjaman
        $borrow->update(['status' => $request->status]);
        return back()->with('successMessage', 'Status peminjaman diperbarui.');
    }

    /**
     * Hitung denda peminjaman (accessor).
     */
    public function getCalculatedDendaAttribute()
    {
        if ($this->status === 'dikembalikan') {
            return $this->denda;
        }

        $tanggal_kembali = Carbon::parse($this->tanggal_kembali);
        $hari_terlambat = max(0, now()->diffInDays($tanggal_kembali, false));
        $tarif_denda = 1000;

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
