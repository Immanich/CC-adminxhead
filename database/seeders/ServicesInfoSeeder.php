<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServicesInfo;
use Illuminate\Database\Seeder;

class ServicesInfoSeeder extends Seeder
{
    public function run(): void
    {
        $service = Service::first(); // Assuming there's at least one service

        // First entry
        ServicesInfo::create([
            'service_id' => $service->id,
            'office_id' => $service->office->id,
            'step' => 1.1,
            'info_title' => 'Submission and Pre-Audit of Documents',
            'clients' => json_encode([
                'A. Submit the Disbursement Voucher/ Liquidation of Cash Advance Report and the supporting documents for Pre-Audit.',
            ]),
            'agency_action' => json_encode([
                'Evaluates and reviews the submitted documents.',
            ]),
            'fees' => 0,
            'processing_time' => json_encode(['Simple - average of 2 minutes; Complex - average of 4 minutes']),
            'person_responsible' => json_encode(['Melka Marabiles (for General Fund DVs)']),
            'total_fees' => 0,
            'total_response_time' => '0',
        ]);

        // Second entry
        ServicesInfo::create([
            'service_id' => $service->id,
            'office_id' => $service->office->id,
            'step' => 1.2,
            'info_title' => 'Review of Documents',
            'clients' => json_encode([
                '1. Review supporting documents.',
                '2. Confirm the details with the relevant departments.',
            ]),
            'agency_action' => json_encode([
                'Collects necessary information.',
            ]),
            'fees' => 100,
            'processing_time' => json_encode(['Average of 5 minutes']),
            'person_responsible' => json_encode(['Accounting Staff']),
            'total_fees' => 100,
            'total_response_time' => '5 minutes',
        ]);

        // Add more entries as needed
        ServicesInfo::create([
            'service_id' => $service->id,
            'office_id' => $service->office->id,
            'step' => 1.3,
            'info_title' => 'Final Approval',
            'clients' => json_encode(['Get final approval from the Municipal Mayor.']),
            'agency_action' => json_encode(['Finalize and sign the documents.']),
            'fees' => 0,
            'processing_time' => json_encode(['Average of 10 minutes']),
            'person_responsible' => json_encode(['Municipal Mayor']),
            'total_fees' => 0,
            'total_response_time' => '10 minutes',
        ]);
    }
}
