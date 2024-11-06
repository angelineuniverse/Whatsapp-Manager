<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class MUserTab extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'm_company_tabs_id',
        'email',
        'code',
        'name',
        'password',
        'contact',
        'avatar',
        'm_status_tabs_id',
        'm_access_tabs_id',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
