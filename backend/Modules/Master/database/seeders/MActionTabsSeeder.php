<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MActionTabsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_action_tabs')->insert(
            array(
                [
                    'action' => 'ADD',
                    'color' => 'green',
                ],
                [
                    'action' => 'DELETE',
                    'color' => 'rose',
                ],
                [
                    'action' => 'UPDATE',
                    'color' => 'blue',
                ],
                [
                    'action' => 'CHANGE',
                    'color' => 'change',
                ],
            )
        );
    }
}
