<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Models\MActionTab;
use Modules\Master\Models\MModuleTab;

class TUserLogTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'm_company_tabs_id',
        'm_user_tabs_id',
        'm_module_tabs_id',
        'm_action_tabs_id',
        'description',
    ];

    public function module()
    {
        return $this->hasOne(MModuleTab::class, 'id', 'm_module_tabs_id');
    }
    public function action()
    {
        return $this->hasOne(MActionTab::class, 'id', 'm_action_tabs_id');
    }

    public function scopeDetail($query)
    {
        $query->with('module', 'action');
        return $query;
    }
}
