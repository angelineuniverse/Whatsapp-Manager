<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Master\Models\MStatusTab;

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
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function status()
    {
        return $this->hasOne(MStatusTab::class, 'id', 'm_status_tabs_id');
    }

    public function user_role()
    {
        return $this->hasOne(TUserRolesTab::class, 'm_user_tabs_id', 'id');
    }

    public function scopeQuery($query, $request = null)
    {
        $query->with(['status', 'user_role' => function ($a) {
            $a->with('role');
        }]);
        return $query;
    }
}
