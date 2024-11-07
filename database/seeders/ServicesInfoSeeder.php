<?php

namespace Database\Seeders;

use App\Models\ServicesInfo;
use App\Models\Service;
use App\Models\Office;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicesInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $service = Service::first();
        $accounting = Office::where('id', 1)->first();
        $accountingService1 = Service::where('id', 1)
            ->where('office_id', $accounting->id)
            ->first();
        $accountingService2 = Service::where('id', 2)
            ->where('office_id', $accounting->id)
            ->first();
        $accountingService3 = Service::where('id', 3)
            ->where('office_id', $accounting->id)
            ->first();
        $accountingService4 = Service::where('id', 4)
            ->where('office_id', $accounting->id)
            ->first();

        $assessor = Office::where('id', 2)->first();
        $assessorService1 = Service::where('id', 5)
            ->where('office_id', $assessor->id)
            ->first();
        $assessorService2 = Service::where('id', 6)
            ->where('office_id', $assessor->id)
            ->first();

            ServicesInfo::create([
                'service_id' => $accountingService1->id,
                'office_id' => $accounting->id,
                'step' => 1.1,
                'info_title' => '',
                'clients' => json_encode([
                    'A. Submit the Disbursement Voucher/ Liquidation of Cash Advance Report and the supporting documents for Pre-Audit. Wait while the documents are being evaluated and reviewed.',
                    '1. Records/pre-numbers DVs/payrolls and prepares JEV',
                    '2. Pre-audits claim per DVs/payroll and supporting docs',
                    '3. Verifies/controls Obligation of Gen. Fund & SEF and Controls/monitors Trust Fund disbursements',
                    '4. Final review and signature of theAccountant',
                    "B. Submit the Pre-Audited voucher to the Treasurer's Office for signing as to availability of funds and preparation of check.",
                    'C. Secure the approval and signature of the Municipal Mayor',
                    "D. Return the approved/signed check together with the voucher and supporting documents to the Accounting Office for the Withholding Tax Certificate and Accountant's advice.",
                    "E. Sign the voucher, receive the check and accountant's advice"
                ]),
                'agency_action' => json_encode(['Evaluates and Reviews submitted documents.']),
                'fees' => json_encode(['0']),
                'processing_time' => json_encode([
                    '',
                    "Simple - average of 2 minutes; Complex - average of 4 minutes",
                    "Simple - average of 5 minutes; Complex - average of 30 minutes",
                    "General Fund/SEF - average of 2 mins; rust Fund - average of 3 minutes",
                    "Simple - average of 2 minutes; Complex - average of 10 minutes",
                    "5 minutes",
                    "5 minutes",
                    "5 minutes",
                    "2 minutes",
                ]),
                'person_responsible' => json_encode([
                    '',
                    'Melka Marabiles(for General Fund Dvs); Analou Casao (for Trust Fund & SEF Dvs)',
                    'Accounting Staff',
                    'Marisol Sibanta (for General Fund Dvs); Analou Casao (for Trust Fund & SEF Dvs)',
                    'Municipal Accountant',
                    'Maria Lourdes D. Lamanilao (Municipal Treasurer)',
                    'Engr. William R. Jao (Municipal Mayor)',
                    'Accounting Staff & Hennessy D. Muga (Municipal Accountant)',
                    "Treasurer's Office Staff",
                ]),
                'total_fees' => '0',
                'total_response_time' => '64 minutes',
                'note' => '',
            ]);

            ServicesInfo::create([
                    'service_id' => $accountingService2->id,
                    'office_id' => $accounting->id,
                    'step' => 1.1,
                    'info_title' => '',
                    'clients' => json_encode([
                        'A. Register in the logbook and state your request.',
                        'B. Wait while the requested documents being prepared by the employee in-charge',
                        'C. Receive the document requested.',
                    ]),
                    'agency_action' => json_encode([
                        '',
                        'Prepares the requested document. Signs the prepared document',
                        'Releases the requested document']),
                    'fees' => json_encode(['0']),
                    'processing_time' => json_encode([  
                        "3 mins",
                        "5 mins & 1 min",
                        "1 min",
                    ]),
                    'person_responsible' => json_encode([
                        'Marisol Sibanta - AO III',
                        'Marisol Sibanta - AO III',
                        'Treasurer’s Office Staff',
                    ]),
                    'total_fees' => "None",
                    'total_response_time' => '10 minutes',
                    'note' => '',
                ]);
            ServicesInfo::create([
                'service_id' => $accountingService3->id,
                'office_id' => $accounting->id,
                'step' => 1.1,
                'info_title' => '',
                'clients' => json_encode([
                    'A. Register in the logbook and state your request.',
                    'B. Wait while the requested documents being prepared by the employee in-charge',
                    'C. Receive the document requested.',
                ]),
                'agency_action' => json_encode([
                    '',
                    'Prepares the requested document. Signs the prepared document',
                    'Releases the requested document']),
                'fees' => json_encode(["None"]),
                'processing_time' => json_encode([  
                    "3 mins",
                    "5 mins & 1 min",
                    "1 min",
                ]),
                'person_responsible' => json_encode([
                    'Marisol Sibanta - AO III',
                    'Marisol Sibanta - AO III & Mun. Accountant',
                    'Marisol Sibanta - AO III',
                ]),
                'total_fees' => "None",
                'total_response_time' => '10 minutes',
                'note' => '',
            ]);

            ServicesInfo::create([
                'service_id' => $accountingService4->id,
                'office_id' => $accounting->id,
                'step' => 1.1,
                'info_title' => '',
                'clients' => json_encode([
                    '1. Submit the Disbursement Vouchers and the supporting documents for evaluation and review. Attached JEVs for audited vouchers.',
                    '2. Submit the evaluated and reviewed Disbursement vouchers and the supporting documents to the Accountant for final approval',
                    '3. Submit the Punong Barangay Certifications for the Accountants approval',
                    '4. Within twenty (20) days after the end of each month, submit all of the Disbursement Vouchers transacted within the previous month with thesupporting documents for final evaluation. Submit also copies of Punong Barangay Certifications and the transmittal report.',
                ]),
                'agency_action' => json_encode([
                    'Evaluates and reviews the submitted documents',
                    'Check & review the submitted documents',
                    'Check & review the submitted documents',
                    'Check & review the submitted documents',
                ]),
                'fees' => json_encode(["None"]),
                'processing_time' => json_encode([  
                    "15 mins",
                    "5 mins",
                    "1 min",
                    "10 mins",
                ]),
                'person_responsible' => json_encode([
                    'Brgy. Bookkeeper/ Accounting Office',
                    'Municipal Accountant',
                    'Municipal Accountant',
                    'Brgy. Bookkeeper/Accounting Office',
                ]),
                'total_fees' => "None",
                'total_response_time' => '31 minutes',
                'note' => '',
            ]);
            // $accountingServicesInfo = [
            //     [
            //         'service_id' => $accountingService1->id,
            //         'office_id' => $accounting->id,
            //         'step' => 1.1,
            //         'info_title' => '',
            //         'clients' => json_encode([
            //             'A. Submit the Disbursement Voucher/ Liquidation of Cash Advance Report and the supporting documents for Pre-Audit. Wait while the documents are being evaluated and reviewed.',
            //             '1. Records/pre-numbers DVs/payrolls and prepares JEV',
            //             '2. Pre-audits claim per DVs/payroll and supporting docs',
            //             '3. Verifies/controls Obligation of Gen. Fund & SEF and Controls/monitors Trust Fund disbursements',
            //             '4. Final review and signature of theAccountant',
            //             "B. Submit the Pre-Audited voucher to the Treasurer's Office for signing as to availability of funds and preparation of check.",
            //             'C. Secure the approval and signature of the Municipal Mayor',
            //             "D. Return the approved/signed check together with the voucher and supporting documents to the Accounting Office for the Withholding Tax Certificate and Accountant's advice.",
            //             "E. Sign the voucher, receive the check and accountant's advice"
            //         ]),
            //         'agency_action' => json_encode(['Evaluates and Reviews submitted documents.']),
            //         'fees' => json_encode(['0']),
            //         'processing_time' => json_encode([
            //             '',
            //             "Simple - average of 2 minutes; Complex - average of 4 minutes",
            //             "Simple - average of 5 minutes; Complex - average of 30 minutes",
            //             "General Fund/SEF - average of 2 mins; rust Fund - average of 3 minutes",
            //             "Simple - average of 2 minutes; Complex - average of 10 minutes",
            //             "5 minutes",
            //             "5 minutes",
            //             "5 minutes",
            //             "2 minutes",
            //         ]),
            //         'person_responsible' => json_encode([
            //             '',
            //             'Melka Marabiles(for General Fund Dvs); Analou Casao (for Trust Fund & SEF Dvs)',
            //             'Accounting Staff',
            //             'Marisol Sibanta (for General Fund Dvs); Analou Casao (for Trust Fund & SEF Dvs)',
            //             'Municipal Accountant',
            //             'Maria Lourdes D. Lamanilao (Municipal Treasurer)',
            //             'Engr. William R. Jao (Municipal Mayor)',
            //             'Accounting Staff & Hennessy D. Muga (Municipal Accountant)',
            //             "Treasurer's Office Staff",
            //         ]),
            //         'total_fees' => '0',
            //         'total_response_time' => '64 minutes',
            //         'note' => '',
            //     ],
            //     [
            //         'service_id' => $accountingService2->id,
            //         'office_id' => $accounting->id,
            //         'step' => 1.1,
            //         'info_title' => '',
            //         'clients' => json_encode([
            //             'A. Register in the logbook and state your request.',
            //             'B. Wait while the requested documents being prepared by the employee in-charge',
            //             'C. Receive the document requested.',
            //         ]),
            //         'agency_action' => json_encode([
            //             '',
            //             'Prepares the requested document. Signs the prepared document',
            //             'Releases the requested document']),
            //         'fees' => json_encode(['0']),
            //         'processing_time' => json_encode([  
            //             "3 mins",
            //             "5 mins & 1 min",
            //             "1 min",
            //         ]),
            //         'person_responsible' => json_encode([
            //             'Marisol Sibanta - AO III',
            //             'Marisol Sibanta - AO III',
            //             'Treasurer’s Office Staff',
            //         ]),
            //         'total_fees' => "None",
            //         'total_response_time' => '10 minutes',
            //         'note' => '',
            //     ],
            //     [
            //         'service_id' => $accountingService3->id,
            //         'office_id' => $accounting->id,
            //         'step' => 1.1,
            //         'info_title' => '',
            //         'clients' => json_encode([
            //             'A. Register in the logbook and state your request.',
            //             'B. Wait while the requested documents being prepared by the employee in-charge',
            //             'C. Receive the document requested.',
            //         ]),
            //         'agency_action' => json_encode([
            //             '',
            //             'Prepares the requested document. Signs the prepared document',
            //             'Releases the requested document']),
            //         'fees' => json_encode(["None"]),
            //         'processing_time' => json_encode([  
            //             "3 mins",
            //             "5 mins & 1 min",
            //             "1 min",
            //         ]),
            //         'person_responsible' => json_encode([
            //             'Marisol Sibanta - AO III',
            //             'Marisol Sibanta - AO III & Mun. Accountant',
            //             'Marisol Sibanta - AO III',
            //         ]),
            //         'total_fees' => "None",
            //         'total_response_time' => '10 minutes',
            //         'note' => '',
            //     ],
            //     [
            //         'service_id' => $accountingService4->id,
            //         'office_id' => $accounting->id,
            //         'step' => 1.1,
            //         'info_title' => '',
            //         'clients' => json_encode([
            //             '1. Submit the Disbursement Vouchers and the supporting documents for evaluation and review. Attached JEVs for audited vouchers.',
            //             '2. Submit the evaluated and reviewed Disbursement vouchers and the supporting documents to the Accountant for final approval',
            //             '3. Submit the Punong Barangay Certifications for the Accountants approval',
            //             '4. Within twenty (20) days after the end of each month, submit all of the Disbursement Vouchers transacted within the previous month with thesupporting documents for final evaluation. Submit also copies of Punong Barangay Certifications and the transmittal report.',
            //         ]),
            //         'agency_action' => json_encode([
            //             'Evaluates and reviews the submitted documents',
            //             'Check & review the submitted documents',
            //             'Check & review the submitted documents',
            //             'Check & review the submitted documents',
            //         ]),
            //         'fees' => json_encode(["None"]),
            //         'processing_time' => json_encode([  
            //             "15 mins",
            //             "5 mins",
            //             "1 min",
            //             "10 mins",
            //         ]),
            //         'person_responsible' => json_encode([
            //             'Brgy. Bookkeeper/ Accounting Office',
            //             'Municipal Accountant',
            //             'Municipal Accountant',
            //             'Brgy. Bookkeeper/Accounting Office',
            //         ]),
            //         'total_fees' => "None",
            //         'total_response_time' => '31 minutes',
            //         'note' => '',
            //     ],
            // ];

            // $assessorServicesInfo = [
            //     [
            //         'service_id' => $assessorService1->id,
            //         'office_id' => $assessor->id,
            //         'step' => 1.1,
            //         'info_title' => '',
            //         'clients' => json_encode([
            //             '1. Request for a certified true copy',
            //             '2. Wait while the requested documents are being retrieved.',
            //             '3. Pay the certification fee to the Municipal Treasurer’s Office',
            //             '4. Present the Official Receipt',
            //             '5. Wait',
            //             '6. Receives the certified copy',
            //         ]),
            //         'agency_action' => json_encode([
            //             'Interviews the client the tax declaration number',
            //             'Retrieve the requested TD thru the RPTIS, if not available informed client',
            //             'Prepared the requested tax declaration',
            //             'Encode the OR on the requested TD',
            //             'Let the Municipal Assessor signed the certified TD',
            //             'Release the certified TD',
            //         ]),
            //         'fees' => json_encode([
            //             '',
            //             '',
            //             'Php 75.00',
            //         ]),
            //         'processing_time' => json_encode([  
            //             "1 min",
            //             "4 mins",
            //             "5 mins",
            //             "1 min",
            //             "1 min",
            //             "1 min",
            //         ]),
            //         'person_responsible' => json_encode([
            //             "Assessor's Staff",
            //             "Assessor's Staff",
            //             "Assessor's Staff",
            //             "Assessor's Staff",
            //             "Assessor's Staff",
            //             "Assessor's Staff",
            //         ]),
            //         'total_fees' => "Php 75.00",
            //         'total_response_time' => '13 minutes',
            //         'note' => 'Note: 13 minutes serving time per tax declaration and it may be extended if two or more tax declarations.',
            //     ],
            //     [
            //         'service_id' => $assessorService2->id,
            //         'office_id' => $assessor->id,
            //         'step' => 1.1,
            //         'info_title' => '',
            //         'clients' => json_encode([
            //             '1. Request for the issuance of new tax declaration',
            //             '2. Present all the requirements needed',
            //             '3. Wait',
            //             '4. Wait',
            //             '5. Wait and needs to follow-up until the TD be approved by the Provincial Assessor',
            //             '6. Wait for the computation of the assessed value and upon instruction of the staff pay the realty tax at the Municipal Treasurer’s Office',
            //             '7. Received the new tax declaration',
            //         ]),
            //         'agency_action' => json_encode([
            //             '',
            //         ]),
            //         'fees' => json_encode([
            //             '',
            //             '',
            //             '',
            //             '',
            //             '',
            //             'Realty tax depends on the assessment',
            //         ]),
            //         'processing_time' => json_encode([  
            //             "3 mins",
            //             "10 mins",
            //             "20 mins",
            //             "1 min",
            //             "3 to 15 days since submission to the Prov’l. Assessor’s Office",
            //             "20 mins",
            //             "1 min",
            //         ]),
            //         'person_responsible' => json_encode([
            //             "Assessor's Staff",
            //             "Assessor's Staff",
            //             "Assessor's Staff",
            //             "Municipal Assessor",
            //             "Provincial Assessor",
            //             "Treasurer's Staff",
            //             "Assessor's Staff",
            //         ]),
            //         'total_fees' => "None",
            //         'total_response_time' => '34 minutes',
            //         'note' => '',
            //     ],
            // ];


            // foreach($accountingServicesInfo as $accountingSi) {
            //     ServicesInfo::create($accountingSi);
            // }

            // foreach($assessorServicesInfo as $assessorSi) {
            //     ServicesInfo::create($assessorSi);
            // }
    }
}

