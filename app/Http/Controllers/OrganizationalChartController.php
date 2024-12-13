<?php

namespace App\Http\Controllers;

use App\Models\OrganizationalChart;
use Illuminate\Http\Request;

class OrganizationalChartController extends Controller
{
    public function index()
    {
        return view('pages.org-chart');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageName = 'org-chart.' . $request->image->extension();
        $request->image->move(public_path('assets/images'), $imageName);

        OrganizationalChart::create(['image' => $imageName]);

        return redirect()->route('org-chart.index')->with('success', 'Image added successfully!');
    }

    public function edit($id)
    {
        $chart = OrganizationalChart::findOrFail($id);
        return response()->json($chart); // Return data as JSON for the modal
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $chart = OrganizationalChart::findOrFail($id);
        $imageName = 'org-chart.' . $request->image->extension();
        $request->image->move(public_path('assets/images'), $imageName);

        $chart->update(['image' => $imageName]);

        return redirect()->route('org-chart.index')->with('success', 'Image updated successfully!');
    }

    public function destroy($id)
    {
        $chart = OrganizationalChart::findOrFail($id);
        unlink(public_path('assets/images/' . $chart->image));
        $chart->delete();

        return redirect()->route('org-chart.index')->with('success', 'Image deleted successfully!');
    }
}
