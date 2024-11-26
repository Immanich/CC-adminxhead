<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mvmsp extends Model
{
    /** @use HasFactory<\Database\Factories\MvmspFactory> */
    use HasFactory;

    protected $fillable = [
        'mandate',
        'vision',
        'mission',
        'service_pledge',
        'office_id', // Include this if you're assigning office-specific MVMSP
    ];

    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}