// namespace Database\Seeders;

// use App\Models\Service;
// use App\Models\ServicesInfo;
// use Illuminate\Database\Seeder;

// class ServicesInfoSeeder extends Seeder
// {
//     public function run(): void
//     {
//         $service = Service::first(); // Assuming there's at least one service

//         $servicesInfo = [
//             [
//                 'service_id' => $service->id,
//                 'office_id' => $service->office->id,
//                 'step' => 1.1,
//                 'info_title' => 'Submission and Pre-Audit of Documents',
//                 'clients' => json_encode([
//                     'A. Submit the Disbursement Voucher/ Liquidation of Cash Advance Report and the supporting documents for Pre-Audit.',
//                     '1. Records/pre-numbers DVs/payrolls and prepares JEV',
//                     '2. Pre-audits claim per DVs/payroll and supporting docs',
//                     '3. Verifies/controls Obligation of Gen. Fund & SEF and Controls/monitors Trust Fund disbursements',
//                     '4. Final review and signature of the Accountant',
//                     "B. Submit the Pre-Audited voucher to the Treasurer's Office for signing as to availability of funds and preparation of check.",
//                     'C. Secure the approval and signature of the Municipal Mayor',
//                     "D. Return the approved/signed check together with the voucher and supporting documents to the Accounting Office for the Withholding Tax Certificate and Accountant's advice.",
//                     "E. Sign the voucher, receive the check and accountant's advice",
//                     "E. Sign the voucher, receive the check and accountant's advice"
//                 ]),
//                 'agency_action' => json_encode([
//                     'Evaluates and reviews the submitted documents.',
//                     'Prepares the necessary financial documents for processing.',
//                     'Prepares the necessary financial documents for processing.',
//                 ]),
//                 'fees' => 0, // No specific fees for these steps
//                 'processing_time' => json_encode([
//                     'Simple - average of 2 minutes; Complex - average of 4 minutes',
//                     'Simple - average of 5 minutes; Complex - average of 30 minutes',
//                     'General Fund/SEF - average of 2 minutes; Trust Fund - average of 3 minutes',
//                     'Simple - average of 2 minutes; Complex - average of 10 minutes',
//                     '5 minutes',
//                     '5 minutes',
//                     '5 minutes',
//                     '2 minutes',
//                     '2 minutes',
//                 ]),
//                 'person_responsible' => json_encode([
//                     '',
//                     'Melka Marabiles (for General Fund DVs); Analou Casao (for Trust Fund & SEF DVs)',
//                     'Accounting Staff',
//                     'Marisol Sibanta (for General Fund DVs); Analou Casao (for Trust Fund & SEF DVs)',
//                     'Municipal Accountant',
//                     'Maria Lourdes D. Lamanilao (Municipal Treasurer)',
//                     'Engr. William R. Jao (Municipal Mayor)',
//                     'Accounting Staff & Hennessy D. Muga (Municipal Accountant)',
//                 ]),
//                 'total_fees' => 0, // Explicitly setting the total_fees to 0
//                 'total_response_time' => '0',  // Explicitly set the total_response_time
//             ],
//             // Add other entries if necessary
//         ];

//         // Loop through and create a new ServicesInfo record for each item
//         foreach ($servicesInfo as $info) {
//             ServicesInfo::create($info);
//         }
//     }
// }
