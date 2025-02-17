<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Borrow;
use App\Models\Books;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch borrowing data grouped by month
        $borrowingData = Borrow::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                               ->groupBy('month')
                               ->get();

        // Fetch registered user data grouped by month
        $registeredUserData = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                                  ->groupBy('month')
                                  ->get();

    // Fetch book statistics
    $totalBooks = Books::count();
    $borrowedBooks = Borrow::where('status','dikembalikan', null)->count();
    $availableBooks = $totalBooks - $borrowedBooks;
    $damagedOrLostBooks = Borrow::where('keterangan', 'rusak')->orWhere('keterangan', 'hilang')->count();
        $months = [];
        $borrowCounts = [];
        $registeredUserCounts = [];

        // Prepare borrowing data
        foreach ($borrowingData as $data) {
            $months[] = Carbon::createFromFormat('m', $data->month)->format('F');
            $borrowCounts[] = $data->count;
        }

        // Prepare registered user data
        foreach ($registeredUserData as $data) {
            $registeredUserCounts[] = $data->count; // Add registered user counts
        }

        // Pass the data to the view
        return view('dashboard.index', [
            'data' => [
                'months' => $months,
                'borrowCounts' => $borrowCounts,
                'registeredUserCounts' => $registeredUserCounts,
                'bookStatistics' => [
                    'totalBooks' => $totalBooks,
                    'borrowedBooks' => $borrowedBooks,
                    'availableBooks' => $availableBooks,
                    'damagedOrLostBooks' => $damagedOrLostBooks,
                ]
            ]
        ]);
    }
}