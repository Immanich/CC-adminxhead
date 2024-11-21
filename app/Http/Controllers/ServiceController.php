<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Service;
use App\Models\ServicesInfo;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    public function show($id)
    {
        // $servicesInfo = ServicesInfo::first();
        $service = Service::with('servicesInfos')->findOrFail($id);
        // $office = load(Office);
        return view('services.show', compact('service', 'office', 'transaction'));
    }

    public function showService($serviceId)
{
    // Fetch the service by its ID
    $service = Service::findOrFail($serviceId);
    $services_infos = ServicesInfo::where('service_id', $serviceId)->get();

    // Pass the service and related service infos to the view
    return view('services.show', compact('service', 'services_infos'));
}

public function storeService(Request $request, $officeId)
{
    // Validate the request
    $request->validate([
        'service_name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'classification' => 'required|in:SIMPLE,COMPLEX,SIMPLE - COMPLEX,HIGHLY TECHNICAL',
        'transaction_id' => 'required|exists:transactions,id',  // Make sure the transaction_id exists
        'checklist_of_requirements' => 'nullable|string', // Allow nullable
        'where_to_secure' => 'nullable|string',  // Allow nullable
    ]);

    // Find the office
    $office = Office::findOrFail($officeId);

    // Create the service with 'pending' status
    $service = $office->services()->create([
        'service_name' => $request->service_name,
        'description' => $request->description,
        'classification' => $request->classification,
        'transaction_id' => $request->transaction_id, // Pass transaction ID
        'checklist_of_requirements' => $request->checklist_of_requirements,
        'where_to_secure' => $request->where_to_secure,
        'status' => 'pending',  // Default status is pending
    ]);

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Service added and pending approval.');
}

    public function showOfficeServices($officeId)
    {
        $services = Service::where('office_id', $officeId)
            ->where('status', 'approved')
            ->get();

        $transactions = Transaction::all(); // Fetch all transactions

        return view('services.show', compact('services', 'transactions'));
    }

    // Admin page for showing pending services
    public function pendingServices()
    {
        // Fetch services with the status 'pending'
        $pendingServices = Service::where('status', 'pending')->get();

        // Ensure the data is passed to the view
        return view('pendings-folder.pending-services', compact('pendingServices'));
    }

    // Admin approves a service
    public function approveService($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $service->status = 'approved';  // Approve the service
        $service->save();

        return redirect()->route('pending.services')->with('success', 'Service approved successfully.');
    }

    // Admin rejects a service
    public function rejectService($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $service->status = 'rejected';  // Reject the service
        $service->save();

        return redirect()->route('pending.services')->with('success', 'Service rejected successfully.');
    }
}
