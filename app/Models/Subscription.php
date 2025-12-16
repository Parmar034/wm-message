<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Subscription extends Model
{
    use SoftDeletes, LogsActivity;

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

        public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['user_id', 'plan_id', 'start_date', 'end_date', 'status'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Subscription {$eventName}");
    }
}
