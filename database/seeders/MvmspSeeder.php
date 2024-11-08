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
        $mvmspData = [
            [
                'office_id' => 1,
                'mandate' => 'Mandate for Accounting Office...',
                'vision' => 'Vision for Accounting Office...',
                'mission' => 'Mission for Accounting Office...',
                'service_pledge' => 'Service pledge for Accounting Office...',
            ],
            // Additional entries for other offices
        ];

        foreach ($mvmspData as $data) {
            Mvmsp::create($data);
        }
    }
}