<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Plan extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'id',
        'plan_name',
        'plan_type',
        'message_type',
        'message_count',
        'price',
        'description',
    ];

    public $timestamps = true;

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['plan_name', 'plan_type', 'message_type', 'message_count', 'price', 'description'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Plan {$eventName}");
    }
}
