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
        'm_company_tabs_id',
        'color',
        'parent_id'
    ];
    public $appends = ['users_count'];

    public function getUsersCountAttribute()
    {
        return count($this->users);
    }

    public function company()
    {
        return $this->hasOne(MCompanyTab::class, 'id', 'm_company_tabs_id');
    }

    public function child()
    {
        return $this->hasMany(MRolesTab::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->hasOne(MRolesTab::class, 'id', 'parent_id');
    }

    public function users()
    {
        return $this->hasMany(MUserTab::class, 'm_access_tabs_id', 'id');
    }

    public function scopeSearch($query, $request = null)
    {
        if ($request->title) $query->where('title', $request->title);
        if ($request->m_company_tabs_id) $query->where('m_company_tabs_id', $request->m_company_tabs_id);
        return $query;
    }

    public function scopeDetail($query)
    {
        $query->with('company', 'child', 'parent', 'users');
        return $query;
    }
}
