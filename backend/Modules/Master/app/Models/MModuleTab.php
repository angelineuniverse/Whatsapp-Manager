<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MModuleTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    public $timestamp = false;
    protected $fillable = [
        'module'
    ];
    public static $PROJECT = 1;
    public static $ROLES = 2;
    public static $MENU = 3;
    public static $PENGGUNA = 4;
    public static $PROFILE = 5;
    public static $UNIT = 6;
}
