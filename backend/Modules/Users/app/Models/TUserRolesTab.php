<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Company\Models\MRolesTab;

class TUserRolesTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    public $timestamps = false;
    protected $fillable = [
        'm_user_tabs_id',
        'm_roles_tabs_id',
    ];

    public function user()
    {
        return $this->hasOne(MUserTab::class, 'id', 'm_user_tabs_id');
    }

    public function role()
    {
        return $this->hasOne(MRolesTab::class, 'id', 'm_roles_tabs_id');
    }
}
