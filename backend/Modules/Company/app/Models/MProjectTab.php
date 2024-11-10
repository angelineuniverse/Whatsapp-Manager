<?php

namespace Modules\Company\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Company\Database\Factories\MProjectTabFactory;

class MProjectTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'm_company_tabs_id',
        'title',
        'avatar',
        'description',
        'address',
        'm_status_tabs_id',
    ];
}
