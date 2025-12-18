<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserMember extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'user_id',
        'member_name',
        'member_email',
        'phone',
        'country_code',
        'description',
    ];

    public $timestamps = true;

    public function sendMessages()
    {
        return $this->belongsToMany(SendMessage::class, 'send_message_user_member', 'user_member_id', 'send_message_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
