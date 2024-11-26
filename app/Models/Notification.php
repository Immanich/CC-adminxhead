<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\DatabaseNotification as NotificationBase;
use Illuminate\Database\Eloquent\Model;

class Notification extends NotificationBase
{
    use HasFactory;
    protected $fillable = [
        'event_id',
        'office_id',
        'service_id',
        'user_id',
        'status',
        'dateTime',
        'title',
        'description',
        'dateTime',
        'user_id',
        'is_read',
        'link'
    ];

    protected $dates = ['dateTime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
