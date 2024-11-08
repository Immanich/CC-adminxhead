<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mvmsp extends Model
{
    /** @use HasFactory<\Database\Factories\MvmspFactory> */
    use HasFactory;

    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}
