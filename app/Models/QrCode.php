<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QrCode extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'qr_serial_no',
        'qr_code',
        'user_id',
        'member_id',
        'message',
        'status',
        'used_date',
        'scan_by',
    ];

    public $timestamps = true;

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_code')->withTrashed();
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'scan_by', 'id');
    }
}
