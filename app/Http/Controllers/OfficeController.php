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

    // Check if the user has the 'user' role
    if ($user->hasRole('user|sub_user')) {
        // Fetch the office assigned to this user user
        $office = $user->office; // Assuming 'office' is a relationship in the User model

        if ($office) {
            // Fetch services related to the office
            $services = $office->services;

            // Fetch all transactions for the dropdown (optional)
            $transactions = Transaction::all();

            // Redirect the user user directly to the services of their assigned office
            return view('offices.services', compact('office', 'services', 'transactions'));
        } else {
            // Handle the case where the user user has no office assigned
            return redirect()->back()->with('error', 'No office assigned to this user.');
        }
    }

    // If the user is an admin, fetch all offices
    $offices = Office::all();
    return view('offices.offices', compact('offices'));
}


    public function feedbacks() {
        return view('offices.feedbacks');
    }

    public function showServices($id)
{
    // Fetch the services related to the office
    $services = Service::where('office_id', $id)->get();

    // Fetch the office information
    $office = Office::findOrFail($id);

    // Fetch all transactions
    $transactions = Transaction::all();

    // Pass the services, office, and transactions to the view
    return view('offices.services', compact('services', 'office', 'transactions'));
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

    $validatedData = $request->validate([
        'office_name' => 'required|string|max:255',
    ]);

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

