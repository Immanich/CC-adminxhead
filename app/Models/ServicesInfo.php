<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicesInfo extends Model
{
    use HasFactory;

    protected $table = 'services_infos';

    // Allow mass assignment on these columns
    protected $fillable = [
        'service_id',
        'office_id',
        'step',
        'info_title',
        'clients',
        'agency_action',
        'fees',
        'processing_time',
        'person_responsible',
    ];

    
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }
    public function translations()
    {
        return $this->hasMany(ServicesInfoTranslation::class);
    }
    
    public function translation($languageCode)
    {
        return $this->translations()
            ->whereHas('language', fn($query) => $query->where('code', $languageCode))
            ->first();
    }
}
