<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MMenuTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    public $timestamps = false;
    protected $fillable = [
        'title',
        'url',
        'icon',
        'm_status_tabs_id',
        'sequence',
        'parent_id',
    ];

    public function child()
    {
        return $this->hasMany(MMenuTab::class, 'parent_id', 'id')->where('m_status_tabs_id', 1)->orderBy('sequence', 'asc');
    }

    public function scopeDetail($query)
    {
        $query->with('child');
        return $query;
    }
}
