<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;

class MUnitClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_unit_class_tabs')->insert(
            array(
                [
                    'title' => 'KPR',
                ],
                [
                    'title' => 'Komersil',
                ]
            )
        );
    }
}
