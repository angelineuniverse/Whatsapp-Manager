<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MModuleTabsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_module_tabs')->insert(
            array(
                ['module' => 'Project'],
                ['module' => 'Roles'],
                ['module' => 'Menu'],
                ['module' => 'Pengguna'],
                ['module' => 'Profile'],
                ['module' => 'Unit'],
            )
        );
    }
}
