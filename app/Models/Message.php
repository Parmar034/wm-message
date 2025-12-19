<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'sender_id',
        'message',
    ];

    public function userMembers()
    {
        // return $this->belongsToMany(UserMember::class)
        //             ->withPivot('is_read')
        //             ->withTimestamps();
        return $this->belongsToMany(UserMember::class, 'message_user', 'message_id', 'user_id')->withPivot(['created_at','status'])->withTimestamps();
    }

    public $timestamps = true;
}
