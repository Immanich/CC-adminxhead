<?php

namespace App\Http\Controllers;

use App\Models\Mvmsp;
use Illuminate\Http\Request;

class MvmspController extends Controller
{
    public function show()
{
    $user = auth()->user();

    // Fetch MVMSP for the user's assigned office
    $officeMvmsp = Mvmsp::where('office_id', $user->office_id)->first();

    // If no MVMSP exists, create an empty object with null values
    if (!$officeMvmsp) {
        $officeMvmsp = (object) [
            'mandate' => null,
            'vision' => null,
            'mission' => null,
            'service_pledge' => null,
        ];
    }

    return view('pages.mvmsp', compact('officeMvmsp'));
}




    public function store(Request $request)
{
    $validated = $request->validate([
        'mandate' => 'required|string',
        'vision' => 'required|string',
        'mission' => 'required|string',
        'service_pledge' => 'required|string',
    ]);

    // Ensure office_id is included during creation
    $validated['office_id'] = auth()->user()->office_id;

    Mvmsp::create($validated);

    return redirect()->route('mvmsp')->with('success', 'MVMSP created successfully!');
}


public function edit($id)
{
    $mvmsp = Mvmsp::where('id', $id)
        ->where('office_id', auth()->user()->office_id) // Ensure the MVMSP belongs to the user's office
        ->firstOrFail();

    return response()->json($mvmsp);
}


    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'mandate' => 'required|string',
        'vision' => 'required|string',
        'mission' => 'required|string',
        'service_pledge' => 'required|string',
    ]);

    $mvmsp = Mvmsp::where('id', $id)
        ->where('office_id', auth()->user()->office_id) // Ensure the MVMSP belongs to the user's office
        ->firstOrFail();

    $mvmsp->update($validated);

    return redirect()->route('mvmsp')->with('success', 'MVMSP updated successfully!');
}


public function destroy($id)
{
    $mvmsp = Mvmsp::findOrFail($id);

    $mvmsp->delete();

    // Redirect to show with success message, showing empty placeholders
    return redirect()->route('mvmsp')->with('success', 'MVMSP deleted successfully!');
}


}
