<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesignationSeeder extends Seeder
{
    public function run()
    {
        $designations = [
            ['title' => 'Inspector General', 'short_form' => 'IG', 'bps' => 22, 'rank_group' => 'Inspector General', 'cadre_type' => 'uniform'],
            ['title' => 'Inspector General', 'short_form' => 'IG', 'bps' => 21, 'rank_group' => 'Inspector General', 'cadre_type' => 'uniform'],
            ['title' => 'Additional Inspector General', 'short_form' => 'Addl IG', 'bps' => 21, 'rank_group' => 'Additional Inspector General', 'cadre_type' => 'uniform'],
            ['title' => 'Deputy Inspector General', 'short_form' => 'DIG', 'bps' => 20, 'rank_group' => 'Deputy Inspector General', 'cadre_type' => 'uniform'],
            ['title' => 'Assistant Inspector General', 'short_form' => 'AIG', 'bps' => 19, 'rank_group' => 'Senior Superintendent of Police', 'cadre_type' => 'uniform'],
            ['title' => 'Assistant Inspector General', 'short_form' => 'AIG', 'bps' => 18, 'rank_group' => 'Superintendent of Police', 'cadre_type' => 'uniform'],
            ['title' => 'Senior Superintendent of Police', 'short_form' => 'SSP', 'bps' => 19, 'rank_group' => 'Senior Superintendent of Police', 'cadre_type' => 'uniform'],
            ['title' => 'Superintendent of Police', 'short_form' => 'SP', 'bps' => 18, 'rank_group' => 'Superintendent of Police', 'cadre_type' => 'uniform'],
            ['title' => 'Acting Superintendent of Police', 'short_form' => 'Acting SP', 'bps' => 17, 'rank_group' => 'Acting Superintendent of Police', 'cadre_type' => 'uniform'],
            ['title' => 'Chief Patrol Officer', 'short_form' => 'DSP/CPO', 'bps' => 17, 'rank_group' => 'Deputy Superintendent of Police', 'cadre_type' => 'uniform'],
            ['title' => 'Chief Patrol Officer', 'short_form' => 'ASP/CPO', 'bps' => 17, 'rank_group' => 'Assistant Superintendent of Police', 'cadre_type' => 'uniform'],
            ['title' => 'Acting Chief Patrol Officer', 'short_form' => 'A/CPO', 'bps' => 16, 'rank_group' => 'Inspector', 'cadre_type' => 'uniform'],
            ['title' => 'Senior Patrol Officer', 'short_form' => 'IP/SPO', 'bps' => 16, 'rank_group' => 'Inspector', 'cadre_type' => 'uniform'],
            ['title' => 'Patrol Officer', 'short_form' => 'SI/PO', 'bps' => 14, 'rank_group' => 'Sub Inspector', 'cadre_type' => 'uniform'],
            ['title' => 'Assistant Patrol Officer', 'short_form' => 'APO', 'bps' => 11, 'rank_group' => 'Assitant Sub Inspector', 'cadre_type' => 'uniform'],
            ['title' => 'Assistant Patrol Officer', 'short_form' => 'HC/APO', 'bps' => 7, 'rank_group' => 'Head Contable', 'cadre_type' => 'uniform'],
            ['title' => 'Junior Patrol Officer', 'short_form' => 'C/JPO', 'bps' => 5, 'rank_group' => 'Constable', 'cadre_type' => 'uniform'],
            ['title' => 'Director', 'short_form' => 'Director', 'bps' => 19, 'rank_group' => 'Director', 'cadre_type' => 'non_uniform'],
            ['title' => 'Incharge Medical Officer', 'short_form' => 'IMO', 'bps' => 19, 'rank_group' => 'Incharge Medical Officer', 'cadre_type' => 'non_uniform'],
            ['title' => 'Doctor', 'short_form' => 'Dr', 'bps' => 18, 'rank_group' => 'Doctor', 'cadre_type' => 'non_uniform'],
            ['title' => 'Deputy Director', 'short_form' => 'DD', 'bps' => 18, 'rank_group' => 'Deputy Director', 'cadre_type' => 'non_uniform'],
            ['title' => 'Assistant Director', 'short_form' => 'AD', 'bps' => 17, 'rank_group' => 'Assistant Director', 'cadre_type' => 'non_uniform'],
            ['title' => 'Accounts Officer', 'short_form' => 'AO', 'bps' => 17, 'rank_group' => 'Accounts Officer', 'cadre_type' => 'non_uniform'],
            ['title' => 'Private Secretary', 'short_form' => 'PS', 'bps' => 18, 'rank_group' => 'Private Secretary', 'cadre_type' => 'non_uniform'],
            ['title' => 'Private Secretary', 'short_form' => 'PS', 'bps' => 17, 'rank_group' => 'Private Secretary', 'cadre_type' => 'non_uniform'],
            ['title' => 'Computer Programmer', 'short_form' => 'CP', 'bps' => 17, 'rank_group' => 'Computer Programmer', 'cadre_type' => 'non_uniform'],
            ['title' => 'Public Relation Officer', 'short_form' => 'PRO', 'bps' => 17, 'rank_group' => 'Public Relation Officer', 'cadre_type' => 'non_uniform'],
            ['title' => 'Accountant', 'short_form' => 'ACCT', 'bps' => 16, 'rank_group' => 'Accountant', 'cadre_type' => 'non_uniform'],
            ['title' => 'Office Superintendent', 'short_form' => 'OS', 'bps' => 16, 'rank_group' => 'Office Superintendent', 'cadre_type' => 'non_uniform'],
            ['title' => 'Land Acquiring Officer', 'short_form' => 'LAO', 'bps' => 16, 'rank_group' => 'Land Acquiring Officer', 'cadre_type' => 'non_uniform'],
            ['title' => 'Asstt. Computer Programmer', 'short_form' => 'ACP', 'bps' => 16, 'rank_group' => 'Computer Operator', 'cadre_type' => 'non_uniform'],
            ['title' => 'Assistant Private Secretary', 'short_form' => 'APS', 'bps' => 16, 'rank_group' => 'Assistant Private Secretary', 'cadre_type' => 'non_uniform'],
            ['title' => 'Stenographer', 'short_form' => 'Steno Gr', 'bps' => 16, 'rank_group' => 'Stenographer', 'cadre_type' => 'non_uniform'],
            ['title' => 'Assistant', 'short_form' => 'Assit', 'bps' => 14, 'rank_group' => 'Assistant', 'cadre_type' => 'non_uniform'],
            ['title' => 'Stenotypist', 'short_form' => 'StenoTy', 'bps' => 12, 'rank_group' => 'Stenotypist', 'cadre_type' => 'non_uniform'],
            ['title' => 'Upper Division Clerk', 'short_form' => 'UDC', 'bps' => 12, 'rank_group' => 'Upper Division Clerk', 'cadre_type' => 'non_uniform'],
            ['title' => 'Field Assistant', 'short_form' => 'F Assit', 'bps' => 11, 'rank_group' => 'Field Assistant', 'cadre_type' => 'non_uniform'],
            ['title' => 'Photographer', 'short_form' => 'PG', 'bps' => 10, 'rank_group' => 'Photographer', 'cadre_type' => 'non_uniform'],
            ['title' => 'Draftsman', 'short_form' => 'DFTMN', 'bps' => 10, 'rank_group' => 'Draftsman', 'cadre_type' => 'non_uniform'],
            ['title' => 'Lower Division Clerk', 'short_form' => 'LDC', 'bps' => 9, 'rank_group' => 'Lower Division Clerk', 'cadre_type' => 'non_uniform'],
            ['title' => 'Electrician Supervisor', 'short_form' => 'Elec Super', 'bps' => 7, 'rank_group' => 'Electrician Supervisor', 'cadre_type' => 'non_uniform'],
            ['title' => 'Mechanical Supervisor', 'short_form' => 'Mech Super', 'bps' => 7, 'rank_group' => 'Mechanical Supervisor', 'cadre_type' => 'non_uniform'],
            ['title' => 'Plumber Supervisor', 'short_form' => 'Plum Super', 'bps' => 7, 'rank_group' => 'Plumber Supervisor', 'cadre_type' => 'non_uniform'],
            ['title' => 'Para Medical Staff', 'short_form' => 'PMS', 'bps' => 7, 'rank_group' => 'Para Medical Staff', 'cadre_type' => 'non_uniform'],
            ['title' => 'Driver', 'short_form' => 'DRV', 'bps' => 7, 'rank_group' => 'Driver', 'cadre_type' => 'non_uniform'],
            ['title' => 'Tailor', 'short_form' => 'Tailor', 'bps' => 7, 'rank_group' => 'Tailor', 'cadre_type' => 'non_uniform'],
            ['title' => 'Khatib', 'short_form' => 'Khatib', 'bps' => 7, 'rank_group' => 'Khatib', 'cadre_type' => 'non_uniform'],
            ['title' => 'Auto Mechanic', 'short_form' => 'Auto Mech', 'bps' => 5, 'rank_group' => 'Auto Mechanic', 'cadre_type' => 'non_uniform'],
            ['title' => 'Electrician', 'short_form' => 'Elec', 'bps' => 5, 'rank_group' => 'Electrician', 'cadre_type' => 'non_uniform'],
            ['title' => 'Plumber', 'short_form' => 'Plum', 'bps' => 5, 'rank_group' => 'Plumber', 'cadre_type' => 'non_uniform'],
            ['title' => 'Dispatch Rider', 'short_form' => 'Disp R', 'bps' => 5, 'rank_group' => 'Dispatch Rider', 'cadre_type' => 'non_uniform'],
            ['title' => 'Mess Staff', 'short_form' => 'Mess Staff', 'bps' => 5, 'rank_group' => 'Mess Staff', 'cadre_type' => 'non_uniform'],
            ['title' => 'Orderly', 'short_form' => 'Orderly', 'bps' => 5, 'rank_group' => 'Orderly', 'cadre_type' => 'non_uniform'],
            ['title' => 'Helper', 'short_form' => 'Hlpr', 'bps' => 5, 'rank_group' => 'Helper', 'cadre_type' => 'non_uniform'],
            ['title' => 'Store Helper', 'short_form' => 'Store Hlpr', 'bps' => 2, 'rank_group' => 'Store Helper', 'cadre_type' => 'non_uniform'],
            ['title' => 'Mali', 'short_form' => 'Mali', 'bps' => 2, 'rank_group' => 'Mali', 'cadre_type' => 'non_uniform'],
            ['title' => 'Cook', 'short_form' => 'Cook', 'bps' => 2, 'rank_group' => 'Cook', 'cadre_type' => 'non_uniform'],
            ['title' => 'Helper Cook', 'short_form' => 'Hlpr Cook', 'bps' => 2, 'rank_group' => 'Helper Cook', 'cadre_type' => 'non_uniform'],
            ['title' => 'Washerman', 'short_form' => 'Washerman', 'bps' => 2, 'rank_group' => 'Washerman', 'cadre_type' => 'non_uniform'],
            ['title' => 'Barber', 'short_form' => 'Barber', 'bps' => 2, 'rank_group' => 'Barber', 'cadre_type' => 'non_uniform'],
            ['title' => 'Sweeper', 'short_form' => 'Sweeper', 'bps' => 2, 'rank_group' => 'Sweeper', 'cadre_type' => 'non_uniform'],
        ];

        foreach ($designations as $designation) {
            \App\Models\Designation::updateOrCreate(
                ['title' => $designation['title'], 'bps' => $designation['bps'], 'short_form' => $designation['short_form']],
                $designation
            );
        }
    }
}