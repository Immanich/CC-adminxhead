<?php

namespace App\Http\Controllers;

use App\Models\Mvmsp;
use Illuminate\Http\Request;

class MvmspController extends Controller
{
   // MvmspController.php
// MvmspController.php

// MvmspController.php

public function show()
{
    $user = auth()->user();

    // Attempt to fetch MVMPS data for the user's assigned office
    $officeMvmsp = Mvmsp::where('office_id', $user->office_id)->first();

    // If the user's office doesn't have specific MVMPS data, use the admin's default MVMPS data
    if (!$officeMvmsp) {
        $officeMvmsp = (object) [
            'mandate' => 'The RA 7160 also known as the Local Government Code of 1991 gives local governments powers to ensure the preservation and enhancement of culture, promotion of health and safety, right of people to a balanced ecology, development of technical capabilities, improvement of public morals, economic prosperity and social justice, full employment of residents, peace and order, and the convenience of inhabitants.',
            'vision' => 'TUBIGON is a prime eco-cultural tourism destination and economically vibrant trading center, and productive agro-industrial municipality in the region led by competent, dynamic, and committed leaders, with family-oriented, God-loving, and empowered people sustainably managing the environment.',
            'mission' => 'To create a positive environment for sustainable growth through the provision of effective and efficient services, and sound local governance that will improve the quality of life its citizenry.',
            'service_pledge' => 'We, the officials and employees of the Local Government Unit of Tubigon, do hereby pledge our strong commitment to perform our duties and functions with utmost goal to ensure its citizenry have the opportunity to access enhanced services and enjoy a better quality of life.',
        ];
    }

    return view('pages.mvmsp', compact('officeMvmsp'));
}



}
