<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Office;
use App\Models\Service;

class Feedback extends Model
{
    use HasFactory;
    protected $table = 'feedback';
    protected $fillable = [
        'office_id',
        'service_id',
        'feedback',
        'name',
    ];

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}