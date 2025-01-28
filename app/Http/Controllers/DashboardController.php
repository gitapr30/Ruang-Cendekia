<?php
namespace App\Http\Controllers;

use App\Models\User; // Pastikan ini ada
use App\Models\Borrow; // Jika Anda menggunakan model Borrow
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

    // Fetch active user data grouped by month
    $activeUserData = User::selectRaw('MONTH(last_login_at) as month, COUNT(*) as count')
                          ->whereNotNull('last_login_at') // Only include users who have logged in
                          ->groupBy('month')
                          ->get();

    // Initialize arrays for months and counts
    $months = [];
    $borrowCounts = [];
    $activeUserCounts = [];

    // Prepare borrowing data
    foreach ($borrowingData as $data) {
        $months[] = Carbon::createFromFormat('m', $data->month)->format('F');
        $borrowCounts[] = $data->count;
    }

    // Prepare active user data
    foreach ($activeUserData as $data) {
        $activeUserCounts[] = $data->count; // Add active user counts
    }

    // Pass the data to the view
    return view('dashboard.index', [
        'data' => [
            'months' => $months,
            'borrowCounts' => $borrowCounts,
            'activeUserCounts' => $activeUserCounts,
        ]
    ]);
}
}
