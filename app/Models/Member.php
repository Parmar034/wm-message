<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'member_code',
        'member_name',
        'phone',
        'location',
        'first_scanned_on',
        'first_scanned_code',
    ];

    public $timestamps = true;
}
