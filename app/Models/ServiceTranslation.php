<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceTranslation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'service_id',
        'language_id',
        'service_name',
        'description',
    ];
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

}
