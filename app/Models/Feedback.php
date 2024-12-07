<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Office;
use App\Models\Service;

class Feedback extends Model
{
    use HasFactory;
    protected $table = 'feedback';
    protected $fillable = [
        'office_id',
        'service_id',
        'feedback',
        'name',
        'replied_by'
    ];

    // In Feedback.php model
    public function office(){
        // Make sure the foreign key is correctly referenced
        return $this->belongsTo(Office::class, 'office_id');  // Adjust the foreign key if it's not 'office_id'
    }


    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Feedback Model
    // Feedback model
public function replied_by()
{
    return $this->belongsTo(User::class, 'user_id'); // Assuming user_id is the column for the reply
}


}