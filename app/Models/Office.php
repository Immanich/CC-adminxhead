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

    public function mvmsp()
    {
        return $this->hasOne(Mvmsp::class);
    }
}
