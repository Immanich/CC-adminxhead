<?php

namespace App\Http\Controllers;

use App\Models\ElectedOfficial;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ElectedOfficialController extends Controller
{
    public function index()
    {
        $officials = ElectedOfficial::all(); // Fetch all officials
        return view('pages.municipal-officials', compact('officials'));
    }

    public function edit($id)
{
    $official = ElectedOfficial::findOrFail($id);
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

    $official = ElectedOfficial::findOrFail($id);

    if ($request->hasFile('image_file')) {
        $imagePath = $request->file('image_file')->store('officials', 'public');
        $official->image = "/storage/" . $imagePath;
        // dd($official);
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
    $yearData = ElectedOfficial::select('start_year', 'end_year')->first(); // Retrieve the first entry's year
    return response()->json($yearData);
}

public function updateYear(Request $request)
{
    $request->validate([
        'start_year' => 'required|digits:4',
        'end_year' => 'required|digits:4|gte:start_year',
    ]);

    $official = ElectedOfficial::first(); // Update the first entry (or adjust logic for multiple)
    $official->start_year = $request->start_year;
    $official->end_year = $request->end_year;
    $official->save();

    return redirect()->back()->with('success', 'Year range updated successfully.');
}

// public function updateStatus()
// {
//     $officials = ElectedOfficial::all();

//     foreach ($officials as $official) {
//         $currentYear = Carbon::now()->year;

//         if ($currentYear >= $official->start_year && $currentYear <= $official->end_year) {
//             $official->status = 'current'; // The official is currently in office
//         } elseif ($currentYear < $official->start_year) {
//             $official->status = 'upcoming'; // The official has been elected but the term hasn't started
//         } else {
//             $official->status = 'expired'; // The official's term has expired
//         }

//         $official->save();
//     }
// }

}
