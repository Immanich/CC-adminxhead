<?php

namespace App\Http\Controllers;

use App\Models\Notification;
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
        $office = Office::find($service->office_id); // Fetch the office
        $transactions = Transaction::all(); // Fetch all transactions for dropdown

        return view('services.show', compact('service', 'office', 'transactions'));
    }

    public function showService($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $services_infos = ServicesInfo::where('service_id', $serviceId)->get();

        return view('services.show', compact('service', 'services_infos'));
    }

    public function storeService(Request $request, $officeId)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'classification' => 'required|in:SIMPLE,COMPLEX,SIMPLE - COMPLEX,HIGHLY TECHNICAL',
            'transaction_id' => 'required|exists:transactions,id',
            'checklist_of_requirements' => 'nullable|string',
            'where_to_secure' => 'nullable|string',
        ]);

        $office = Office::findOrFail($officeId);
        $checklist = $request->checklist_of_requirements
            ? json_encode(explode("\n", $request->checklist_of_requirements))
            : null;

        $status = auth()->user()->hasRole('admin') ? 'approved' : 'pending';

        // Create the service and set created_by
        $service = $office->services()->create([
            'service_name' => $request->service_name,
            'description' => $request->description,
            'classification' => $request->classification,
            'transaction_id' => $request->transaction_id,
            'checklist_of_requirements' => $checklist,
            'where_to_secure' => $request->where_to_secure,
            'status' => $status,
            'created_by' => auth()->id(),
        ]);

        Log::info("Service created: {$service->id}, Created By: {$service->created_by}");

        // Notify admin if the service is pending approval
        if ($status === 'pending') {
            Notification::create([
                'title' => 'New Service Pending Approval',
                'description' => "The <strong>{$office->office_name}</strong> added a new service, awaiting approval.",
                'dateTime' => now(),
                'user_id' => 1, // Admin ID
                'link' => route('pending.services'),
            ]);
        }

        $message = $status === 'approved'
            ? 'Service created successfully.'
            : 'Service created and is waiting for approval.';

        return redirect()->back()->with('success', $message);
    }

    public function showOfficeServices($officeId)
    {
        $query = Service::where('office_id', $officeId);

        if (!auth()->user()->hasRole('admin')) {
            $query->where('status', 'approved'); // Show approved services for non-admins
        }

        $services = $query->get();
        $office = Office::findOrFail($officeId); // Fetch office info
        $transactions = Transaction::all(); // Fetch transactions for modal

        return view('services.services', compact('services', 'office', 'transactions'));
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return response()->json($service);
    }

    public function updateService(Request $request, $serviceId)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'classification' => 'required|in:SIMPLE,COMPLEX,SIMPLE - COMPLEX,HIGHLY TECHNICAL',
            'transaction_id' => 'required|exists:transactions,id',
            'checklist_of_requirements' => 'nullable|string',
            'where_to_secure' => 'nullable|string',
        ]);

        $service = Service::findOrFail($serviceId);
        $service->update($request->all());

        return redirect()->back()->with('success', 'Service updated successfully.');
    }

    public function deleteService($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $service->delete();

        return redirect()->back()->with('success', 'Service deleted successfully.');
    }

    public function pendingServices()
    {
        $pendingServices = Service::where('status', 'pending')->get();

        return view('pendings-folder.pending-services', compact('pendingServices'));
    }

    public function approveService($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $service->status = 'approved';
        $service->save();

        $creatorId = $service->created_by;

        Log::info("Approving service: {$serviceId}, Creator ID: {$creatorId}");

        if ($creatorId) {
            Notification::create([
                'title' => 'Service Approved',
                'description' => 'Your service has been approved by the admin.',
                'dateTime' => now(),
                'user_id' => $creatorId,
                'link' => route('services.show', ['id' => $service->id]),
            ]);
        } else {
            Log::error("Approval notification failed: Creator ID missing for service ID {$serviceId}");
        }

        return redirect()->route('pending.services')->with('success', 'Service approved successfully.');
    }

    public function rejectService($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $service->status = 'rejected';
        $service->save();

        $creatorId = $service->created_by;

        Log::info("Rejecting service: {$serviceId}, Creator ID: {$creatorId}");

        if ($creatorId) {
            Notification::create([
                'title' => 'Service Rejected',
                'description' => 'Your service has been rejected by the admin.',
                'dateTime' => now(),
                'user_id' => $creatorId,
                'link' => route('services.show', ['id' => $service->id]),
            ]);
        } else {
            Log::error("Rejection notification failed: Creator ID missing for service ID {$serviceId}");
        }

        return redirect()->route('pending.services')->with('success', 'Service rejected successfully.');
    }
}
