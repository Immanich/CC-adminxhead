<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Language;
use App\Models\Office;
use App\Models\OfficeTranslation;

class OfficeTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(){
    $languages = [
        ['code' => 'en', 'name' => 'English'],
        ['code' => 'fil', 'name' => 'Filipino'],
    ];

    $offices = Office::all();

    foreach ($offices as $office) {
        foreach ($languages as $language) {
            $translation = [
                'office_id' => $office->id,
                'language_id' => Language::where('code', $language['code'])->first()->id,
                'office_name' => ($language['code'] === 'en') ? $office->office_name : $this->getFilipinoTranslation($office->office_name),
                'description' => ($language['code'] === 'en') ? "Default description" : $this->getFilipinoDescription($office->office_name),
            ];
            OfficeTranslation::create($translation);
        }
    }
}



    private function getFilipinoTranslation($officeName)
    {
        // Define translations for Filipino
        $translations = [
            'Accounting Office' => 'Tanggapan ng Accounting1',
            'Assessor\'s Office' => 'Tanggapan ng Tagatasa1',
            'Business Permits & Licensing Office' => 'Tanggapan ng Mga Pahintulot sa Negosyo at Lisensya',
            'Engineering Office' => 'Tanggapan ng Inhenyeriya',
            'Human Resource & Management Office' => 'Tanggapan ng Human Resource at Pamamahala',
            'Mayor\'s Office' => 'Tanggapan ng Alkalde',
            'MDRMMO' => 'Tanggapan ng MDRMMO',
            'MENRO' => 'Tanggapan ng MENRO',
            'MESWMO' => 'Tanggapan ng MESWMO',
            'MLGOO' => 'Tanggapan ng MLGOO',
            'MPDCO' => 'Tanggapan ng MPDCO',
            'MSWDO' => 'Tanggapan ng MSWDO',
            'Municipal Agriculture' => 'Pagsasaka ng Bayan',
            'Municipal Budget' => 'Badyet ng Bayan',
            'Municipal Civil Registrar' => 'Tagatala ng Sibil ng Bayan',
            'Municipal Health Office' => 'Tanggapan ng Kalusugan ng Bayan',
            'Municipal Treasurer' => 'Tanggapan ng Ingat-Yaman ng Bayan',
            'Senior Citizen Affairs' => 'Usaping Senior Citizen',
            'Secretary to the SB' => 'Kalihim ng SB',
            'STAC' => 'STAC',
            'Toll Roads' => 'Mga Toll Road',
            'Tubigon Community Hospital' => 'Tubigon Community Hospital',
            'Waterworks' => 'Tubig',
        ];

        return $translations[$officeName] ?? $officeName; // Fallback to original if no translation exists
    }

    private function getFilipinoDescription($officeName)
    {
        return "Inilarawan para sa {$officeName}"; // You can customize this as needed
    }
}
