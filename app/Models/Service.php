<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_name',
        'description',
        'office_id',
        'classification',
        'transaction_id',
        'status',
        'checklist_of_requirements',
        'where_to_secure',
        'created_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($service) {
            Notification::create([
                'office_id' => $service->office_id,
                'service_id' => $service->id,
                'status' => 'approved',
                'dateTime' => now(),
            ]);
        });

        static::updated(function ($service) {
            Notification::create([
                'office_id' => $service->office_id,
                'service_id' => $service->id,
                'status' => 'approved',
                'dateTime' => now(),
            ]);
        });
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    // Define the relationship with the transaction
    public function transaction(){
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }


    // Define the relationship with service info
    public function servicesInfos()
    {
        return $this->hasMany(ServicesInfo::class);
    }

    public function serviceInfos()
    {
        return $this->hasMany(ServicesInfo::class);
    }

    public function translations()
    {
        return $this->hasMany(ServiceTranslation::class);
    }


    // public function translation($languageCode)
    // {
    //     $languageId = Language::where('code', $languageCode)->first()->id;
    //     return $this->translations()->where('language_id', $languageId)->first();
    // }

    public function translation($languageCode)
    {
        return $this->translations()
            ->whereHas('language', fn($query) => $query->where('code', $languageCode))
            ->first();
    }

    public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

}

