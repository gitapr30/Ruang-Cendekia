<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Borrow;

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->shareCommonData();
    }

    public function index()
    {
        $userId = Auth::id();
        $notifications = $this->getNotificationData($userId);

        return view('notification.index', [
            'notifications' => $notifications,
            'unreadCount' => count($notifications)
        ]);
    }

    public function getNotifications()
    {
        $userId = Auth::id();
        $notifications = $this->getNotificationData($userId);

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unreadCount' => count($notifications)
        ]);
    }

    private function getNotificationData($userId)
    {
        $today = Carbon::today();
        $notifications = [];

        // Get all active borrowed books - hanya baca data, tidak mengubah
        $borrowedBooks = Borrow::with('book')
            ->where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->get()
            ->map(function ($borrow) {
                return (object)[
                    'book_id' => $borrow->book_id,
                    'book' => $borrow->book,
                    'tanggal_kembali' => $borrow->tanggal_kembali,
                    'status' => $borrow->status, // Pastikan status tetap asli
                    'updated_at' => $borrow->updated_at
                ];
            });

        foreach ($borrowedBooks as $borrow) {
            $returnDate = Carbon::parse($borrow->tanggal_kembali);
            $daysDifference = $today->diffInDays($returnDate, false);

            // Hanya menentukan jenis notifikasi, tidak mengubah status
            if ($daysDifference < 0) {
                $daysOverdue = abs($daysDifference);
                $notifications[] = [
                    'type' => 'overdue',
                    'message' => 'Buku "' . $borrow->book->title . '" sudah terlambat ' . $daysOverdue . ' hari!',
                    'book_id' => $borrow->book_id,
                    'return_date' => $borrow->tanggal_kembali,
                    'days_overdue' => $daysOverdue,
                    'created_at' => $borrow->updated_at,
                    'actual_status' => $borrow->status // Simpan status asli
                ];
            } 
            elseif ($daysDifference == 0) {
                $notifications[] = [
                    'type' => 'due-today',
                    'message' => 'Buku "' . $borrow->book->title . '" harus dikembalikan hari ini!',
                    'book_id' => $borrow->book_id,
                    'return_date' => $borrow->tanggal_kembali,
                    'days_left' => 0,
                    'created_at' => $borrow->updated_at,
                    'actual_status' => $borrow->status // Simpan status asli
                ];
            }
            elseif ($daysDifference == 1) {
                $notifications[] = [
                    'type' => 'due-soon',
                    'message' => 'Buku "' . $borrow->book->title . '" harus dikembalikan besok!',
                    'book_id' => $borrow->book_id,
                    'return_date' => $borrow->tanggal_kembali,
                    'days_left' => 1,
                    'created_at' => $borrow->updated_at,
                    'actual_status' => $borrow->status // Simpan status asli
                ];
            }
        }

        // Sorting tetap sama
        usort($notifications, function ($a, $b) {
            $priority = [
                'overdue' => 1,
                'due-today' => 2,
                'due-soon' => 3
            ];
            
            if ($a['type'] !== $b['type']) {
                return $priority[$a['type']] <=> $priority[$b['type']];
            }
            
            if ($a['type'] === 'overdue') {
                return $b['days_overdue'] <=> $a['days_overdue'];
            }
            
            return $b['created_at'] <=> $a['created_at'];
        });

        return $notifications;
    }

    public function markAsRead(Request $request)
    {
        // In a real app, you would update the database here
        // For now, we'll just return success
        return response()->json([
            'success' => true,
            'message' => 'Notifikasi telah ditandai sebagai sudah dibaca'
        ]);
    }

    protected function shareCommonData()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $today = Carbon::today();

            // Hitung jumlah notifikasi yang perlu ditampilkan
            $unreadCount = Borrow::where('user_id', $userId)
                ->where('status', 'dipinjam')
                ->where(function($query) use ($today) {
                    $query->whereDate('tanggal_kembali', '<=', $today) // Sudah jatuh tempo atau hari ini
                          ->orWhereDate('tanggal_kembali', '=', $today->copy()->addDay()); // Atau besok
                })
                ->count();

            view()->share('unreadCount', $unreadCount);
        } else {
            view()->share('unreadCount', 0);
        }
    }
}