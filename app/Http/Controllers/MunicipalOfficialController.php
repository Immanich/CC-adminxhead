<?php

namespace App\Http\Controllers;

use App\Models\MunicipalOfficial;
use Illuminate\Http\Request;

class MunicipalOfficialController extends Controller
{
    public function index()
    {
        $officials = MunicipalOfficial::all(); // Fetch all officials
        return view('pages.municipal-officials', compact('officials'));
    }

    public function edit($id)
{
    $official = MunicipalOfficial::findOrFail($id);
    return response()->json($official);
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'title' => 'required|string|max:255',
        'image' => 'nullable|string|url',
        'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if (!$request->filled('image') && !$request->hasFile('image_file')) {
        return redirect()->back()->withErrors(['image' => 'Either an image URL or a file must be provided.']);
    }

    $official = MunicipalOfficial::findOrFail($id);

    if ($request->hasFile('image_file')) {
        $imagePath = $request->file('image_file')->store('officials', 'public');
        $official->image = "/storage/" . $imagePath;
    } elseif ($request->filled('image')) {
        $official->image = $request->image;
    }

    $official->name = $request->name;
    $official->title = $request->title;
    $official->save();

    return redirect()->route('municipal-officials')->with('success', 'Official updated successfully!');
}

public function editYear()
{
    $yearData = MunicipalOfficial::select('start_year', 'end_year')->first(); // Retrieve the first entry's year
    return response()->json($yearData);
}

public function updateYear(Request $request)
{
    $request->validate([
        'start_year' => 'required|digits:4',
        'end_year' => 'required|digits:4|gte:start_year',
    ]);

    $official = MunicipalOfficial::first(); // Update the first entry (or adjust logic for multiple)
    $official->start_year = $request->start_year;
    $official->end_year = $request->end_year;
    $official->save();

    return redirect()->back()->with('success', 'Year range updated successfully.');
}



}
