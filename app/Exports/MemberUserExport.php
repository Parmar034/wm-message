<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Plan;
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


class MemberUserExport implements FromCollection, WithCustomStartCell,  WithHeadings, WithStyles
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
        $query = UserMember::with('user')->orderBy('created_at', 'desc')
        

                ->when($this->data['selected_items'] !== 'all', function ($q) {
                    $q->whereIn('id', $this->data['selected_items']);
                });

                if (Auth::user()->role == 'Admin') {
                    $query->where('user_id', Auth::id());
                }

                if (!empty($this->data['members_filter'])) {
                    $query->where('user_id', $this->data['members_filter']);
                }



            $member_list = $query->get();

        $column_name_multi = $this->data['column_name'];
        array_unshift($column_name_multi, 'Sr. No');

        $data = [];
        $counter = 1;


        foreach ($member_list as $member) {
            $row = [];

            foreach ($column_name_multi as $column) {
                switch (trim($column)) {
                    case 'Sr. No':
                        $row[] = $counter++;
                        break;
                    case 'Name':
                        $row[] = isset($member->member_name) ? $member->member_name : '-';
                        break;
                    case 'Email':
                        $row[] = isset($member->member_email) ? $member->member_email : '-';
                        break;  
                    case 'Phone':
                        $row[] = $member->country_code .' '. $member->phone ; 
                        break;
                    case 'Member':
                        $row[] = isset($member->user->name) ? $member->user->name : '-';
                        break;
                    case 'Status':
                        $row[] = $member->status == 1 ? 'Enabled' : 'Disabled';
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
