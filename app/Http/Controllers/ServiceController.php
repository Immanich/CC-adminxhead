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

    // Convert checklist and where_to_secure to JSON format
    $checklist = $request->checklist_of_requirements
        ? json_encode(explode("\n", trim($request->checklist_of_requirements)))
        : '[]';

    $whereToSecure = $request->where_to_secure
        ? json_encode(explode("\n", trim($request->where_to_secure)))
        : '[]';

    // Ensure the lengths of checklist and where_to_secure match
    if (json_decode($checklist, true) && json_decode($whereToSecure, true) &&
        count(json_decode($checklist, true)) !== count(json_decode($whereToSecure, true))) {
        return back()->withErrors([
            'where_to_secure' => 'The "Where to Secure" entries must match the number of checklist requirements.',
        ]);
    }

    // Set status based on user role
    $status = auth()->user()->hasRole('admin') ? 'approved' : 'pending';

    // Store service
    $service = $office->services()->create([
        'service_name' => $request->service_name,
        'description' => $request->description,
        'classification' => $request->classification,
        'transaction_id' => $request->transaction_id,
        'checklist_of_requirements' => $checklist, // Store as JSON
        'where_to_secure' => $whereToSecure,       // Store as JSON
        'status' => $status,
        'created_by' => auth()->id(),
    ]);

    // If the service is created by an admin, no notification is required
    if ($status === 'approved' && !auth()->user()->hasRole('admin')) {
        Notification::create([
            'title' => 'Service Approved',
            'description' => "The <strong>{$office->office_name}</strong> service has been approved.",
            'dateTime' => now(),
            'user_id' => 1, // Admin ID for approval notification
            'link' => route('services.show', ['id' => $service->id]),
        ]);
    } elseif ($status === 'pending') {
        // Notify admin when the service is pending
        Notification::create([
            'title' => 'New Service Pending Approval',
            'description' => "The <strong>{$office->office_name}</strong> added a new service, awaiting approval.",
            'dateTime' => now(),
            'user_id' => 1, // Admin ID for pending notification
            'link' => route('pending.services'),
        ]);
    }

    return redirect()->back()->with('success', $status === 'approved' ? 'Service created successfully.' : 'Service created and is waiting for approval.');
}



public function showOfficeServices($officeId)
{
    $query = Service::where('office_id', $officeId);

    // Fetch all approved services for users and admins
    if (!auth()->user()->hasRole('admin')) {
        $query->where('status', 'approved');
    }

    $services = $query->get();
    $pendingServices = Service::where('office_id', $officeId)
        ->where('status', 'pending')
        ->get(); // Fetch pending services for the user

    $office = Office::findOrFail($officeId); // Fetch office info
    $transactions = Transaction::all(); // Fetch transactions for modal

    return view('services.services', compact('services', 'pendingServices', 'office', 'transactions'));
}


    public function edit($id)
{
    $service = Service::findOrFail($id);

    // Decode JSON and join with newlines
    $service->checklist_of_requirements = implode("\n", json_decode($service->checklist_of_requirements, true) ?? []);
    $service->where_to_secure = implode("\n", json_decode($service->where_to_secure, true) ?? []);

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

    // Convert newline-separated strings to JSON
    $checklist = $request->checklist_of_requirements
        ? json_encode(explode("\n", trim($request->checklist_of_requirements)))
        : '[]';

    $whereToSecure = $request->where_to_secure
        ? json_encode(explode("\n", trim($request->where_to_secure)))
        : '[]';

    $service->update([
        'service_name' => $request->service_name,
        'description' => $request->description,
        'classification' => $request->classification,
        'transaction_id' => $request->transaction_id,
        'checklist_of_requirements' => $checklist,
        'where_to_secure' => $whereToSecure,
    ]);

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

        return redirect()->route('pending.services')->with('success', "Service '{$service->service_name}' approved successfully.");
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

        return redirect()->route('pending.services')->with('success', "Service '{$service->service_name}' rejected successfully.");
    }
}
// <?php

// namespace App\Http\Controllers;

