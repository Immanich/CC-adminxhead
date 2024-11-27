<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transaction = Transaction::where('type_of_transaction', 'G2G-Government to Government')->first();

        $services = [
            [
                'service_name' => 'PROCESSING OF CLAIMS (MUNICIPAL TRANSACTIONS)',
                'description' => "To safeguard the use and disposition of the Municipal Government's assets and to determine its liabilities from claims, pre-audit is undertaken by the Municipal Accountant to determine that all necessary supporting documents of vouchers/claims are submitted.",
                'office_id' => 1,
                'classification' => 'SIMPLE',
                'transaction_id' => $transaction->id,
                'checklist_of_requirements' => json_encode([
                    '1. Disbursement vouchers, payrolls & supporting documents',
                    '2. Pre-numbered and pre-audited DVs and payrolls',
                    '3. Duly filed up/dated/signed supporting documents',
                    "4. Audited DV's with duly accomplished Obligation Request (OBR) by the MBO",
                    '5. Audited & obligated DVs, payrolls and duly filled up/signed/dated supporting documents'
                ]),
                'where_to_secure' => json_encode([
                    'ACCOUNTING OFFICE',
                    'ACCOUNTING OFFICE',
                    'ACCOUNTING OFFICE',
                    'ACCOUNTING OFFICE',
                    'ACCOUNTING OFFICE'
                ])
            ],
            [
                'service_name' => 'PROCESSING OF CLAIMS (MUNICIPAL TRANSACTIONS)',
                'description' => "To safeguard the use and disposition of the Municipal Government's assets and to determine its liabilities from claims, pre-audit is undertaken by the Municipal Accountant to determine that all necessary supporting documents of vouchers/ claims are submitted.",
                'office_id' => 1,
                'classification' => 'SIMPLE',
                'transaction_id' => $transaction->id,
                'checklist_of_requirements' =>  json_encode([
                    '1. Disbursement vouchers, payrolls & supporting documents',
                    '2. Pre-numbered and pre-audited DVs and payrolls',
                    '3. Duly filed up/dated/signed supporting documents',
                    "4. Audited DV's with duly accomplished Obligation Request (OBR) by the MBO",
                    '5. Audited & obligated DVs, payrolls and duly filled up/signed/dated supporting documents'
                ]),
                'where_to_secure' => 'ACCOUNTING OFFICE'
            ],
        ];

        foreach($services as $service) {
            Service::create($service);
        }
    }
}
