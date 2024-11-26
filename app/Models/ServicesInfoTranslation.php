<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicesInfoTranslation extends Model
{
    protected $fillable = [
        'services_info_id',
        'language_id',
        'service_info_name',
        'description',
    ];
    
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function servicesInfo()
    {
        return $this->belongsTo(ServicesInfo::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
