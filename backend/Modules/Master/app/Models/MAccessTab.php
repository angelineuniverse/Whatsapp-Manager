<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Company\Models\MCompanyTab;

class MAccessTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    public $timestamps = false;
    protected $fillable = [
        'title',
        'm_company_tabs_id',
        'color',
        'parent_id'
    ];

    public function company()
    {
        return $this->hasOne(MCompanyTab::class, 'id', 'm_company_tabs_id');
    }

    public function child()
    {
        return $this->hasMany(MAccessTab::class, 'parent_id', 'id');
    }

    public function scopeSearch($query, $request = null)
    {
        if ($request->title) $query->where('title', $request->title);
        if ($request->m_company_tabs_id) $query->where('m_company_tabs_id', $request->m_company_tabs_id);
        return $query;
    }

    public function scopeDetail($query)
    {
        $query->with('company', 'child');
        return $query;
    }

}
