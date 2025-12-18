<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Message;
use App\Models\UserMember;
use DateTime;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
// use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class MessageHistoryExport implements FromCollection, WithCustomStartCell,  WithHeadings, WithStyles
{
    protected $data;

    protected $boldRowIndex;

    private $rowHeight = 40;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        $column_name_multi = $this->data['column_name'];
        array_unshift($column_name_multi, 'Sr. No');

        return $column_name_multi;
    }

    public function collection()
    {
        $member_id  = $this->data['members_filter'] ?? null;
        $user_id  = $this->data['users_filter'] ?? null;


         $messages = Message::with(['userMembers.user'])
            ->orderBy('created_at', 'desc')
            ->get();

     
        $data = [];


        foreach ($messages as $message) {
            foreach ($message->userMembers as $member) {
                $data[] = [    
                    'message_id'           => $member->id,
                    'member_name'  => optional($member->user)->name,
                    'user_member_id' => $member->id,  
                    'member_id'  => optional($member->user)->id,
                    'user_name'    => $member->member_name,
                    'user_id'    => $member->user_id,
                    'phone'        => $member->phone,
                    'message_text' => $message->message,
                    'created_at'   => Carbon::parse($member->pivot->created_at)
                                        ->format('d-m-Y'),
                ];
            }
        }

        // 3️⃣ Convert to collection
        $collection = collect($data);

        if ($this->data['selected_items'] !== 'all') {
            $collection = $collection->whereIn('message_id', $this->data['selected_items']);
        }


        if (!empty($member_id)) {
            $collection = $collection->where('member_id', (int) $member_id);
        }

        if (!empty($user_id)) {
            $collection = $collection->where('user_member_id', (int) $user_id);
        }


        $column_name_multi = $this->data['column_name'];
        array_unshift($column_name_multi, 'Sr. No');

        $data = [];
        $counter = 1;

        foreach ($collection as $member) {

            $row = [];

            foreach ($column_name_multi as $column) {
                switch (trim($column)) {
                    case 'Sr. No':
                        $row[] = $counter++;
                        break;
                    case 'Member Name':
                        $row[] = isset($member['member_name']) ? $member['member_name'] : '-';
                        break;
                    case 'User Name':
                        $row[] = isset($member['user_name']) ? $member['user_name'] : '-';
                        break;  
                    case 'Phone':
                        $row[] = isset($member['phone']) ? $member['phone'] : '-';
                        break;
                    case 'Message':
                        $row[] = isset($member['message_text']) ? $member['message_text'] : '-';
                        break;
                    case 'Created At':
                        $row[] = isset($member['created_at']) ? $member['created_at'] : '-';
                        break;  
                    default:
                        $row[] = '';
                        break;
                }
            }

            $data[] = $row;
        }


        $this->boldRowIndex = count($data);

        return collect($data);
    }



    public function startCell(): string
    {
        return 'A1';
    }

    public function styles(Worksheet $sheet)
    {
        $dataStartRow = 1;
        $column_name_multi = $this->data['column_name'];
        array_unshift($column_name_multi, 'Sr. No');

   
            // Make the first row (headings) bold
            $sheet->getStyle('1')->getFont()->setBold(true);

            // Set column widths and headings
            $this->setColumnsAndHeadings($sheet, $column_name_multi);
        

        foreach (range('A', chr(64 + count($column_name_multi))) as $columnID) {
            $sheet->getStyle($columnID)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($columnID)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Set bold styles for specified rows
        // if ($this->boldRowIndex) {
        //     $sheet->getStyle('A'.($this->boldRowIndex + $dataStartRow - 1).':'.chr(64 + count($column_name_multi)).($this->boldRowIndex + $dataStartRow - 1))->getFont()->setBold(true);
        //     for ($i = 1; $i <= $this->boldRowIndex; $i++) {
        //         $sheet->getRowDimension($i)->setRowHeight($this->rowHeight);
        //     }
        // }
        $lastRow = $sheet->getHighestRow();
        $secondToLastRow = $lastRow - 1;
        $sheet->getRowDimension($secondToLastRow)->setRowHeight(40);

        return [];
    }

    private function setColumnsAndHeadings($sheet, $column_name_multi)
    {
        $columnIndex = 1; // Start with column A (index 1)
        foreach ($column_name_multi as $column) {
            $columnLetter = chr(64 + $columnIndex);
            $sheet->setCellValue($columnLetter.'1', $column); // Set headings in row 2
            $sheet->getColumnDimension($columnLetter)->setAutoSize(true); // Set auto size for column
            $columnIndex++;
        }
    }
}
