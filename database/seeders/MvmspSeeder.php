<?php

namespace Database\Seeders;

use App\Models\Mvmsp;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MvmspSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $officeMvmspData = [
            1 => [
                'mandate' => 'Mandate for Accounting Office...',
                'vision' => 'Vision for Accounting Office...',
                'mission' => 'Mission for Accounting Office...',
                'service_pledge' => 'Service pledge for Accounting Office...',
            ],
            2 => [
                'mandate' => 'Mandate for HR Office...',
                'vision' => 'Vision for HR Office...',
                'mission' => 'Mission for HR Office...',
                'service_pledge' => 'Service pledge for HR Office...',
            ],
            3 => [
                'mandate' => 'Mandate for Engineering Office...',
                'vision' => 'Vision for Engineering Office...',
                'mission' => 'Mission for Engineering Office...',
                'service_pledge' => 'Service pledge for Engineering Office...',
            ],
            // Add more offices as needed...
        ];

        foreach ($officeMvmspData as $officeId => $data) {
            Mvmsp::firstOrCreate(
                ['office_id' => $officeId],
                $data
            );
        }

        // Create the admin's default MVMSP
        Mvmsp::firstOrCreate(
            ['office_id' => null],
            [
                'mandate' => 'The RA 7160 also known as the Local Government Code of 1991 gives local governments powers to ensure the preservation and enhancement of culture, promotion of health and safety, right of people to a balanced ecology, development of technical capabilities, improvement of public morals, economic prosperity and social justice, full employment of residents, peace and order, and the convenience of inhabitants.',
                'vision' => 'TUBIGON is a prime eco-cultural tourism destination and economically vibrant trading center, and productive agro-industrial municipality in the region led by competent, dynamic, and committed leaders, with family-oriented, God-loving, and empowered people sustainably managing the environment.',
                'mission' => 'To create a positive environment for sustainable growth through the provision of effective and efficient services, and sound local governance that will improve the quality of life its citizenry.',
                'service_pledge' => 'We, the officials and employees of the Local Government Unit of Tubigon, do hereby pledge our strong commitment to perform our duties and functions with utmost goal to ensure its citizenry have the opportunity to access enhanced services and enjoy a better quality of life.',
            ]
        );
    }
}
