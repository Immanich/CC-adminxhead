<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Office;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index($officeId)
    {
        // Find the office by its ID
        $office = Office::findOrFail($officeId);

        // Get employees for this office
        $employees = $office->employees; // Assuming there's a relationship defined

        return view('offices.employees', compact('office', 'employees'));
    }

    public function store(Request $request, $officeId)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'position' => 'required|string|max:255',
        'image_url' => 'nullable|url|required_without:image_file',
        'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000|required_without:image_url',
    ]);

    // Determine which image source to use
    if ($request->hasFile('image_file')) {
        // Store uploaded file and get relative path
        $imagePath = $request->file('image_file')->store('employees', 'public');
        $validated['image'] = $imagePath;
    } elseif ($request->filled('image_url')) {
        // Use the provided URL
        $validated['image'] = $request->input('image_url');
    } else {
        $validated['image'] = null; // No image provided
    }

    $validated['office_id'] = $officeId;
    Employee::create($validated);

    return redirect()->route('offices.employees', $officeId)
                     ->with('success', 'Employee added successfully.');
}




    public function edit($id)
    {
        $employee = Employee::findOrFail($id);

        return response()->json($employee);
    }

    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'position' => 'required|string|max:255',
        'image_url' => 'nullable|url|required_without:image_file',
        'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000|required_without:image_url',
    ]);

    $employee = Employee::findOrFail($id);

    // Determine which image source to use
    if ($request->hasFile('image_file')) {
        $imagePath = $request->file('image_file')->store('employees', 'public');
        $validated['image'] = $imagePath;
    } elseif ($request->filled('image_url')) {
        $validated['image'] = $request->input('image_url');
    }

    $employee->update($validated);

    return redirect()->route('offices.employees', $employee->office_id)
                     ->with('success', 'Employee updated successfully.');
}



    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        $employee->delete();

        return redirect()->route('offices.employees', $employee->office_id)
                         ->with('success', 'Employee deleted successfully.');
    }
}