// use App\Models\Notification;
// use App\Models\Office;
// use App\Models\Service;
// use App\Models\ServicesInfo;
// use App\Models\Transaction;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Log;

// class ServiceController extends Controller
// {
//     public function show($id)
//     {
//         // Fetch the service by its ID
//         $service = Service::with('serviceInfos')->findOrFail($id);
//         $office = Office::find($service->office_id); // Fetch the office
//         $transactions = Transaction::all(); // Fetch all transactions for dropdown

//         return view('services.show', compact('service', 'office', 'transactions'));
//     }

//     public function showService($serviceId)
//     {
//         $service = Service::findOrFail($serviceId);
//         $services_infos = ServicesInfo::where('service_id', $serviceId)->get();

//         return view('services.show', compact('service', 'services_infos'));
//     }

//     public function storeService(Request $request, $officeId)
// {
//     $request->validate([
//         'service_name' => 'required|string|max:255',
//         'description' => 'nullable|string',
//         'classification' => 'required|in:SIMPLE,COMPLEX,SIMPLE - COMPLEX,HIGHLY TECHNICAL',
//         'transaction_id' => 'required|exists:transactions,id',
//         'checklist_of_requirements' => 'nullable|string',
//         'where_to_secure' => 'nullable|string',
//     ]);

//     $office = Office::findOrFail($officeId);

//     // Convert checklist and where_to_secure to JSON format
//     $checklist = $request->checklist_of_requirements
//         ? json_encode(explode("\n", trim($request->checklist_of_requirements)))
//         : '[]';

//     $whereToSecure = $request->where_to_secure
//         ? json_encode(explode("\n", trim($request->where_to_secure)))
//         : '[]';

//     // Ensure the lengths of checklist and where_to_secure match
//     if (json_decode($checklist, true) && json_decode($whereToSecure, true) &&
//         count(json_decode($checklist, true)) !== count(json_decode($whereToSecure, true))) {
//         return back()->withErrors([
//             'where_to_secure' => 'The "Where to Secure" entries must match the number of checklist requirements.',
//         ]);
//     }

//     $status = auth()->user()->hasRole('admin') ? 'approved' : 'pending';

//     // Store service
//     $service = $office->services()->create([
//         'service_name' => $request->service_name,
//         'description' => $request->description,
//         'classification' => $request->classification,
//         'transaction_id' => $request->transaction_id,
//         'checklist_of_requirements' => $checklist, // Store as JSON
//         'where_to_secure' => $whereToSecure,       // Store as JSON
//         'status' => $status,
//         'created_by' => auth()->id(),
//     ]);

//     // Notify admin
//     if ($status === 'approved') {
//         Notification::create([
//             'title' => 'Service Approved',
//             'description' => "The <strong>{$office->office_name}</strong> service has been approved.",
//             'dateTime' => now(),
//             'user_id' => 1, // You can replace this with the admin ID
//             'link' => route('services.show', ['id' => $service->id]),
//         ]);
//     } elseif ($status === 'pending') {
//         Notification::create([
//             'title' => 'New Service Pending Approval',
//             'description' => "The <strong>{$office->office_name}</strong> added a new service, awaiting approval.",
//             'dateTime' => now(),
//             'user_id' => 1, // Admin ID for pending notification
//             'link' => route('pending.services'),
//         ]);
//     }
//     // if ($status === 'pending') {
//     //     Notification::create([
//     //         'title' => 'New Service Pending Approval',
//     //         'description' => "The <strong>{$office->office_name}</strong> added a new service, awaiting approval.",
//     //         'dateTime' => now(),
//     //         'user_id' => 1,
//     //         'link' => route('pending.services'),
//     //     ]);
//     // }

//     return redirect()->back()->with('success', $status === 'approved' ? 'Service created successfully.' : 'Service created and is waiting for approval.');
// }

// public function showOfficeServices($officeId)
// {
//     $query = Service::where('office_id', $officeId);

//     // Fetch all approved services for users and admins
//     if (!auth()->user()->hasRole('admin')) {
//         $query->where('status', 'approved');
//     }

