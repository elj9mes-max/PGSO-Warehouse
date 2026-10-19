<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offices = [
            // Page 1
            [
                'office_name' => 'Office Of The Governor',
                'office_code' => 'OG',
            ],
            [
                'office_name' => 'Office Of The Vice Governor',
                'office_code' => 'OVG',
            ],
            [
                'office_name' => 'Provincial Administrator',
                'office_code' => 'PA',
            ],
            [
                'office_name' => 'Office Of The Executive Assistant',
                'office_code' => 'OEA',
            ],
            [
                'office_name' => 'Provincial Legal Office',
                'office_code' => 'PLO',
            ],
            [
                'office_name' => 'Provincial Planning & Development Office',
                'office_code' => 'PPDO',
            ],
            [
                'office_name' => 'Provincial Disaster Risk Reduction and Management Office',
                'office_code' => 'PDRRMO',
            ],
            [
                'office_name' => 'Bids and Awards Committee',
                'office_code' => 'BAC',
            ],
            [
                'office_name' => 'Provincial Human Resource Management Office',
                'office_code' => 'PHRMO',
            ],
            [
                'office_name' => 'Provincial Youth Development Office',
                'office_code' => 'PYDO',
            ],
            [
                'office_name' => 'Provincial Tourism Office',
                'office_code' => 'PTourO',
            ],
            [
                'office_name' => 'Public Employment Service Office',
                'office_code' => 'PESO',
            ],
            [
                'office_name' => 'Provincial Social Welfare & Development Office',
                'office_code' => 'PSWDO',
            ],
            [
                'office_name' => 'Persons with Disability Affairs Office',
                'office_code' => 'PDAO',
            ],
            [
                'office_name' => 'Public Affairs Information Assistance Division',
                'office_code' => 'PAIAD',
            ],
            [
                'office_name' => 'Provincial Accounting Office',
                'office_code' => 'PACCO',
            ],
            [
                'office_name' => 'Office Of The Provincial Agriculturist',
                'office_code' => 'OPA',
            ],
            [
                'office_name' => 'Provincial Assessors Office',
                'office_code' => 'PASSO',
            ],
            [
                'office_name' => 'Provincial Budget Office',
                'office_code' => 'PBO',
            ],
            [
                'office_name' => 'Provincial Health Office',
                'office_code' => 'PHO',
            ],
            [
                'office_name' => 'Aurora Memorial Hospital',
                'office_code' => 'AMH',
            ],
            [
                'office_name' => 'Casiguran District Hospital',
                'office_code' => 'CDH',
            ],

            // Page 2
            [
                'office_name' => 'Dingalan Community Hospital',
                'office_code' => 'DCH',
            ],
            [
                'office_name' => 'Maria Aurora Community Hospital',
                'office_code' => 'MACH',
            ],
            [
                'office_name' => 'Provincial Treasury Office',
                'office_code' => 'PTO',
            ],
            [
                'office_name' => 'Provincial Veterinary Office',
                'office_code' => 'PVO',
            ],
            [
                'office_name' => 'Office of the Sangguniang Panlalawigan Secretariat',
                'office_code' => 'OSPS',
            ],
            [
                'office_name' => 'Provincial Nutrition Action Office',
                'office_code' => 'PNAO',
            ],
            [
                'office_name' => 'Occupational Health Clinic',
                'office_code' => 'OHC',
            ],
            [
                'office_name' => 'Provincial Government Employees Association',
                'office_code' => 'PGEA',
            ],
            [
                'office_name' => 'Provincial Equipment Pool Office',
                'office_code' => 'PEPO',
            ],
            [
                'office_name' => 'Provincial Internal Audit Services Office',
                'office_code' => 'PIASO',
            ],
            [
                'office_name' => 'Provincial Cooperatives Development Office',
                'office_code' => 'PCDO',
            ],
            [
                'office_name' => 'Provincial Engineering Office',
                'office_code' => 'PEO',
            ],
            [
                'office_name' => 'Environment and Natural Resources Office',
                'office_code' => 'ENRO',
            ],
            [
                'office_name' => 'Provincial General Services Office',
                'office_code' => 'PGSO',
            ],
            [
                'office_name' => 'Provincial Warden’s Division',
                'office_code' => 'PWD',
            ],
            [
                'office_name' => 'Provincial Local Economic Development and Investment Promotion Office',
                'office_code' => 'PLEDIPO',
            ],
            [
                'office_name' => 'Provincial Population Office',
                'office_code' => 'PPO',
            ],
            [
                'office_name' => 'Cultural Affairs Division',
                'office_code' => 'CAD',
            ],
            [
                'office_name' => 'Provincial Agricultural and Biosystems Engineering Division',
                'office_code' => 'PABED',
            ],
        ];

        foreach ($offices as $office) {
            Office::updateOrCreate(
                ['office_name' => $office['office_name']],
                ['office_code' => $office['office_code']]
            );
        }
    }
}
