<?php

namespace Modules\Company\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Models\MStatusTab;

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

    protected $appends = [
        'link'
    ];

    public function getLinkAttribute()
    {
        $files = public_path('avatar') . '/' . $this->avatar;
        $type = pathinfo($files, PATHINFO_EXTENSION);
        $data = file_get_contents($files);
        if (file_exists($files)) {
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        return null;
    }

    public function status()
    {
        return $this->hasOne(MStatusTab::class, 'id', 'm_status_tabs_id');
    }

    public function scopeQuery($query, $request = null)
    {
        $query->with('status');
        return $query;
    }
}
