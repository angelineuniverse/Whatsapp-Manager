<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MMenuTabsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_menu_tabs')->insert(
            array(
                [
                    'title' => 'Dashboard',
                    'url' => "/",
                    'icon' => "home_simple",
                    'm_status_tabs_id' => 1,
                    'sequence' => 1,
                    'parent_id' => null,
                ],
                [
                    'title' => 'Master',
                    'url' => "/master",
                    'icon' => "database",
                    'm_status_tabs_id' => 1,
                    'sequence' => 7,
                    'parent_id' => null,
                ],
                [
                    'title' => 'Project',
                    'url' => "/project",
                    'icon' => "element",
                    'm_status_tabs_id' => 1,
                    'sequence' => 1,
                    'parent_id' => 2,
                ],
                [
                    'title' => 'Unit',
                    'url' => "/unit",
                    'icon' => "home_simple",
                    'm_status_tabs_id' => 1,
                    'sequence' => 2,
                    'parent_id' => 2,
                ],
                [
                    'title' => 'Roles',
                    'url' => "/roles",
                    'icon' => "role",
                    'm_status_tabs_id' => 1,
                    'sequence' => 3,
                    'parent_id' => 2,
                ],
                [
                    'title' => 'Menu',
                    'url' => "/menu",
                    'icon' => "menu",
                    'm_status_tabs_id' => 1,
                    'sequence' => 4,
                    'parent_id' => 2,
                ],
                [
                    'title' => 'Pengguna',
                    'url' => "/pengguna",
                    'icon' => "person",
                    'm_status_tabs_id' => 1,
                    'sequence' => 5,
                    'parent_id' => 2,
                ],
                [
                    'title' => 'Profile',
                    'url' => "/profile",
                    'icon' => "person",
                    'm_status_tabs_id' => 1,
                    'sequence' => 8,
                    'parent_id' => null,
                ],
            )
        );
    }
}
