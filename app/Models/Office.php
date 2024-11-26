<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Service;
use App\Models\Feedback;
use App\Models\User;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_name',
        // 'description',
    ];

    protected static function boot()
    {
        parent::boot();

        // Listen for creation and update events
        static::created(function ($office) {
            Notification::create([
                'office_id' => $office->id,
                'status' => 'approved',
                'dateTime' => now(),
            ]);
        });

        static::updated(function ($office) {
            Notification::create([
                'office_id' => $office->id,
                'status' => 'approved',
                'dateTime' => now(),
            ]);
        });
    }

    
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    // Define the relationship with feedbacks
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function translations()
    {
        return $this->hasMany(OfficeTranslation::class);
    }


    public function translation($languageCode)
{
    return $this->translations()
        ->whereHas('language', function ($query) use ($languageCode) {
            $query->where('code', $languageCode);
        })
        ->first();
}



    
}
