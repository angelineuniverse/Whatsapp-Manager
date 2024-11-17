<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MStatusTabsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_status_tabs')->insert(
            array(
                [
                    'title' => 'Active',
                    'color' => "emerald",
                ],
                [
                    'title' => 'Not Active',
                    'color' => "rose",
                ]
            )
        );
    }
}
