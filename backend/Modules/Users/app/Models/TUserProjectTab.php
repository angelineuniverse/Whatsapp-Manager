<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Users\Database\Factories\TUserProjectTabFactory;

class TUserProjectTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'm_user_tabs_id',
        'm_project_tabs_id',
    ];

    // protected static function newFactory(): TUserProjectTabFactory
    // {
    //     // return TUserProjectTabFactory::new();
    // }
}
