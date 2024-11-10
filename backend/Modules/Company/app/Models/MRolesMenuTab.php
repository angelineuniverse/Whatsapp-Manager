<?php

namespace Modules\Company\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Company\Database\Factories\MRolesMenuTabFactory;

class MRolesMenuTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): MRolesMenuTabFactory
    // {
    //     // return MRolesMenuTabFactory::new();
    // }
}
