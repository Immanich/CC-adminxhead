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
        Office::create([
            'office_name' => 'Accounting Office',
            'address' => '1st floor, Mun. Bldg., Potohan, Tubigon',
            'email' => 'hdmugacpa@gmail.com ',
            'mobile_number' => '09177710105',
            'tel_number' => '5107007',
        ]);
        Office::create([
            'office_name' => "Assessor's Office",
            'address' => '1st floor, Mun. Bldg., Potohan, Tubigon',
            'email' => 'jessicainsonwise@gmail.com',
            'mobile_number' => '09177710140',
            'tel_number' => '5107016',
        ]);
        Office::create([
            'office_name' => 'Business Permits & Licensing Office',
            'address' => 'testing',
            'email' => 'testing',
            'mobile_number' => 'testing',
            'tel_number' => 'testing',
        ]);
        Office::create([
            'office_name' => 'Engineering Office',
            'address' => 'testing',
            'email' => 'testing',
            'mobile_number' => 'testing',
            'tel_number' => 'testing',
        ]);
        Office::create([
            'office_name' => 'Human Resource & Management Office',
            'address' => 'testing',
            'email' => 'testing',
            'mobile_number' => 'testing',
            'tel_number' => 'testing',
        ]);
        Office::create([
            'office_name' => "Mayor's Office",
            'address' => 'testing',
            'email' => 'testing',
            'mobile_number' => 'testing',
            'tel_number' => 'testing',
        ]);
        Office::create([
            'office_name' => 'Municipal Disaster Risk Reduction and Management Office(MDRRMO)',
            'address' => 'testing',
            'email' => 'testing',
            'mobile_number' => 'testing',
            'tel_number' => 'testing',
        ]);
        Office::create([
            'office_name' => 'Municipal Environment & Natural Resources Office (MENRO)',
            'address' => 'testing',
            'email' => 'testing',
            'mobile_number' => 'testing',
            'tel_number' => 'testing',
        ]);
        Office::create([
            'office_name' => 'Municipal Ecological Solid Waste Managament Office (ESWMO)',
            'address' => 'testing',
            'email' => 'testing',
            'mobile_number' => 'testing',
            'tel_number' => 'testing',
        ]);
        Office::create([
            'office_name' => "Municipal Local Government Operations Office (MLGOO)",
            'address' => 'testing',
            'email' => 'testing',
            'mobile_number' => 'testing',
            'tel_number' => 'testing',
        ]);
        Office::create([
            'office_name' => "Municipal Planning & Development Coordinator's Office (MPDCO)",
            'address' => 'testing',
            'email' => 'testing',
            'mobile_number' => 'testing',
            'tel_number' => 'testing',
        ]);
        Office::create([
            'office_name' => "Municipal Social Welfare & Development Office (MSWDO)",
            'address' => 'testing',
            'email' => 'testing',
            'mobile_number' => 'testing',
            'tel_number' => 'testing',
        ]);
        Office::create([
            'office_name' => 'Municipal Agriculture Office (MAO)',
            'address' => 'testing',
            'email' => 'testing',
            'mobile_number' => 'testing',
            'tel_number' => 'testing',
        ]);
        Office::create([
            'office_name' => 'Municipal Budget Office',
            'address' => 'testing',
            'email' => 'testing',
            'mobile_number' => 'testing',
            'tel_number' => 'testing',
        ]);
        Office::create([
            'office_name' => "Municipal Civil Registrar's Office",
            'address' => 'testing',
            'email' => 'testing',
            'mobile_number' => 'testing',
            'tel_number' => 'testing',
        ]);
        Office::create([
            'office_name' => 'Municipal Health Office',
            'address' => 'testing',
            'email' => 'testing',
            'mobile_number' => 'testing',
            'tel_number' => 'testing',
        ]);
        Office::create([
            'office_name' => 'Municipal Treasurer',
            'address' => 'testing',
            'email' => 'testing',
            'mobile_number' => 'testing',
            'tel_number' => 'testing',
        ]);
        Office::create([
            'office_name' => 'Office of Senior Citizen Affairs (OSCA)',
            'address' => 'testing',
            'email' => 'testing',
            'mobile_number' => 'testing',
            'tel_number' => 'testing',
        ]);
        Office::create([
            'office_name' => 'Office of the Secretary to the Sangguniang Bayan',
            'address' => 'testing',
            'email' => 'testing',
            'mobile_number' => 'testing',
            'tel_number' => 'testing',
        ]);
        Office::create([
            'office_name' => "Stimulation and Therapeutic Activity Center (STAC)",
            'address' => 'testing',
            'email' => 'testing',
            'mobile_number' => 'testing',
            'tel_number' => 'testing',
        ]);
        Office::create([
            'office_name' => 'Toll Roads Office',
            'address' => 'testing',
            'email' => 'testing',
            'mobile_number' => 'testing',
            'tel_number' => 'testing',
        ]);
        Office::create([
            'office_name' => 'Tubigon Community Hospital',
            'address' => 'testing',
            'email' => 'testing',
            'mobile_number' => 'testing',
            'tel_number' => 'testing',
        ]);
        Office::create([
            'office_name' => 'Waterworks Office',
            'address' => 'testing',
            'email' => 'testing',
            'mobile_number' => 'testing',
            'tel_number' => 'testing',
        ]);
    }
}
