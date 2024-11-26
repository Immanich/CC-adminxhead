<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Office;
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
        // $transactions = Transaction::where('type_of_transaction', 'type_of_transaction');
        $g2gTransaction = Transaction::where('type_of_transaction', 'G2G-Government to Government')->first();
        $g2cTransaction = Transaction::where('type_of_transaction', 
                                "G2C - Government Service to transacting public\n
                                G2B - Government Service to business entity\n
                                G2C - Government Service to government")->first();
        $accounting = Office::where('office_name', "Accounting Office")->first();
        $assessors = Office::where('id', 2)->first();

        Service::create([
            'service_name' => 'PROCESSING OF CLAIMS(MUNICIPAL TRANSACTIONS)',
            'description' => "To safeguard the use and disposition of the Municipal Government's assets and to determine its liabilities from claims, pre-audit is undertaken by the Municipal Accountant to determine that all necessary supporting documents of vouchers/claims are submitted.",
            'office_id' => $accounting->id,  // Accounting Office 
            'classification' => 'SIMPLE',
            'transaction_id' => $g2gTransaction->id,
            'checklist_of_requirements' => json_encode([
                '1. Disbursement vouchers, payrolls & supporting documents',
                '2. Pre-numbered and pre-audited DVs and payrolls',
                '3. Duly filed up/dated/signed support  ing documents',
                "4. Audited DV's with duly accomplished Obligation Request (OBR) by the MBO",
                '5. Audited & obligated DVs, payrolls and duly filled up/signed/dated supporting documents',
            ]),
            'where_to_secure' => json_encode(['Accounting Office'])
        ]);

        Service::create([
            'service_name' => 'ISSUANCE OF CERTIFICATE OF INCOME TAX WITHHELD FROM EMPLOYEES',
            'description' => "Government employees' income taxes are withheld pursuant to the National Internal Revenue Code. The Certificate of Compensation Payment/Tax withheld is annually given to show proof that tax due to employees had been paid.",
            'office_id' => $accounting->id,  // Accounting Office
            'classification' => 'SIMPLE',
            'transaction_id' => $g2gTransaction->id,
            'checklist_of_requirements' => json_encode([
                'None',
            ]),
            'where_to_secure' => json_encode(['Accounting Office'])
        ]);

        Service::create([
            'service_name' => 'ISSUANCE OF CERTIFICATE OF NET TAKE HOME PAY',
            'description' => "Employees shall secure from the Municipal Accounting Office the certificate of net take home pay for whatever purpose it may serve them.",
            'office_id' => $accounting->id,  // Accounting Office
            'classification' => 'SIMPLE',
            'transaction_id' => $g2gTransaction->id,
            'checklist_of_requirements' => json_encode([
                'None',
            ]),
            'where_to_secure' => json_encode(['Accounting Office']),
        ]);

        Service::create([
            'service_name' => 'PROCESSING OF CLAIMS (MUNICIPAL TRANSACTIONS)',
            'description' => "All claims shall be approved by the Punong Barangay (PB) and certified as to validity, propriety and legality of the cla im by the Municipal Accountant. In case of claim chargeable against SK Fund, the SK Chairman shall initial under the name of the PB. All disbursements shall be covered with duly processed and approved DVs/payrolls. The BT shall be responsible for paying claims against the Barangay.",
            'office_id' => $accounting->id,  // Accounting Office
            'classification' => 'SIMPLE',
            'transaction_id' => $g2gTransaction->id,
            'checklist_of_requirements' => json_encode([
                '1. Disbursement Vouchers with complete supporting documents.',
                '2. Transmittal Letter',
                '3. Punong Barangay Certification (Duplicate for the Municipal Accountant and Quadruplicate for COA SA)',
                "4. Personal appearance of the Barangay Treasurer",
            ]),
            'where_to_secure' => json_encode(['Accounting Office']),
        ]);

        Service::create([
            'service_name' => 'ISSUANCE OF CERTIFIED TRUE COPIES OF TAX DECLARATIONS',
            'description' => "To provide system-generated certified true copies to the transacting clients.",
            'office_id' => $assessors->id, 
            'classification' => 'SIMPLE',
            'transaction_id' => $g2gTransaction->id,
            'checklist_of_requirements' => json_encode([
                '1. Official receipt for the certification fee',
                '2. Real Property tax must be paid until the current year.',
                '3. Special Power of Attorney is required if the requesting party is not the tax declarant.',
            ]),
            'where_to_secure' => json_encode([
                '1. Municipal Treasurer’s Office',
                '2. Notary Public',
                '3. To be prepared by a Notary Public']),
        ]);

        Service::create([
            'service_name' => 'REQUEST FOR ISSUANCE OF TAX DECLARATIONS FOR NEW DISCOVERIES OF LAND',
            'description' => "The objective for the issuance of tax declaration for the newly discovered lands is to properly account all real properties within the municipality.",
            'office_id' => $assessors->id, 
            'classification' => 'SIMPLE',
            'transaction_id' => $g2gTransaction->id,
            'checklist_of_requirements' => json_encode([
                'FOR UNTITLED PROPERTY:',
                '1. Sketch Plan', 
                '2. A & D certification from DENR (original copy)',
                '3. Affidavit of ownership',
                '4. Affidavit of Adjoining Owners (all adjoining owners must sign in the affidavit)',
                '',
                'FOR TITLED PROPERTY:',
                '1. Sketch Plan',
                '2. Photo copy of the title authenticated by the 
                Municipal Assessor',
                '3. Document that support the ownership of the 
                title (if in case the document is insufficient 
                additional affidavit is required)',
                'FOR NEW DISCOVERIES OF LAND WITH ERRONEOUS SURVEY CLAIMANT(UNTITLED PROPERTY):',
                '1. Sketch Plan',
                '2. Certification from DENR as to A & D',
                '3. Affidavit of Ownership',
                '4. Affidavit of Adjoining owners',
                '5. Affidavit of waiver from the cadastral survey claimant',
                '6. Certification from the barangay captain',
                'NEW DISCOVERIES OF FISHPONDS WITH FLA:',
                '1. Approved Plans FLA/Sketch plan duly signed by Geodetic Engineer with certificate from DENR/DA/BFAR',
                '2. Letter request from applicant with proper endorsement from the Municipal Assessor (masso level) Note: It should be indicated in the declared owner portion of the FAAS and TD that the applicant is only a beneficial user-developer and not the declared owner.',
                'NEW DISCOVERIES OF FISHPONDS WITHOUT FLA:',
                '1. Sketch Map',
                '2.  Findings of the Municipal Assessor
                Note: It should be indicated in the declared owner portion of the FAAS and TD that the applicant is only a beneficial user-developer and not a declared owner.
                Note: All the documents submitted must be in two (2) copies',
            ]),
            'where_to_secure' => json_encode([
                '',
                '1. CENRO – DENR',
                '2. CENRO – DENR',
                '3. To be prepared by a Notary Public',
                '4. To be prepared by a Notary Public',
                '',
                '1. Municipal Assessor’s',
                '2. From the Owner',
                '3. From the Owner',
                '',
                '1. CENRO – DENR',
                '2. CENRO – DENR',
                '3. To be prepared by a Notary Public',
                '4. To be prepared by a Notary Public',
                '5. To be prepared by a Notary Public',
                '6. Barangay captain where the property is located',
                '',
                '1. CENRO – DENR',
                '2. From the applicant',
                '',
                '1. Geodetic Engineer',
                "2. Municipal Assessor's Office",
            ])
        ]);

        Service::create([
            'service_name' => 'REQUEST FOR ISSUANCE OF TAX DECLARATIONS FOR NEW BUILDING AND MACHINERY',
            'description' => "The objective for the issuance of tax declaration for the new building and machinery is to generate more revenues. .",
            'office_id' => $assessors->id, 
            'classification' => 'SIMPLE',
            'transaction_id' => $g2gTransaction->id,
            'checklist_of_requirements' => json_encode([
                'FOR NEW BUILDING:',
                '1. Actual measurement of the building or blue print copy of the building plan', 
                'FOR MACHINERY:',
                '2. Proof of Purchase with Official receipts or sworn statement of the owner as to prices, year acquired, installed and operated. ',
                'Note: All the documents submitted must be in two (2) copies ',
            ]),
            'where_to_secure' => json_encode([
                '',
                '1.  Actual Inspection by the Municipal Assessor’s Staff ',
                '',
                '2. From the Supplier or Owner '
            ])
        ]);

        Service::create([
            'service_name' => 'REQUEST FOR ISSUANCE OF TAX DECLARATIONS FOR TRANSFER OF OWNERSHIP OF UNTITLED PROPERTY ',
            'description' => "The issuance of tax declaration for transfer of ownership of untitled property is the updating of the ownership property index.",
            'office_id' => $assessors->id, 
            'classification' => 'COMPLEX',
            'transaction_id' => $g2gTransaction->id,
            'checklist_of_requirements' => json_encode([
                'A. THRU DEED OF SALE',
                '1. Tax clearance and/or current tax receipts (1 copy)', 
                '2. Deed of Sale duly registered with the Office of the Registry of Deeds (2 copies) ',
                '3. Request form signed by the owner or his/her representative duly endorsed by the Municipal Assessor to the Provincial Assessor  for approval if signed by a representative a special power of attorney is required – 2 copies',
                '4. Original owner’s copy of the tax declaration',
                '5. Assessor’s Fee',
                '6. Verification Fee',
                'B. THRU DEED OF DONATION',
                '1. Tax clearance and/or current tax receipts (1 copy)',
                '2. Deed of Donation duly registered with the Office of the Registry of Deeds (2 copies)',
                '3. Request form signed by the owner or his/her representative duly endorsed by the Municipal Assessor to the Provincial Assessor  for approval if signed by a representative a special power of attorney is required – 2 copies',
                '4. Original owner’s copy of the tax declaration',
                '5. Assessor’s Fee',
                '6. Verification Fee',
                'C. THRU DEED OF EXCHANGE',
                '1. Tax clearance and/or current tax receipts (1 copy)',
                '2. Deed of Exchange duly registered with the Office of the Registry of Deeds (2 copies)',
                '3. Request form signed by the owner or his/her representative duly endorsed by the Municipal Assessor to the Provincial Assessor  for approval if signed by a representative a special power of attorney is required – 2 copies',
                '4. Original owner’s copy of the tax declaration subject to exchange ',
                '5. Assessor’s Fee',
                '6. Verification Fee',
                'D. THRU EXTRAJUDICIAL SETTLEMENT',
                '1. Tax clearance and/or current tax receipts (1 copy)',
                '2. Extrajudicial Settlement of Estate duly registered with the Office of the Registry of Deeds (ROD) (2 copies)',
                '3. Request form signed by the owner or his/her representative duly endorsed by the Municipal Assessor to the Provincial Assessor  for approval if signed by a representative a special power of attorney is required – 2 copies',
                '4. Original owner’s copy of the tax declaration',
                '5. Assessor’s Fee',
                '6. Verification Fee',
                'E. THRU COURT ORDER',
                '1. Tax clearance and/or current tax receipts (1 copy)',
                '2. Court Decision/Order duly registered with the Office of the Registry of Deeds - (2 copies)',
                '3. Request form signed by the owner or his/her representative duly endorsed by the Municipal Assessor to the Provincial Assessor  for approval if signed by a representative a special power of attorney is required – 2 copies',
                '4. Original owner’s copy of the tax declaration',
                '5. Finality of Judgment duly registered in the Registry of Deeds (ROD) – 2 copies',
                '6. Writ of Execution duly registered in the Registry of Deeds (ROD) – 2 copies',
                '7. Assessor’s Fee ',
                '8. Verification Fee',
                'F. THRU BANK FORECLOSURE',
                '1. Tax clearance and/or current tax receipts (1 copy)',
                '2. Deed of Foreclosure/Consolidation of Ownership duly registered with the Office of the Registry of Deeds (ROD) - 2 copies',
                '3. Request form signed by the owner or his/her representative duly endorsed by the Municipal Assessor to the Provincial Assessor  for approval if signed by a representative a special power of attorney is required – 2 copies',
                '4. Original owner’s copy of the tax declaration',
                '5. Final Deed of Sale (if any) duly registered in the Registry of Deeds (ROD) – 2 copies',
                '6. Assessor’s Fee ',
                '7. Verification Fee',
            ]),
            'where_to_secure' => json_encode([
                '',
                '1. Municipal Treasurer’s Office',
                '2. From the Owner',
                '3. Municipal Assessor’s Office',
                '4. From the Owner',
                '5. Municipal Treasurer’s Office',
                '6. Municipal Treasurer’s Office',
                '',
                '1. Municipal Treasurer’s Office',
                '2. From the Owner',
                '3. Municipal Assessor’s Office',
                '4. From the owner',
                '5. Municipal Treasurer’s Office ',
                '6. Provincial Treasurer’s Office ',
                '',
                '1. Municipal Treasurer’s Office',
                '2. From the Owner',
                '3. Municipal Assessor’s Office',
                '4. From the owner',
                '5. Municipal Treasurer’s Office',
                '6. Provincial Treasurer’s Office',
                '',
                '1. Municipal Treasurer’s Office',
                '2. From the Owner',
                '3. Municipal Assessor’s Office',
                '4. From the owner',
                '5. Municipal Treasurer’s Office',
                '6. Provincial Treasurer’s Office',
                '',
                '1. Municipal Treasurer’s Office',
                '2. From the Owner',
                '3. Municipal Assessor’s Office',
                '4. From the owner',
                '5. From the owner',
                '6. From the owner',
                '7. Municipal Treasurer’s Office',
                '8. Provincial Treasurer’s Office',
                '',
                '1. Municipal Treasurer’s Office',
                '2. From the Owner',
                '3. Municipal Assessor’s Office',
                '4. From the owner',
                '5. Municipal Treasurer’s Office',
                '6. Provincial Treasurer’s Office',
                '',
                '1. Municipal Treasurer’s Office',
                '2. From the banking institution',
                '3. Municipal Assessor’s Office',
                '4. From the owner',
                '5. From the banking institution',
                '6. Municipal Treasurer’s Office',
                '7. Provincial Treasurer’s Office',
            ])
        ]);

        Service::create([
            'service_name' => 'REQUEST FOR ISSUANCE OF TAX DECLARATIONS FOR TRANSFER OF OWNERSHIP OF TITLED PROPERTY',
            'description' => "",
            'office_id' => $assessors->id, 
            'classification' => 'COMPLEX',
            'transaction_id' => $g2gTransaction->id,
            'checklist_of_requirements' => json_encode([
                'A. THRU DEED OF SALE',
                '1. Tax clearance and/or current tax receipts (1 copy)', 
                '2. Deed of Sale duly registered with the Office of the Registry of Deeds (2 copies) ',
                '3. Request form signed by the owner or his/her representative duly endorsed by the Municipal Assessor to the Provincial Assessor  for approval if signed by a representative a special power of attorney is required – 2 copies',
                '4. Original owner’s copy of the tax declaration',
                '5. Authenticated copy of the title – 2 copies ',
                '6. Assessor’s Fee',
                '7. Verification Fee',
                'B. THRU DEED OF DONATION',
                '1. Tax clearance and/or current tax receipts (1 copy)',
                '2. Deed of Donation duly registered with the Office of the Registry of Deeds (2 copies)',
                '3. Request form signed by the owner or his/her representative duly endorsed by the Municipal Assessor to the Provincial Assessor  for approval if signed by a representative a special power of attorney is required – 2 copies',
                '4. Original owner’s copy of the tax declaration',
                '5. Authenticated copy of the title – 2 copies',
                '6. Assessor’s Fee',
                '7. Verification Fee',
                'C. THRU DEED OF EXCHANGE',
                '1. Tax clearance and/or current tax receipts (1 copy)',
                '2. Deed of Exchange duly registered with the Office of the Registry of Deeds - (2 copies)',
                '3. Request form signed by the owner or his/her representative duly endorsed by the Municipal Assessor to the Provincial Assessor  for approval if signed by a representative a special power of attorney is required – 2 copies',
                '4. Original owner’s copy of the tax declaration subject to exchange ',
                '5. Authenticated copies of the titles subject to exchange – 2 copies',
                '6. Assessor’s Fee',
                '7. Verification Fee',
                'D. THRU EXTRAJUDICIAL SETTLEMENT',
                '1. Tax clearance and/or current tax receipts (1 copy)',
                '2. Extrajudicial Settlement of Estate duly registered with the Office of the Registry of Deeds (ROD) (2 copies)',
                '3. Request form signed by the owner or his/her representative duly endorsed by the Municipal Assessor to the Provincial Assessor  for approval if signed by a representative a special power of attorney is required – 2 copies',
                '4. Original owner’s copy of the tax declaration',
                '5. Authenticated copy of the title – 2 copies',
                '6. Assessor’s Fee',
                '7. Verification Fee',
                'E. THRU COURT ORDER',
                '1. Tax clearance and/or current tax receipts (1 copy)',
                '2. Court Decision/Order duly registered with the Office of the Registry of Deeds - (2 copies)',
                '3. Request form signed by the owner or his/her representative duly endorsed by the Municipal Assessor to the Provincial Assessor  for approval if signed by a representative a special power of attorney is required – 2 copies',
                '4. Original owner’s copy of the tax declaration',
                '5. Finality of Judgment duly registered in the Registry of Deeds (ROD) – 2 copies',
                '6. Writ of Execution duly registered in the Registry of Deeds (ROD) – 2 copies',
                '7. Authenticated copy of the title – 2 copies',
                '8. Assessor’s Fee ',
                '9. Verification Fee',
                'F. THRU BANK FORECLOSURE',
                '1. Tax clearance and/or current tax receipts (1 copy)',
                '2. Deed of Foreclosure/Consolidation of Ownership duly registered with the Office of the Registry of Deeds (ROD) - 2 copies',
                '3. Request form signed by the owner or his/her representative duly endorsed by the Municipal Assessor to the Provincial Assessor  for approval if signed by a representative a special power of attorney is required – 2 copies',
                '4. Original owner’s copy of the tax declaration',
                '5. Final Deed of Sale (if any) duly registered in the Registry of Deeds (ROD) – 2 copies',
                '6. Authenticated copy of the title – 2 copies',
                '7. Assessor’s Fee ',
                '8. Verification Fee',
            ]),
            'where_to_secure' => json_encode([
                '',
                '1. Municipal Treasurer’s Office',
                '2. From the Owner',
                '3. Municipal Assessor’s Office',
                '4. From the Owner',
                '5. Registry of Deeds Tagbilaran City',
                '6. Municipal Treasurer’s Office',
                '7. Provincial Treasurer’s Office',
                '',
                '1. Municipal Treasurer’s Office',
                '2. Deed of Donation',
                '3. Municipal Assessor’s Office',
                '4. From the owner',
                '5. Registry of Deeds Tagbilaran City',
                '6. Municipal Treasurer’s Office',
                '7. Provincial Treasurer’s Office ',
                '',
                '1. Municipal Treasurer’s Office',
                '2. From the Owner',
                '3. Municipal Assessor’s Office',
                '4. From the owner',
                '5. Registry of Deeds Tagbilaran City',
                '6. Municipal Treasurer’s Office',
                '7. Provincial Treasurer’s Office',
                '',
                '1. Municipal Treasurer’s Office',
                '2. From the Owner',
                '3. Municipal Assessor’s Office',
                '4. From the owner',
                '5. Registry of Deeds Tagbilaran City',
                '6. Municipal Treasurer’s Office',
                '7. Provincial Treasurer’s Office',
                '',
                '1. Municipal Treasurer’s Office',
                '2. From the Owner',
                '3. Municipal Assessor’s Office',
                '4. From the owner',
                '5. From the owner',
                '6. From the owner',
                '7. Registry of Deeds Tagbilaran City',
                '8. Municipal Treasurer’s Office',
                '9. Provincial Treasurer’s Office',
                '',
                '1. Municipal Treasurer’s Office',
                '2. From the banking institution',
                '3. Municipal Assessor’s Office',
                '4. From the owner',
                '5. From the banking institution',
                '6. Registry of Deeds Tagbilaran City',
                '7. Municipal Treasurer’s Office',
                '8. Provincial Treasurer’s Office',
            ])
        ]);

        Service::create([
            'service_name' => 'REQUEST FOR THE REVISION OF OLD TAX DECLARATION',
            'description' => "",
            'office_id' => $assessors->id, 
            'classification' => 'SIMPLE',
            'transaction_id' => $g2gTransaction->id,
            'checklist_of_requirements' => json_encode([
                '1. Tax clearance and/or current tax receipts - 1 copy', 
                '2. Request form signed by the owner or his/her representative duly endorsed by the Municipal Assessor to the Provincial Assessor  for approval if signed by a representative a special power of attorney is required – 2 copies',
                '3. Original owner’s copy of the old tax declaration',
                '4. Assessor’s fee',
                '5. Verification Fee',
            ]),
            'where_to_secure' => json_encode([
                '1. Municipal Treasurer’s Office',
                '2. Municipal Assessor’s Office',
                '3. From the Owner',
                '4. Municipal Treasurer’s Office',
                '5. Provincial Treasurer’s Office',
            ])
        ]);

        Service::create([
            'service_name' => 'REQUEST FOR THE ISSUANCE OF CERTIFICATION OF LANDHOLDING/NO LANDHOLDINGS',
            'description' => "These two certifications are needed in the computation of estate tax and to determine the total aggregate landholding of the property owner. ",
            'office_id' => $assessors->id, 
            'classification' => 'SIMPLE',
            'transaction_id' => $g2gTransaction->id,
            'checklist_of_requirements' => json_encode([
                '1. Official receipt for the assessor’s  fee', 
            ]),
            'where_to_secure' => json_encode([
                '1. Municipal Treasurer’s Office',
            ])
        ]);

        Service::create([
            'service_name' => 'REQUEST FOR THE ISSUANCE OF SKETCH PLAN PER APPROVED CADASTRAL SURVEY OR VICINITY MAP',
            'description' => "",
            'office_id' => $assessors->id, 
            'classification' => 'SIMPLE',
            'transaction_id' => $g2gTransaction->id,
            'checklist_of_requirements' => json_encode([
                '1. Official receipt for the assessor’s  fee', 
            ]),
            'where_to_secure' => json_encode([
                '1. Municipal Treasurer’s Office',
            ])
        ]);

        Service::create([
            'service_name' => 'REQUEST FOR THE ISSUANCE OF CERTIFICATION OF IMPROVEMENT/NO IMPROVEMENT',
            'description' => "",
            'office_id' => $assessors->id, 
            'classification' => 'SIMPLE',
            'transaction_id' => $g2cTransaction->id,
            'checklist_of_requirements' => json_encode([
                '1. Official receipt for the assessor’s fee', 
            ]),
            'where_to_secure' => json_encode([
                '1. Municipal Treasurer’s Office',
            ])
        ]);

        Service::create([
            'service_name' => 'REQUEST FOR CANCELLATION OF TAX DECLARATION BECAUSE IT IS NO LONGER EXISTING AND DUE TO DESTRUCTION',
            'description' => "",
            'office_id' => $assessors->id, 
            'classification' => 'SIMPLE',
            'transaction_id' => $g2cTransaction->id,
            'checklist_of_requirements' => json_encode([
                '1. Request form duly signed by the owner or his/her representative duly endorsed by the Municipal Assessor to the Provincial Assessor for approval – copies (if signed by a representative a special power of attorney is required)', 
                '2. Tax Declaration subject for cancellation',
                '3. Current land tax official receipt',
                '4. Ocular inspection of the property subject for cancellation',
                "5. Assessor's fee",
                "6. Verification fee",
            ]),
            'where_to_secure' => json_encode([
                '1. Municipal Treasurer’s Office',
                '2. From the owner',
                '3. Municipal Treasurer’s Office',
                '4. Municipal Assessor’s Office Staff',
                '5. Municipal Treasurer’s Office',
                '6. Provincial Treasurer’s Office',
            ])
        ]);

        Service::create([
            'service_name' => 'REQUEST FOR CANCELLATION OF TAX DECLARATION DUE TO COURT DECISION',
            'description' => "",
            'office_id' => $assessors->id, 
            'classification' => 'SIMPLE',
            'transaction_id' => $g2cTransaction->id,
            'checklist_of_requirements' => json_encode([
                '1. Request form duly signed by the owner or his/her representative duly endorsed by the Municipal Assessor to the Provincial Assessor for approval – copies (if signed by a representative a special power of attorney is required)', 
                '2. Tax Declaration subject for cancellation',
                '3. Current land tax official receipt paid at the Municipal Treasurer’s Office ',
                '4. Write of execution duly registered from ROD (2 copies)',
                "5. Finality of Judgment duly registered from ROD (2 copies)",
                "6. Court Decision duly registered from ROD (2 copies)",
                "7. Assessor’s Fee",
                "8. Verification Fee",
            ]),
            'where_to_secure' => json_encode([
                '1. Municipal Assessor’s Office',
                '2. From the owner',
                '3. Municipal Treasurer’s Office',
                '4. From the owner',
                '5. From the owner',
                '6. From the owner',
                '7. Municipal Treasurer’s Office',
                '8. Provincial Treasurer’s Office',
            ])
        ]);

        Service::create([
            'service_name' => 'REQUEST FOR CANCELLATION OF TAX DECLARATION DUE TO DUPLICATIONS',
            'description' => "",
            'office_id' => $assessors->id, 
            'classification' => 'SIMPLE',
            'transaction_id' => $g2cTransaction->id,
            'checklist_of_requirements' => json_encode([
                "Same Declared Owner:\n1. Request form duly signed by the owner or his/her representative duly endorsed by the Municipal Assessor to the Provincial Assessor for approval – copies (if signed by a representative a special power of attorney is required)", 
                '2. Ocular inspection of the property subject for cancellation',
                '3. Tax Declaration subject for cancellation',
                '4. Current land tax official receipt',
                "5. Assessor’s Fee",
                "6. Verification Fee",
                "Different Owners:\n1. Request form duly signed by the owner or his/her representative duly endorsed by the Municipal Assessor to the Provincial Assessor for approval – copies (if signed by a representative a special power of attorney is required)",
                '2. Ocular inspection of the property subject for cancellation',
                '3. Tax Declaration subject for cancellation',
                '4. Current land tax official receipt',
                '5. Affidavit of Waiver',
                "6. Assessor’s Fee",
                "7. Verification Fee",
                "Subdivided lot with tax declaration but the mother lot is not cancelled:\n1. Request form duly signed by the owner or his/her representative duly endorsed by the Municipal Assessor to the Provincial Assessor for approval – copies (if signed by a representative a special power of attorney is required)",
                '2. Ocular inspection of the property subject for cancellation',
                '3. Tax Declaration subject for cancellation',
                "4. Assessor’s Fee",
                "5. Verification Fee",
            ]),
            'where_to_secure' => json_encode([
                "\n1. Municipal Assessor’s Office",
                '2. Municipal Assessor’s Staff',
                '3. From the owner',
                '4. Municipal Treasurer’s Office',
                '5. Municipal Treasurer’s Office',
                '6. Provincial Treasurer’s Office',
                "\n1. Municipal Assessor’s Office",
                '2. Municipal Assessor’s Staff',
                '3. From the owner',
                '4. Municipal Treasurer’s Office',
                '5. To be prepared by a Notary Public',
                '6. Municipal Treasurer’s Office',
                '7. Provincial Treasurer’s Office',
            ])
        ]);

        Service::create([
            'service_name' => 'REQUEST FOR SUBDIVISION/CONSOLIDATION OF TITLED PROPERTIES',
            'description' => "",
            'office_id' => $assessors->id, 
            'classification' => 'SIMPLE',
            'transaction_id' => $g2cTransaction->id,
            'checklist_of_requirements' => json_encode([
                "1. Request form duly signed by the owner or his/her representative duly endorsed by the Municipal Assessor to the Provincial Assessor for approval if signed by a representative a special power of attorney is required - 2 copies", 
                '2. Approved subdivision plan (2 copies)',
                '3. Deed of Conveyance duly registered at the Registry of Deeds (2 copies)',
                '4. Authenticated copy of the title – 2 copies',
                "5. Tax Declaration of the mother lot",
                "6. Current land tax receipt",
                "7. Assessor’s Fee",
                "8. Verification Fee",
            ]),
            'where_to_secure' => json_encode([
                "1. Municipal Assessor’s Office",
                '2. From the owner',
                '3. From the owner',
                '4. Registry of Deeds Tagbilaran City',
                '5. From the owner',
                '6. Municipal Treasurer’s Office',
                '7. Municipal Treasurer’s Office',
                '8. Provincial Treasurer’s Office',
            ])
        ]);

        Service::create([
            'service_name' => 'REQUEST FOR SUBDIVISION/CONSOLIDATION OF UNTITLED PROPERTIES',
            'description' => "",
            'office_id' => $assessors->id, 
            'classification' => 'SIMPLE',
            'transaction_id' => $g2cTransaction->id,
            'checklist_of_requirements' => json_encode([
                "1. Request form duly signed by the owner or his/her representative duly endorsed by the Municipal Assessor to the Provincial Assessor for approval if signed by a representative a special power of attorney is required - 2 copies", 
                '2. Approved subdivision plan (2 copies)',
                '3. Deed of Conveyance duly registered at the Registry of Deeds (2 copies)',
                "4. Tax Declaration of the mother lot",
                "5. Current land tax receipt",
                "6. Assessor’s Fee",
                "7. Verification Fee",
            ]),
            'where_to_secure' => json_encode([
                "1. Municipal Assessor’s Office",
                '2. From the owner',
                '3. From the owner',
                '4. From the owner',
                '5. Municipal Treasurer’s Office',
                '6. Municipal Treasurer’s Office',
                '7. Provincial Treasurer’s Office',
            ])
        ]);

        Service::create([
            'service_name' => 'REQUEST FOR SUBDIVISION/CONSOLIDATION OF PROPERTY UNDER CARP/OLT/CLOA',
            'description' => "",
            'office_id' => $assessors->id, 
            'classification' => 'SIMPLE',
            'transaction_id' => $g2cTransaction->id,
            'checklist_of_requirements' => json_encode([
                "1. Request form duly signed by the owner or his/her representative duly endorsed by the Municipal Assessor to the Provincial Assessor for approval if signed by a representative a special power of attorney is required - 2 copies", 
                '2. Approved subdivision plan (2 copies)',
                '3. Deed of Conveyance duly registered at the Registry of Deeds (2 copies)',
                "4. Authenticated copy of the title – 2 copies",
                "5. Tax Declaration of the mother lot",
                "6. Current land tax receipt",
                "7. Assessor’s Fee",
                "8. Verification Fee",
            ]),
            'where_to_secure' => json_encode([
                "1. Municipal Assessor’s Office",
                '2. From the owner',
                '3. From the owner',
                '4. Registry of Deeds Tagbilaran City',
                '5. From the owner',
                '6. Municipal Treasurer’s Office',
                '7. Municipal Treasurer’s Office',
                '8. Provincial Treasurer’s Office',
            ])
        ]);

        Service::create([
            'service_name' => 'REQUEST FOR SUBDIVISION/CONSOLIDATION OF PROPERTY WITH P468 (WATERSHED/RESERVE AREA)',
            'description' => "",
            'office_id' => $assessors->id, 
            'classification' => 'SIMPLE',
            'transaction_id' => $g2cTransaction->id,
            'checklist_of_requirements' => json_encode([
                "1. Request form duly signed by the owner or his/her representative duly endorsed by the Municipal Assessor to the Provincial Assessor for approval if signed by a representative a special power of attorney is required - 2 copies", 
                '2. Approved subdivision plan (2 copies)',
                '3. Deed of Conveyance duly registered at the Registry of Deeds (2 copies)',
                "4. Certification from CENRO, DENR as to A & D but within the watershed and reserve area",
                "5. Tax Declaration of the mother lot",
                "6. Current land tax receipt",
                "7. Assessor’s Fee",
                "8. Verification Fee",
            ]),
            'where_to_secure' => json_encode([
                "1. Municipal Assessor’s Office",
                '2. From the owner',
                '3. From the owner',
                '4. From the owner',
                '5. From the owner',
                '6. Municipal Treasurer’s Office',
                '7. Municipal Treasurer’s Office',
                '8. Provincial Treasurer’s Office',
            ])
        ]);
    }
}


        


