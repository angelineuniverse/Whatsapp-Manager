<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MCodeTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    public $timestamps = false;
    protected $fillable = [
        'preffix',
        'start',
        'length',
        'year',
        'description',
    ];

    public static function generateCode($preffix)
    {
        $code = self::where('preffix', $preffix)->first();
        if ($code->year != date("y")) {
            $code->update([
                'year' => date("y"),
                'start' => 1
            ]);
        }
        $next_value = $code->start;
        $code->increment('start', 1);
        return $code->preffix . $code->year . str_pad($next_value, $code->length, '0', STR_PAD_LEFT);
    }

}
