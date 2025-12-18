<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageUser  extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'message_id',
        'user_id',
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public $timestamps = true;
}
