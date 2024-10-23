<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServicesInfo;
use Illuminate\Http\Request;

class ServicesInfoController extends Controller
{
    // Store new service info
    public function store(Request $request)
{
    // Validate input (notice 'fees' is a string, not numeric)
    $request->validate([
        'clients' => 'required|string|max:255',
        'agency_action' => 'required|string|max:255',
        'info_title' => 'required|string|max:255',
        'fees' => 'required|string|max:255', // Allow string input for fees
        'processing_time' => 'required|string|max:255',
        'person_responsible' => 'required|string|max:255',
        'step' => 'required|string|max:255',
        'service_id' => 'required|integer|exists:services,id',
        'office_id' => 'required|integer|exists:offices,id',
    ]);

    try {
        // Create new ServicesInfo entry
        ServicesInfo::create([
            'clients' => json_encode([$request->clients]),
            'agency_action' => json_encode([$request->agency_action]),
            'info_title' => $request->info_title,
            'fees' => $request->fees,  // Directly store the fees as a string
            'processing_time' => json_encode([$request->processing_time]),
            'person_responsible' => json_encode([$request->person_responsible]),
            'step' => $request->step,
            'service_id' => $request->service_id,
            'office_id' => $request->office_id,
            'total_fees' => 0,  // Keep this or modify based on actual logic
            'total_response_time' => "N/A",  // Keep this or modify based on actual logic
        ]);

        // Redirect to the correct service show page
        return redirect()->route('services.show', $request->service_id)
            ->with('success', 'Service info added successfully');
    } catch (\Exception $e) {
        // Redirect back to the service show page with error message
        return redirect()->route('services.show', $request->service_id)
            ->with('error', 'Failed to add service info: ' . $e->getMessage());
    }
}


    // Show service and its service info
    public function show($id)
    {
        $service = Service::with('serviceInfos')->findOrFail($id);
        return view('services.show', compact('service'));
    }

    // Delete a specific service info
    public function destroy($service_id, $info_id)
    {
        $info = ServicesInfo::where('id', $info_id)->where('service_id', $service_id)->firstOrFail();
        $info->delete();
        return redirect()->back()->with('success', 'Service info deleted successfully.');
    }
}