// namespace Database\Seeders;

// use App\Models\Service;
// use App\Models\Transaction;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use Illuminate\Database\Seeder;

// class ServiceSeeder extends Seeder
// {
//     /**
//      * Run the database seeds.
//      */
//     public function run(): void
//     {
//         $transaction = Transaction::where('type_of_transaction', 'G2G-Government to Government')->first();
        
//         $services = [
//             [
//                 'service_name' => 'PROCESSING OF CLAIMS (MUNICIPAL TRANSACTIONS)',
//                 'description' => "To safeguard the use and disposition of the Municipal Government's assets and to determine its liabilities from claims, pre-audit is undertaken by the Municipal Accountant to determine that all necessary supporting documents of vouchers/ claims are submitted.",
//                 'office_id' => 1,
//                 'classification' => 'SIMPLE',
//                 'transaction_id' => $transaction->id,
//                 'checklist_of_requirements' =>  json_encode([
//                     '1. Disbursement vouchers, payrolls & supporting documents',
//                     '2. Pre-numbered and pre-audited DVs and payrolls',
//                     '3. Duly filed up/dated/signed supporting documents',
//                     "4. Audited DV's with duly accomplished Obligation Request (OBR) by the MBO",
//                     '5. Audited & obligated DVs, payrolls and duly filled up/signed/dated supporting documents'
//                 ]),
//                 'where_to_secure' => 'ACCOUNTING OFFICE'
//             ],
//             [
//                 'service_name' => 'PROCESSING OF CLAIMS (MUNICIPAL TRANSACTIONS)',
//                 'description' => "To safeguard the use and disposition of the Municipal Government's assets and to determine its liabilities from claims, pre-audit is undertaken by the Municipal Accountant to determine that all necessary supporting documents of vouchers/ claims are submitted.",
//                 'office_id' => 1,
//                 'classification' => 'SIMPLE',
//                 'transaction_id' => $transaction->id,
//                 'checklist_of_requirements' =>  json_encode([
//                     '1. Disbursement vouchers, payrolls & supporting documents',
//                     '2. Pre-numbered and pre-audited DVs and payrolls',
//                     '3. Duly filed up/dated/signed supporting documents',
//                     "4. Audited DV's with duly accomplished Obligation Request (OBR) by the MBO",
//                     '5. Audited & obligated DVs, payrolls and duly filled up/signed/dated supporting documents'
//                 ]),
//                 'where_to_secure' => 'ACCOUNTING OFFICE'
//             ],
//         ];

//         foreach($services as $service) {
//             Service::create($service);
//         }
//     }
// }
