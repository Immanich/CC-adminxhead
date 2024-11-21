<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offices = [
            [
                'office_name' => 'Accounting Office',
                // 'status' => "approved",
                // 'description' => 'The central office for all services.',
            ],
            [
                'office_name' => "Assessor's Office",
                // 'status' => "approved",
                // 'description' => 'The central office for all services.',
            ],
            [
                'office_name' => 'Business Permits & Licensing Office',
                // 'status' => "approved",
                // 'description' => 'The central office for all services.',
            ],
            [
                'office_name' => 'Engineering Office',
                // 'status' => "approved",
                // 'description' => 'The central office for all services.',
            ],
            [
                'office_name' => 'Human Resource & Management Office',
                // 'status' => "approved",
                // 'description' => 'The central office for all services.',
            ],
            [
                'office_name' => "Mayor's Office",
                // 'status' => "approved",
                // 'description' => 'The central office for all services.',
            ],
            [
                'office_name' => 'MDRMMO',
                // 'status' => "approved",
                // 'description' => 'The central office for all services.',
            ],
            [
                'office_name' => 'MENRO',
                // 'status' => "approved",
                // 'description' => 'The central office for all services.',
            ],
            [
                'office_name' => 'MESWMO',
                // 'status' => "approved",
                // 'description' => 'The central office for all services.',
            ],
            [
                'office_name' => 'MLGOO',
                // 'status' => "approved",
                // 'description' => 'The central office for all services.',
            ],
            [
                'office_name' => 'MPDCO',
                // 'status' => "approved",
                // 'description' => 'The central office for all services.',
            ],
            [
                'office_name' => 'MSWDO',
                // 'status' => "approved",
                // 'description' => 'The central office for all services.',
            ],
            [
                'office_name' => 'Municipal Agriculture',
                // 'status' => "approved",
                // 'description' => 'The central office for all services.',
            ],
            [
                'office_name' => 'Municipal Budget',
                // 'status' => "approved",
                // 'description' => 'The central office for all services.',
            ],
            [
                'office_name' => 'Municipal Civil Registrar',
                // 'status' => "approved",
                // 'description' => 'The central office for all services.',
            ],
            [
                'office_name' => 'Municipal Health Office',
                // 'status' => "approved",
                // 'description' => 'The central office for all services.',
            ],
            [
                'office_name' => 'Municipal Treasurer',
                // 'status' => "approved",
                // 'description' => 'The central office for all services.',
            ],
            [
                'office_name' => 'Senior Citizen Affairs',
                // 'status' => "approved",
                // 'description' => 'The central office for all services.',
            ],
            [
                'office_name' => 'Secretary to the SB',
                // 'status' => "approved",
                // 'description' => 'The central office for all services.',
            ],
            [
                'office_name' => 'STAC',
                // 'status' => "approved",
                // 'description' => 'The central office for all services.',
            ],
            [
                'office_name' => 'Toll Roads',
                // 'status' => "approved",
                // 'description' => 'The central office for all services.',
            ],
            [
                'office_name' => 'Tubigon Community Hospital',
                // 'status' => "approved",
                // 'description' => 'The central office for all services.',
            ],
            [
                'office_name' => 'Waterworks',
                // 'status' => "approved",
                // 'description' => 'The central office for all services.',
            ],
        ];

        foreach($offices as $office) {
            Office::create($office);
        }
    }
}
