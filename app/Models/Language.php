<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name'];

    // Example relationship if required
    public function serviceTranslations()
    {
        return $this->hasMany(ServiceTranslation::class, 'language_id');
    }
}
