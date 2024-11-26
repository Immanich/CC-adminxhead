<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\ServiceTranslation;
use App\Models\Language;

class ServiceTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            ['code' => 'en', 'name' => 'English'],
            ['code' => 'fil', 'name' => 'Filipino'],
        ];

        $services = Service::all();

        foreach ($services as $service) {
            foreach ($languages as $language) {
                $translation = [
                    'service_id' => $service->id,
                    'language_id' => Language::where('code', $language['code'])->first()->id,
                    'service_name' => ($language['code'] === 'en')
                        ? $service->service_name
                        : $this->getFilipinoTranslation($service->service_name),
                    'description' => ($language['code'] === 'en')
                        ? $service->description
                        : $this->getFilipinoDescription($service->description),
                ];
                ServiceTranslation::create($translation);
            }
        }
    }

    private function getFilipinoTranslation($serviceName)
    {
        // Define translations for Filipino
        $translations = [
            'PROCESSING OF CLAIMS(MUNICIPAL TRANSACTIONS)' => 'PAGPROSESO NG MGA CLAIM (MUNICIPAL TRANSACTIONS)',
            'ISSUANCE OF CERTIFICATE OF INCOME TAX WITHHELD FROM EMPLOYEES' => 'PAGLABAS NG SERTIPIKO NG INCOME TAX NA ITINAGO MULA SA MGA EMPLEYADO',
            'ISSUANCE OF CERTIFICATE OF NET TAKE HOME PAY' => 'PAGLABAS NG SERTIPIKO NG NET TAKE HOME PAY',
            'ISSUANCE OF CERTIFIED TRUE COPIES OF TAX DECLARATIONS' => 'PAGLABAS NG SERTIPIKADONG KOPIYA NG TAX DECLARATIONS',
            'REQUEST FOR ISSUANCE OF TAX DECLARATIONS FOR NEW DISCOVERIES OF LAND' => 'PAGHINGI NG PAGLABAS NG MGA TAX DECLARATIONS PARA SA BAGONG NADISKUBRENG LUPA',
            // Add more translations as necessary
        ];

        return $translations[$serviceName] ?? $serviceName; // Fallback to original if no translation exists
    }

    private function getFilipinoDescription($description)
    {
        return "Inilarawan para sa serbisyo: {$description}"; // Customize this if needed
    }
}
