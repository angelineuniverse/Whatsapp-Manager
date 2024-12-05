<?php

namespace Modules\Company\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Models\MUnitClassTab;

class MUnitTypeTabs extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'm_project_tabs_id',
        'm_unit_status_tabs_id',
        'm_unit_class_tabs_id',
        'title',
        'descriptions',
        'price',
        'long_build',
        'long_land',
        'width_build',
        'width_land',
    ];


    public function project()
    {
        return $this->hasOne(MProjectTab::class, 'id', 'm_project_tabs_id');
    }

    public function unit_status()
    {
        return $this->hasOne(MUnitStatusTabs::class, 'id', 'm_unit_status_tabs_id');
    }

    public function unit_class()
    {
        return $this->hasOne(MUnitClassTab::class, 'id', 'm_unit_class_tabs_id');
    }

    public function scopeSearch($query, $request = null)
    {
        if ($request->title) $query->where('title', $request->title);
        if ($request->m_project_tabs_id) $query->where('m_project_tabs_id', $request->m_project_tabs_id);
        return $query;
    }

    public function scopeDetail($query)
    {
        $query->with('project', 'unit_status', 'unit_class');
        return $query;
    }

}
