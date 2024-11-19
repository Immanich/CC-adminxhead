<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MunicipalOfficial extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'title', 'image'];
}
