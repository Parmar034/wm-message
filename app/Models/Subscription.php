<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'user_id',
        'plan_id',
        'start_date',
        'end_date',
        'status',
    ];

    public $timestamps = true;

    public function sendMessages()
    {
        return $this->belongsToMany(SendMessage::class, 'send_message_user_member', 'user_member_id', 'send_message_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}
