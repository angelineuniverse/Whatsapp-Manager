<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MActionTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    public $timestamp = false;
    protected $fillable = [
        'action',
        'color',
    ];
    public static $ADD = 1;
    public static $DELETE = 2;
    public static $UPDATE = 3;
    public static $CHANGE = 4;
}
