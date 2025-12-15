<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;

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
}
