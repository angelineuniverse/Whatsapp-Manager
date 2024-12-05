<?php

namespace Modules\Company\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Models\MUnitClassTab;

// use Modules\Company\Database\Factories\MUnitTabsFactory;

class MUnitTabs extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'blok',
        'm_project_tabs_id',
        'm_unit_type_tabs_id',
        'm_unit_status_tabs_id',
    ];

    public function project()
    {
        return $this->hasOne(MProjectTab::class, 'id', 'm_project_tabs_id');
    }

    public function status()
    {
        return $this->hasOne(MUnitStatusTabs::class, 'id', 'm_unit_status_tabs_id');
    }

    public function type()
    {
        return $this->hasOne(MUnitTypeTabs::class, 'id', 'm_unit_type_tabs_id');
    }

    public function scopeSearch($query, $request = null)
    {
        if ($request->blok) $query->where('blok', $request->blok);
        if ($request->m_project_tabs_id) $query->where('m_project_tabs_id', $request->m_project_tabs_id);
        return $query;
    }

    public function scopeDetail($query)
    {
        $query->with(['project', 'status', 'type' => function ($a) {
            $a->with('unit_class');
        }]);
        return $query;
    }
}
