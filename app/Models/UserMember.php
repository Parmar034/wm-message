<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserMember extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'member_name',
        'phone',
        'description',
    ];

    public $timestamps = true;

    public function sendMessages()
    {
        return $this->belongsToMany(SendMessage::class, 'send_message_user_member', 'user_member_id', 'send_message_id');
    }
}
