<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Service;
use App\Models\ServicesInfo;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfficeController extends Controller
{

    public function index()
{
    $user = auth()->user();

    if ($user->hasRole('head|sub_head')) {
        $office = $user->office; // Assuming 'office' is a relationship in the User model

        if ($office) {
            $services = $office->services()->where('status', 'approved')->get(); // Only approved services
            $pendingServices = $office->services()->where('status', 'pending')->get(); // Pending services
            $transactions = Transaction::all(); // Fetch all transactions (optional)

            // Pass pendingServices to the view
            return view('offices.services', compact('office', 'services', 'pendingServices', 'transactions'));
        } else {
            return redirect()->back()->with('error', 'No office assigned to this user.');
        }
    }

    $offices = Office::all();
    return view('offices.offices', compact('offices'));
}

public function informations()
{
    // Fetch all offices from the database
    $offices = Office::all();

    // Pass the data to the Blade view
    return view('offices.informations', compact('offices'));
}



    public function feedbacks() {
        return view('offices.feedbacks');
    }

    public function showServices($id)
{
    $services = Service::where('office_id', $id)->where('status', 'approved')->get(); // Approved services
    $pendingServices = Service::where('office_id', $id)->where('status', 'pending')->get(); // Pending services
    $office = Office::findOrFail($id);
    $transactions = Transaction::all();

    // Pass pendingServices to the view
    return view('offices.services', compact('services', 'pendingServices', 'office', 'transactions'));
}



    public function serviceDetails($service_id, $office_id){
        $service = Service::findOrFail($service_id);
        $office = Office::findOrFail($office_id);
        $services_infos = ServicesInfo::where('service_id',  $service_id)->get();

        return view('services.show', compact('service', 'services_infos', 'office'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'office_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:255',
        ]);

        Office::create($validatedData);

        return redirect()->route('admin.offices.index')->with('success', 'Office added successfully.');
    }

public function show($id)
{
    $office = Office::findOrFail($id);
    $services = $office->services; // Assuming you have a relationship set up between Office and Service
    $transactions = Transaction::all(); // Fetch all transactions for the dropdown

    return view('services.show', compact('office', 'services', 'transactions')); // Pass transactions to the view
}


public function storeService(Request $request, $officeId)
{
    try {
        // Validate the request
        $request->validate([
            'service_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Create the service
        $service = new Service();
        $service->service_name = $request->service_name;
        $service->description = $request->description;
        $service->office_id = $officeId;
        $service->save();

        // Return a JSON response
        return response()->json([
            'success' => true,
            'service' => $service,
        ]);
    } catch (\Exception $e) {
        // Return a JSON error response
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while adding the service.',
            'error' => $e->getMessage(),
        ], 500);
    }
}
public function update(Request $request, $id)
{
    $office = Office::findOrFail($id);

    $user = auth()->user();

    // Check if the user has permission to edit this office
    if ($user->hasRole('head|sub_head')) {
        if ($office->id !== $user->office_id) { // Assuming 'office_id' links the user to their assigned office
            return redirect()->back()->with('error', 'You are not authorized to edit this office.');
        }
    }

    // Validate the request
    $validatedData = $request->validate([
        'office_name' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
        'address' => 'nullable|string|max:255',
        'email' => 'nullable|string|max:255',
        'contact_number' => 'nullable|string|max:255',
    ]);

    // Update the office
    $office->update($validatedData);

    return redirect()->route('admin.offices.index')->with('success', 'Office updated successfully.');
}


public function destroy($id)
{
    $office = Office::findOrFail($id);
    $office->delete();

    return redirect()->route('admin.offices.index')->with('success', 'Office deleted successfully.');
}

    public function translation(Request $request)
    {
        $languageCode = $request->get('lang', 'en'); // Default to English

        $offices = Office::with(['translations' => function ($query) use ($languageCode) {
            $query->whereHas('language', function ($langQuery) use ($languageCode) {
                $langQuery->where('code', $languageCode);
            });
        }])->get();

        return response()->json($offices);
    }
}

