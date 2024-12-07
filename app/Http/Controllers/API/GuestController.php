<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Office;
use App\Models\Service;
use App\Models\ServicesInfo;
use App\Models\OfficeTranslation;
use App\Models\ServiceTranslation;
use App\Models\Notification;
use App\Models\ElectedOfficial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GuestController extends Controller
{

    public function getOffices(Request $request)
{
    $languageCode = $request->query('lang', 'en');
    $validLanguages = ['en', 'fil'];

    if (!in_array($languageCode, $validLanguages)) {
        $languageCode = 'en';
    }

    $language = \App\Models\Language::where('code', $languageCode)->first();
    if (!$language) {
        $language = \App\Models\Language::where('code', 'en')->first();
    }

    $translations = OfficeTranslation::where('language_id', $language->id)
        ->get()
        ->keyBy('office_id');

    $offices = Office::all();

    $response = $offices->map(function ($office) use ($translations, $language) {
        $translation = $translations->get($office->id);

        return [
            'id' => $office->id,
            'office_name' => $translation ? $translation->office_name : $office->office_name,
            'description' => $translation ? $translation->description : "Default description for {$office->office_name}",
        ];
    });

    // Return the response
    return response()->json($response);
}




// public function getServicesForOffice($officeId, Request $request)
// {
//     // Fetch only approved services for mobile or general users
//     $services = Service::where('office_id', $officeId)
//         ->where('status', 'approved') // Only fetch approved services
//         ->compact('transaction')
//         ->get();

//     // Return the services as a response
//     return response()->json($services, 200);
// }

    public function getServicesForOffice($officeId, Request $request)
{
    // Fetch only approved services for mobile or general users
    $services = Service::where('office_id', $officeId)
        ->where('status', 'approved') // Only fetch approved services
        ->get();

    // Return the services as a response
    return response()->json($services, 200);
}

//     public function getServicesForOffice($officeId, Request $request)
// {
//     $languageCode = $request->query('lang', 'en');
//     $validLanguages = ['en', 'fil']; 

//     if (!in_array($languageCode, $validLanguages)) {
//         $languageCode = 'en';
//     }

//     $language = \App\Models\Language::where('code', $languageCode)->first();
//     if (!$language) {
//         $language = \App\Models\Language::where('code', 'en')->first();
//     }

//     $services = Service::where('office_id', $officeId)
//         ->where('status', 'approved')
//         ->get();

//     // Fetch translations for the services
//     $translations = ServiceTranslation::where('language_id', $language->id)
//         ->whereIn('service_id', $services->pluck('id'))
//         ->get()
//         ->keyBy('service_id'); 

//     $response = $services->map(function ($service) use ($translations, $language) {
//         $translation = $translations->get($service->id);

//         return [
//             'id' => $service->id,
//             'service_name' => $translation ? $translation->service_name : $service->service_name,
//             'description' => $translation ? $translation->description : "Default description for {$service->service_name}",
//             'classification' => $service->classification,
//             'type_of_transaction' => $service->type_of_transaction,
//             'checklist_of_requirements' => $service->checklist_of_requirements,
//             'where_to_secure' => $service->where_to_secure,
//         ];
//     });

//     // Return the services with translations
//     return response()->json($response, 200);
// }



public function getServiceInfo($office_id, $service_id, Request $request)
{
    $languageCode = $request->query('lang', 'en');

    try {
        // Get the language ID from the languages table
        $languageId = \App\Models\Language::where('code', $languageCode)->value('id');
        
        if (!$languageId) {
            return response()->json(['error' => 'Language not found'], 400);
        }

        $service = Service::where('office_id', $office_id)
            ->where('id', $service_id)
            ->with(['servicesInfos.translations', 'transaction'])
            ->first();

        if (!$service) {
            return response()->json(['error' => 'Service info not found'], 404);
        }

        $translatedServiceInfos = $service->servicesInfos->map(function ($info) use ($languageId) {
            $translation = $info->translations->where('language_id', $languageId)->first();
            return [
                'step' => $info->step,
                'info_title' => $translation ? $translation->service_info_name : $info->info_title,
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
            $notifications = Notification::with('event')
                ->whereHas('service', function($query) {
                    // Only fetch notifications for approved services
                    $query->where('status', 'approved');
                })
                ->get()
                ->toArray();
            
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
// <?php

// namespace App\Http\Controllers\API;

// use App\Http\Controllers\Controller;
// use App\Models\Event;
// use App\Models\Office;
// use App\Models\Service;
// use App\Models\ServicesInfo;
// use App\Models\OfficeTranslation;
// use App\Models\ServiceTranslation;
// use App\Models\Notification;
// use App\Models\ElectedOfficial;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Log;
// use Carbon\Carbon;

// class GuestController extends Controller
// {

//     public function getOffices(Request $request)
// {
//     // Get the language code from the query parameter, default to 'en' if not provided or invalid.
//     $languageCode = $request->query('lang', 'en');
//     $validLanguages = ['en', 'fil'];  // Allowed languages

//     if (!in_array($languageCode, $validLanguages)) {
//         // If the language is invalid, fall back to 'en'
//         $languageCode = 'en';
//     }

//     // Fetch the language record based on the 'code' column (not 'language_code')
//     $language = \App\Models\Language::where('code', $languageCode)->first();
//     if (!$language) {
//         // If no language record is found, fall back to the default language (English)
//         $language = \App\Models\Language::where('code', 'en')->first();
//     }

//     // Fetch office translations based on the language ID
//     $translations = OfficeTranslation::where('language_id', $language->id)
//         ->get()
//         ->keyBy('office_id'); // Key by office_id for easy lookup

//     $offices = Office::all();

//     $response = $offices->map(function ($office) use ($translations, $language) {
//         // Get the translation for the office if available
//         $translation = $translations->get($office->id);

//         return [
//             'id' => $office->id,
//             'office_name' => $translation ? $translation->office_name : $office->office_name,
//             'description' => $translation ? $translation->description : "Default description for {$office->office_name}",
//         ];
//     });

//     // Return the response
//     return response()->json($response);
// }




// public function getServicesForOffice($officeId, Request $request)
// {
//     // Fetch only approved services for mobile or general users
//     $services = Service::where('office_id', $officeId)
//         ->where('status', 'approved') // Only fetch approved services
//         ->get();

//     // Return the services as a response
//     return response()->json($services, 200);
// }


// public function getServiceInfo($office_id, $service_id, Request $request)
// {
//     $languageCode = $request->query('lang', 'en');

//     try {
//         // Get the language ID from the languages table
//         $languageId = \App\Models\Language::where('code', $languageCode)->value('id');
        
//         if (!$languageId) {
//             return response()->json(['error' => 'Language not found'], 400);
//         }

//         $service = Service::where('office_id', $office_id)
//             ->where('id', $service_id)
//             ->with(['servicesInfos.translations', 'transaction'])
//             ->first();

//         if (!$service) {
//             return response()->json(['error' => 'Service info not found'], 404);
//         }

//         $translatedServiceInfos = $service->servicesInfos->map(function ($info) use ($languageId) {
//             $translation = $info->translations->where('language_id', $languageId)->first();
//             return [
//                 'step' => $info->step,
//                 'info_title' => $translation ? $translation->service_info_name : $info->info_title,
//                 'clients' => $info->clients,
//                 'agency_action' => $info->agency_action,
//                 'fees' => $info->fees,
//                 'processing_time' => $info->processing_time,
//                 'person_responsible' => $info->person_responsible,
//                 'total_fees' => $info->total_fees,
//                 'total_response_time' => $info->total_response_time,
//                 'note' => $info->note,
//             ];
//         });

//         return response()->json([
//             'id' => $service->id,
//             'service_name' => $service->service_name,
//             'description' => $service->description,
//             'office_id' => $service->office_id,
//             'classification' => $service->classification,
//             'type_of_transaction' => $service->transaction?->type_of_transaction,
//             'status' => $service->status,
//             'checklist_of_requirements' => $service->checklist_of_requirements,
//             'where_to_secure' => $service->where_to_secure,
//             'created_at' => $service->created_at,
//             'updated_at' => $service->updated_at,
//             'services_infos' => $translatedServiceInfos,
//         ], 200);
//     } catch (\Exception $e) {
//         Log::error("Error in getServiceInfo: " . $e->getMessage());
//         return response()->json(['error' => 'Internal server error'], 500);
//     }
// }



// public function getEvents()
// {
//     // Fetch all approved events
//     $approvedEvents = Event::where('status', 'approved')->get();
    
//     // Return JSON response with approved events
//     return response()->json($approvedEvents, 200);
// }

// public function getEventById($event_id) {
//     $event = Event::find($event_id);

//     return response()->json($event);
// }

//     public function getNotifications(){
//         try {
//             Log::info('Retrieving notifications...');
//             $notifications = Notification::with('event')
//                 ->whereHas('service', function($query) {
//                     // Only fetch notifications for approved services
//                     $query->where('status', 'approved');
//                 })
//                 ->get()
//                 ->toArray();
            
//             Log::info('Notifications retrieved successfully.');
//             return $notifications;
//         } catch (\Exception $e) {
//             Log::error('Error retrieving notifications: ' . $e->getMessage());
//             return [
//                 'success' => false,
//                 'error' => 'Failed to load notifications: ' . $e->getMessage(),
//             ];
//         }
//     }
// }
