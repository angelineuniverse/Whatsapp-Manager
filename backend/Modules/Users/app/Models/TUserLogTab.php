<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TUserLogTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'm_company_tabs_id',
        'm_user_tabs_id',
        'm_module_tabs_id',
        'm_action_tabs_id',
        'description',
    ];
}
