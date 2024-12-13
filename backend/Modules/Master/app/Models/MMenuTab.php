<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Company\Models\MRolesMenuTab;

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
        'description',
        'parent_id',
    ];

    protected $appends = ['selected', 'isgroup'];

    public function getSelectedAttribute()
    {
        return isset($this->attributes['selected']) ? $this->attributes['selected'] : false;
    }

    public function rolesmenu()
    {
        return $this->hasOne(MRolesMenuTab::class, 'm_menu_tabs_id', 'id');
    }

    public function getIsgroupAttribute()
    {
        return count($this->child) > 0 && $this->parent_id == null ? true : false;
    }

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
