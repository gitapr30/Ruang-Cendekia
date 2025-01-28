<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Display a listing of the histories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $histories = History::with(['user', 'book'])->get();
        return view('history.history', ['histories' => $histories]); }
    

    /**
     * Store a newly created history in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'books_id' => 'required|exists:books,id',
        ]);

        $history = History::create($validated);

        return response()->json($history, 201);
    }

    /**
     * Display the specified history.
     *
     * @param  \App\Models\History  $history
     * @return \Illuminate\Http\Response
     */
    public function show(History $history)
    {
        return response()->json($history->load(['user', 'book']));
    }

    /**
     * Update the specified history in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\History  $history
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, History $history)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'books_id' => 'required|exists:books,id',
        ]);

        $history->update($validated);

        return response()->json($history);
    }

    /**
     * Remove the specified history from storage.
     *
     * @param  \App\Models\History  $history
     * @return \Illuminate\Http\Response
     */
    public function destroy(History $history)
    {
        $history->delete();

        return response()->json(null, 204);
    }
}
