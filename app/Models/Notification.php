<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\DatabaseNotification as NotificationBase;
use Illuminate\Database\Eloquent\Model;

class Notification extends NotificationBase
{
    use HasFactory;
    protected $fillable = [
        // 'title',
        // 'description',
        'dateTime',
        'event_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function event(){
        return $this->belongsTo(Event::class);
    }
}
