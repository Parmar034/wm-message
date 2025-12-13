<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SendMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'user_members_id',
        'message_text',
    ];

    public $timestamps = true;

    public function userMembers()
    {
        return $this->belongsToMany(UserMember::class, 'send_message_user_member', 'send_message_id', 'user_member_id');
    }
}
