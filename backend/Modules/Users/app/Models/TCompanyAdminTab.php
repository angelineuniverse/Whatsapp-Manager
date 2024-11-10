<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TCompanyAdminTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    public $timestamps = false;
    protected $fillable = [
        'm_company_tabs_id',
        'm_user_tabs_id',
    ];
}
