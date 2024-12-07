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
            ['code' => 'fil', 'name' => 'Filipino'], // Changed to Filipino
        ];

        $services = Service::all();

        foreach ($services as $service) {
            foreach ($languages as $language) {
                $translation = [
                    'service_id' => $service->id,
                    'language_id' => Language::where('code', $language['code'])->first()->id,
                    'description' => ($language['code'] === 'en')
                        ? $service->description
                        : $this->getFilipinoDescription($service->description),
                    'checklist_of_requirements' => ($language['code'] === 'en')
                        ? $service->checklist_of_requirements
                        : $this->getFilipinoChecklist($service->checklist_of_requirements),
                    'where_to_secure' => ($language['code'] === 'en')
                        ? $service->where_to_secure
                        : $this->getFilipinoWhereToSecure($service->where_to_secure),
                ];
                ServiceTranslation::create($translation);
            }
        }
    }

    // Define translations for the service descriptions in Filipino
    private function getFilipinoDescription($description)
    {
        $translations = [
            'To safeguard the use and disposition of the Municipal Government\'s assets and to determine its liabilities from claims, pre-audit is undertaken by the Municipal Accountant to determine that all necessary supporting documents of vouchers/claims are submitted.' =>
                'Aron maprotektahan ang paggamit ug pagpanag-iya sa mga asset sa Munisipyo ug matino ang mga obligasyon gikan sa mga claim, ang pre-audit ipahigayon sa Munisipal nga Accountant aron masiguro nga ang tanang kinahanglanon nga mga dokumento sa mga vouchers/claims gisumite.',
            'Government employees\' income taxes are withheld pursuant to the National Internal Revenue Code. The Certificate of Compensation Payment/Tax withheld is annually given to show proof that tax due to employees had been paid.' =>
                'Ang mga buwis sa kita sa mga empleyado sa gobyerno gipugos subay sa National Internal Revenue Code. Ang Sertipiko sa Pagbayad sa Kompensasyon/Withheld Tax gihatag matag tuig aron ipakita ang ebidensya nga ang buwis nga angay bayran sa mga empleyado nabayran.',
            'Employees shall secure from the Municipal Accounting Office the certificate of net take home pay for whatever purpose it may serve them.' =>
                'Ang mga empleyado kinahanglan magkuha gikan sa Munisipal nga Accounting Office sa sertipiko sa netong take-home pay alang sa bisan unsang katuyoan nga magamit nila.',
            'All claims shall be approved by the Punong Barangay (PB) and certified as to validity, propriety and legality of the claim by the Municipal Accountant. In case of claim chargeable against SK Fund, the SK Chairman shall initial under the name of the PB. All disbursements shall be covered with duly processed and approved DVs/payrolls. The BT shall be responsible for paying claims against the Barangay.' =>
                'Ang tanang claim aprobahan sa Punong Barangay (PB) ug i-certify ang pagka-valid, propriety, ug legalidad sa claim sa Munisipal nga Accountant. Kung ang claim mabayran gikan sa SK Fund, ang SK Chairman magbutang og inisyal sa ilawom sa pangalan sa PB. Ang tanang disbursements sakop sa mga pinroseso ug aprobadong DVs/payrolls. Ang BT responsable sa pagbayad sa mga claim batok sa Barangay.',
        ];

        return $translations[$description] ?? $description; // Fallback to original if no translation exists
    }

    // Define translations for the checklist_of_requirements in Filipino
    private function getFilipinoChecklist($checklist)
    {
        $checklist = json_decode($checklist);

        // Translate the checklist items into Filipino
        $translations = [
            '1. Disbursement vouchers, payrolls & supporting documents' => '1. Mga disbursement vouchers, payrolls ug mga sumusuportang dokumento',
            '2. Pre-numbered and pre-audited DVs and payrolls' => '2. Mga pre-numbered ug pre-audited nga DVs ug payrolls',
            '3. Duly filed up/dated/signed supporting documents' => '3. Mga dokumento nga sumusuporta nga gi-file, gi-update ug pirmahan',
            "4. Audited DV's with duly accomplished Obligation Request (OBR) by the MBO" => "4. Mga Audited DV's nga adunay husto nga gi-accomplish nga Obligation Request (OBR) sa MBO",
            '5. Audited & obligated DVs, payrolls and duly filled up/signed/dated supporting documents' => '5. Mga Audited ug obligated DVs, payrolls ug mga dokumento nga pirmahan ug naa sa hustong petsa',
        ];

        return json_encode(array_map(function ($item) use ($translations) {
            return $translations[$item] ?? $item; // Fallback to original if no translation exists
        }, $checklist));
    }

    // Define translations for the where_to_secure field in Filipino
    private function getFilipinoWhereToSecure($whereToSecure)
    {
        $locations = json_decode($whereToSecure);

        // Translate the locations into Filipino
        $translations = [
            'Accounting Office' => 'Accounting Office',  // No need for translation, as it remains the same
            'Barangay Hall' => 'Barangay Hall',  // You can modify this if necessary
        ];

        return json_encode(array_map(function ($location) use ($translations) {
            return $translations[$location] ?? $location; // Fallback to original if no translation exists
        }, $locations));
    }
}
