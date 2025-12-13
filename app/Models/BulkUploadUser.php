<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkUploadUser extends Model
{
   

    protected $fillable = [
        'id',
        'file',
        'member_name',
    ];

    public $timestamps = true;
}
