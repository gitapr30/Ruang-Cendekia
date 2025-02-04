<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class HistoryController extends Controller
{
    /**
     * Show the borrowing history of the user.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retrieve user_id from cookie or fallback to authenticated user
        $userId = Cookie::get('user_id') ?? Auth::id();

        // If there's no user_id in the cookie and the user is not logged in, redirect to login page
        if (!$userId && !Auth::check()) {
            return redirect()->route('login');
        }

        // Retrieve all borrows associated with the user
        $borrow = Borrow::where('user_id', $userId)
            ->orderBy('tanggal_pinjam', 'desc') // Ordering by borrow date, descending
            ->get();

        return view('history.index', compact('borrow'));
    }

    /**
     * Show the details of a specific borrow record.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Retrieve the specific borrow record
        $borrow = Borrow::with(['user', 'book'])->find($id);
    
        // Debug: Check if borrow exists
        if (!$borrow) {
            return redirect()->route('history.index')->withErrors('Borrow record not found.');
        }
    
        return view('history.show', compact('borrow, history'));
    }
}
