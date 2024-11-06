<?php

namespace Modules\Company\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Models\MStatusTab;

class MCompanyTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'name',
        'email',
        'avatar',
        'm_status_tabs_id'
    ];

    public function status()
    {
        return $this->hasOne(MStatusTab::class, 'id', 'm_status_tabs_id');
    }

    public function scopeSearch($query, $request = null)
    {
        if ($request->name) $query->where('name', $request->name);
        if ($request->email) $query->where('email', $request->email);
        if ($request->code) $query->where('code', $request->code);
        return $query;
    }

    public function scopeDetail($query)
    {
        $query->with('status');
        return $query;
    }
    
}
