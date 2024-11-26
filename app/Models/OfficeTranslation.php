<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficeTranslation extends Model
{
    protected $fillable = [
        'office_id',
        'language_id',
        'office_name',
        'description',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}
