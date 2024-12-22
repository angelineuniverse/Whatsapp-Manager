<?php

namespace Modules\Company\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Models\MMenuTab;

class MRolesMenuTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    public $timestamps = false;
    protected $fillable = [
        'm_roles_tabs_id',
        'm_menu_tabs_id',
        'm_action_tabs_id',
    ];
    public function menu()
    {
        return $this->hasOne(MMenuTab::class, 'id', 'm_menu_tabs_id')->whereNull('parent_id');
    }

    public function role()
    {
        return $this->hasOne(MRolesTab::class, 'id', 'm_roles_tabs_id');
    }

}
