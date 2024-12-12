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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000', // Ensure the image is validated
        ]);

        // Handle image upload if file is uploaded
        if ($request->hasFile('image')) {
            // Store the image in the 'public' disk and get the relative path
            $imagePath = $request->file('image')->store('employees', 'public');
            // Store only the relative path to the image (without 'public/' prefix)
            $validated['image'] = $imagePath;
        }

        // Create the employee record
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000', // Make sure to validate the image file
        ]);

        $employee = Employee::findOrFail($id);

        // If an image is uploaded, handle the update of the image field
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('employees', 'public');
        }

        // Update the employee record
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
