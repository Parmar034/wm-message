<?php

namespace App\Exports;

use App\Models\SendMessage;
use App\Models\UserMember;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;


class MessageExport implements FromArray, WithHeadings
{
    protected $sendmessageid;
    protected $messages;

    public function __construct($messages)
    {
        $this->messages = $messages;
    }

    public function headings(): array
    {
        return [
            'Name',
            'Phone',
            'Message',
            'Created Date',
        ];
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->messages as $msg) {
            foreach ($msg->userMembers as $member) {
                $rows[] = [
                    $member->member_name,
                    $member->phone,
                    $msg->message_text,
                    Carbon::parse($msg->created_at)->format('d-m-Y H:i:s'),
                ];
            }
        }

        return $rows;
    }

}