//     $services = $query->get();
//     $pendingServices = Service::where('office_id', $officeId)
//         ->where('status', 'pending')
//         ->get(); // Fetch pending services for the user

//     $office = Office::findOrFail($officeId); // Fetch office info
//     $transactions = Transaction::all(); // Fetch transactions for modal

//     return view('services.services', compact('services', 'pendingServices', 'office', 'transactions'));
// }


//     public function edit($id)
// {
//     $service = Service::findOrFail($id);

//     // Decode JSON and join with newlines
//     $service->checklist_of_requirements = implode("\n", json_decode($service->checklist_of_requirements, true) ?? []);
//     $service->where_to_secure = implode("\n", json_decode($service->where_to_secure, true) ?? []);

//     return response()->json($service);
// }


// public function updateService(Request $request, $serviceId)
// {
//     $request->validate([
//         'service_name' => 'required|string|max:255',
//         'description' => 'nullable|string',
//         'classification' => 'required|in:SIMPLE,COMPLEX,SIMPLE - COMPLEX,HIGHLY TECHNICAL',
//         'transaction_id' => 'required|exists:transactions,id',
//         'checklist_of_requirements' => 'nullable|string',
//         'where_to_secure' => 'nullable|string',
//     ]);

//     $service = Service::findOrFail($serviceId);

//     // Convert newline-separated strings to JSON
//     $checklist = $request->checklist_of_requirements
//         ? json_encode(explode("\n", trim($request->checklist_of_requirements)))
//         : '[]';

//     $whereToSecure = $request->where_to_secure
//         ? json_encode(explode("\n", trim($request->where_to_secure)))
//         : '[]';

//     $service->update([
//         'service_name' => $request->service_name,
//         'description' => $request->description,
//         'classification' => $request->classification,
//         'transaction_id' => $request->transaction_id,
//         'checklist_of_requirements' => $checklist,
//         'where_to_secure' => $whereToSecure,
//     ]);

//     return redirect()->back()->with('success', 'Service updated successfully.');
// }

//     public function deleteService($serviceId)
//     {
//         $service = Service::findOrFail($serviceId);
//         $service->delete();

//         return redirect()->back()->with('success', 'Service deleted successfully.');
//     }

//     public function pendingServices()
//     {
//         $pendingServices = Service::where('status', 'pending')->get();

//         return view('pendings-folder.pending-services', compact('pendingServices'));
//     }

//     public function approveService($serviceId)
//     {
//         $service = Service::findOrFail($serviceId);
//         $service->status = 'approved';
//         $service->save();

//         $creatorId = $service->created_by;

//         Log::info("Approving service: {$serviceId}, Creator ID: {$creatorId}");

//         if ($creatorId) {
//             Notification::create([
//                 'title' => 'Service Approved',
//                 'description' => 'Your service has been approved by the admin.',
//                 'dateTime' => now(),
//                 'user_id' => $creatorId,
//                 'link' => route('services.show', ['id' => $service->id]),
//             ]);
//         } else {
//             Log::error("Approval notification failed: Creator ID missing for service ID {$serviceId}");
//         }

//         return redirect()->route('pending.services')->with('success', "Service '{$service->service_name}' approved successfully.");
//     }

//     public function rejectService($serviceId)
//     {
//         $service = Service::findOrFail($serviceId);
//         $service->status = 'rejected';
//         $service->save();

//         $creatorId = $service->created_by;

//         Log::info("Rejecting service: {$serviceId}, Creator ID: {$creatorId}");

//         if ($creatorId) {
//             Notification::create([
//                 'title' => 'Service Rejected',
//                 'description' => 'Your service has been rejected by the admin.',
//                 'dateTime' => now(),
//                 'user_id' => $creatorId,
//                 'link' => route('services.show', ['id' => $service->id]),
//             ]);
//         } else {
//             Log::error("Rejection notification failed: Creator ID missing for service ID {$serviceId}");
//         }

//         return redirect()->route('pending.services')->with('success', "Service '{$service->service_name}' rejected successfully.");
//     }
// }
