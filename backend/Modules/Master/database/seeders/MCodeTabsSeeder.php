<?php

namespace Modules\Master\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MCodeTabsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_code_tabs')->insert(
            array(
                [
                    'preffix' => 'CPY',
                    'year' => Carbon::now()->format('y'),
                    'start' => 1,
                    'length' => 4,
                    'description' => "Company",
                ],
                [
                    'preffix' => 'USR',
                    'year' => Carbon::now()->format('y'),
                    'start' => 1,
                    'length' => 4,
                    'description' => "User",
                ]
            )
        );
    }
}
