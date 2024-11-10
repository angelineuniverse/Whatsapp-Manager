<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Users\Database\Factories\TUserRolesTabFactory;

class TUserRolesTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'm_user_tabs_id',
        'm_roles_tabs_id',
    ];

    // protected static function newFactory(): TUserRolesTabFactory
    // {
    //     // return TUserRolesTabFactory::new();
    // }
}
