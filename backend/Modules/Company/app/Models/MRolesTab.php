<?php

namespace Modules\Company\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Users\Models\MUserTab;

// use Modules\Company\Database\Factories\MRolesTabFactory;

class MRolesTab extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'title',
        'm_project_tabs_id',
        'color',
        'parent_id'
    ];

    public function project()
    {
        return $this->hasOne(MProjectTab::class, 'id', 'm_project_tabs_id');
    }

    public function child()
    {
        return $this->hasMany(MRolesTab::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->hasOne(MRolesTab::class, 'id', 'parent_id');
    }

    public function scopeSearch($query, $request = null)
    {
        if ($request->title) $query->where('title', $request->title);
        if ($request->m_project_tabs_id) $query->where('m_project_tabs_id', $request->m_project_tabs_id);
        return $query;
    }

    public function scopeDetail($query)
    {
        $query->with('project', 'child', 'parent');
        return $query;
    }
}
