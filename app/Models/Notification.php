<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'dateTime',
        'user_id',
        'is_read',
        'link' // To track if a notification is read
    ];

    protected $dates = ['dateTime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
