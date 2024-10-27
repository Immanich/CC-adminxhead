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
    // Fetch the service by its ID
    $service = Service::with('serviceInfos')->findOrFail($id);

    // Fetch the office information (if you are using $office)
    $office = Office::find($service->office_id);

    // Fetch all transactions to display in the dropdown
    $transactions = Transaction::all();

    // Return the view with the service, office, and transactions
    return view('services.show', compact('service', 'office', 'transactions'));
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
        'transaction_id' => 'required|exists:transactions,id',
        'checklist_of_requirements' => 'nullable|string',
        'where_to_secure' => 'nullable|string',
    ]);

    // Find the office by ID
    $office = Office::findOrFail($officeId);

    // Convert checklist_of_requirements to JSON if not empty
    $checklist = $request->checklist_of_requirements ? json_encode(explode("\n", $request->checklist_of_requirements)) : null;

    // Determine the status based on user role
    $status = auth()->user()->hasRole('admin') ? 'approved' : 'pending';

    // Create a new service for the office
    $service = $office->services()->create([
        'service_name' => $request->service_name,
        'description' => $request->description,
        'classification' => $request->classification,
        'transaction_id' => $request->transaction_id,
        'checklist_of_requirements' => $checklist,
        'where_to_secure' => $request->where_to_secure,
        'status' => $status,
    ]);

    // Set a different success message based on the status
    $message = $status === 'approved'
        ? 'Service created successfully.'
        : 'Service created and is waiting for approval.';

    return redirect()->back()->with('success', $message);
}



public function showOfficeServices($officeId)
{
    // Fetch services with appropriate filters based on user role
    $query = Service::where('office_id', $officeId);

    if (!auth()->user()->hasRole('admin')) {
        $query->where('status', 'approved'); // Show only approved services for non-admins
    }

    $services = $query->get();

    // Fetch the office information
    $office = Office::findOrFail($officeId);

    // Fetch all transactions for the dropdown in the modal
    $transactions = Transaction::all();

    return view('services.services', compact('services', 'office', 'transactions'));
}

public function edit($id)
{
    $service = Service::findOrFail($id);
    return response()->json($service);
}

public function updateService(Request $request, $serviceId)
{
    // Validate the request
    $request->validate([
        'service_name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'classification' => 'required|in:SIMPLE,COMPLEX,SIMPLE - COMPLEX,HIGHLY TECHNICAL',
        'transaction_id' => 'required|exists:transactions,id',
        'checklist_of_requirements' => 'nullable|string',
        'where_to_secure' => 'nullable|string',
    ]);

    // Find the service and update its fields
    $service = Service::findOrFail($serviceId);
    $service->update($request->all());

    return redirect()->back()->with('success', 'Service updated successfully.');
}



public function deleteService($serviceId)
{
    // Find the service and delete it
    $service = Service::findOrFail($serviceId);
    $service->delete();

    return redirect()->back()->with('success', 'Service deleted successfully.');
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
