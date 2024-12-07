<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_of_transaction',
    ];
    
    // Transaction Model
public function services()
{
    return $this->hasMany(Service::class);
}

}
