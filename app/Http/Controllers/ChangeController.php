<?php

namespace App\Http\Controllers;

use App\Models\Change;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChangeController extends Controller
{
    public function index()
    {
        return view('changes.index', [
            'title' => 'Change List',
            'changes' => Change::all(),
        ]);
    }

    public function create()
    {
        return view('changes.create', [
            'title' => 'Create Change'
        ]);
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nama_website' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_telp' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'maps' => 'required|string',
            'tittle' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'footer' => 'required|string',
        ]);

        $validateData['logo'] = $request->file('logo')->store('logos');
        $validateData['image'] = $request->file('image')->store('images');

        Change::create($validateData);

        return redirect()->back()->with('success', 'New Change has been added!');
    }

    public function show($id)
    {
        $change = Change::findOrFail($id);
        return view('changes.show', compact('change'));
    }

    public function edit($id)
{
    $change = Change::findOrFail($id);
    return view('changes.update', compact('change'));
}

public function update(Request $request, $id)
{
    $change = Change::findOrFail($id);
    $validateData = $request->validate([
        'user_id' => 'required|exists:users,id',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'nama_website' => 'required|string|max:255',
        'alamat' => 'required|string|max:255',
        'no_telp' => 'required|string|max:15',
        'email' => 'required|email|max:255',
        'maps' => 'required|string',
        'tittle' => 'required|string|max:255',
        'description' => 'required|string',
        'content' => 'required|string',
        'footer' => 'required|string',
        'denda' => 'required|numeric',
        'max_peminjaman' => 'required|integer',
        'waktu_operasional' => 'required|string',
    ]);


    if ($request->hasFile('logo')) {
        if ($change->logo) {
            Storage::delete($change->logo);
        }
        $validateData['logo'] = $request->file('logo')->store('logos', 'public');
    }

    if ($request->hasFile('image')) {
        if ($change->image) {
            Storage::delete($change->image);
        }
        $validateData['image'] = $request->file('image')->store('images', 'public');
    }

    $change->update($validateData);

    return redirect()->back()->with('success', 'Change has been updated!');
}



    public function destroy(Change $change)
    {
        if ($change->logo) {
            Storage::delete($change->logo);
        }

        if ($change->image) {
            Storage::delete($change->image);
        }

        $change->delete();

        return redirect()->back()->with('success', 'Change has been deleted!');
    }
}
