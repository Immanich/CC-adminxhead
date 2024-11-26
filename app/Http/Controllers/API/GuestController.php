<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Office;
use App\Models\Service;
use App\Models\ServicesInfo;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GuestController extends Controller
{
    // public function getOffices(Request $request){
    //     $langCode = $request->query('lang', 'en'); // Default to 'en' if no lang is provided

    //     $offices = Office::with(['translations' => function($query) use ($langCode) {
    //         $query->whereHas('language', function($q) use ($langCode) {
    //             $q->where('code', $langCode);
    //         });
    //     }])->get();

    //     // Prepare the response
    //     $response = $offices->map(function ($office) use ($langCode) {
    //         // Get the translation or fallback to the default office name and description
    //         $translation = $office->translations->first();

    //         return [
    //             'id' => $office->id,
    //             'office_name' => $translation ? $translation->office_name : $office->office_name,
    //             'description' => $translation ? $translation->description : "Default description for {$office->office_name}", // Adjust this fallback if necessary
    //         ];
    //     });

    //     return response()->json($response);
    // }


    public function getOffices(Request $request)
{
    $languageCode = $request->query('lang', 'en'); // Default to English
    
    $offices = Office::with('translations')->get();

    $response = $offices->map(function ($office) use ($languageCode) {
        $translation = $office->translations->firstWhere('language.code', $languageCode);

        return [
            'id' => $office->id,
            'office_name' => $translation?->office_name ?? $office->office_name,
            'description' => $translation?->description ?? "Default description for {$office->office_name}",
        ];
    });

    return response()->json($response);
}





    public function getServicesForOffice($officeId, Request $request)
{
    $languageCode = $request->query('lang', 'en');

    // Fetch services with translations and eager-load relationships
    $services = Service::where('office_id', $officeId)
        ->with(['translations.language'])
        ->get();

    // Map services with translations
    $translatedServices = $services->map(function ($service) use ($languageCode) {
        // Find the appropriate translation
        $translation = $service->translations->firstWhere('language.code', $languageCode);

        return [
            'id' => $service->id,
            'service_name' => $translation->service_name ?? $service->service_name,
            'description' => $translation->description ?? $service->description,
            'classification' => $service->classification,
            'status' => $service->status,
        ];
    });

    return response()->json($translatedServices);
}




    public function getServiceInfo($office_id, $service_id, Request $request)
{
    $languageCode = $request->query('lang', 'en');

    try {
        $service = Service::where('office_id', $office_id)
            ->where('id', $service_id)
            ->with(['servicesInfos.translations', 'transaction'])
            ->first();

        if (!$service) {
            return response()->json(['error' => 'Service info not found'], 404);
        }

        $translatedServiceInfos = $service->servicesInfos->map(function ($info) use ($languageCode) {
            $translation = $info->translation($languageCode);
            return [
                'step' => $info->step,
                'info_title' => $translation->info_title ?? $info->info_title,
                'clients' => $info->clients,
                'agency_action' => $info->agency_action,
                'fees' => $info->fees,
                'processing_time' => $info->processing_time,
                'person_responsible' => $info->person_responsible,
                'total_fees' => $info->total_fees,
                'total_response_time' => $info->total_response_time,
                'note' => $info->note,
            ];
        });

        return response()->json([
            'id' => $service->id,
            'service_name' => $service->service_name,
            'description' => $service->description,
            'office_id' => $service->office_id,
            'classification' => $service->classification,
            'type_of_transaction' => $service->transaction?->type_of_transaction,
            'status' => $service->status,
            'checklist_of_requirements' => $service->checklist_of_requirements,
            'where_to_secure' => $service->where_to_secure,
            'created_at' => $service->created_at,
            'updated_at' => $service->updated_at,
            'services_infos' => $translatedServiceInfos,
        ], 200);
    } catch (\Exception $e) {
        Log::error("Error in getServiceInfo: " . $e->getMessage());
        return response()->json(['error' => 'Internal server error'], 500);
    }
}


//     public function getOffices() {
//         $offices = Office::all();
//         return response()->json($offices);
//     }

//     public function getServicesForOffice($officeId)
//     {
//         // Retrieve services only for the specific office
//         $services = Service::where('office_id', $officeId)
//             ->with(['servicesInfos', 'transaction'])
//             ->get();

//         return response()->json($services);
//     }

//     public function getServiceInfo($office_id, $service_id) {
//     try {
//         $service = Service::where('office_id', $office_id)
//             ->where('id', $service_id)
//             ->with(['servicesInfos', 'transaction'])
//             ->first();

//         if (!$service) {
//             Log::error("Service not found for office ID $office_id and service ID $service_id");
//             return response()->json(['error' => 'Service info not found'], 404);
//         }

//         // Explicitly name the key as `services_infos` in the JSON response
//         return response()->json([
//             'id' => $service->id,
//             'service_name' => $service->service_name,
//             'description' => $service->description,
//             'office_id' => $service->office_id,
//             'classification' => $service->classification,
//             'type_of_transaction' => $service->transaction ? $service->transaction->type_of_transaction : null,
//             'status' => $service->status,
//             'checklist_of_requirements' => $service->checklist_of_requirements,
//             'where_to_secure' => $service->where_to_secure,
//             'created_at' => $service->created_at,
//             'updated_at' => $service->updated_at,
//             'services_infos' => $service->servicesInfos,  // Include associated servicesInfos data
//         ], 200);
//     } catch (\Exception $e) {
//         Log::error("Error in getServiceInfo: " . $e->getMessage());
//         return response()->json(['error' => 'Internal server error'], 500);
//     }
// }

public function getEvents()
{
    // Fetch all approved events
    $approvedEvents = Event::where('status', 'approved')->get();
    
    // Return JSON response with approved events
    return response()->json($approvedEvents, 200);
}

public function getEventById($event_id) {
    $event = Event::find($event_id);

    return response()->json($event);
}

    public function getNotifications(){
        try {
            Log::info('Retrieving notifications...');
            $notifications = Notification::with('event')->get()->toArray();
            Log::info('Notifications retrieved successfully.');
            return $notifications;
        } catch (\Exception $e) {
            Log::error('Error retrieving notifications: ' . $e->getMessage());
            return [
            'success' => false,
            'error' => 'Failed to load notifications: ' . $e->getMessage(),
        ];
        }
    }   
}
