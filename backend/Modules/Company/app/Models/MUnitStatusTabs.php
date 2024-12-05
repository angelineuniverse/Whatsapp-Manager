<?php

namespace Modules\Company\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MUnitStatusTabs extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    public $timestamp = false;
    protected $fillable = [
        'm_company_tabs_id',
        'title',
        'color',
    ];

    public function company()
    {
        return $this->hasOne(MCompanyTab::class, 'id', 'm_company_tabs_id');
    }

    public function scopeSearch($query, $request = null)
    {
        if ($request->title) $query->where('title', $request->title);
        if ($request->m_company_tabs_id) $query->where('m_company_tabs_id', $request->m_company_tabs_id);
        return $query;
    }

    public function scopeDetail($query)
    {
        $query->with('company');
        return $query;
    }

}
