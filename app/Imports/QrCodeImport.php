<?php
namespace App\Imports;

use App\Models\QrCode;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;


class QrCodeImport implements ToModel, WithHeadingRow, WithCalculatedFormulas
{
    public $saved = 0;
    public $wrong_file = 0;


    
    public function model(array $row)
    {
        if (!isset($row['price']) || !isset($row['qr_code']) || !preg_match('/^\d+$/', $row['price'])) {
            // Optionally log or throw an error
            $this->wrong_file++;
            return null;
        }

        if (empty(trim($row['price'])) || empty(trim($row['qr_code']))) {
            return null;
        }
    	$exists = QrCode::where('qr_code', $row['qr_code'])
            ->exists();
        if ($exists) {
            return null;
        }

        $this->saved++;

        return new QrCode([
            'qr_serial_no' => $row['price'],
            'qr_code'      => $row['qr_code'],
            'user_id'      => Auth::id(),
            'status'    => 'Unused',
        ]);
    }
}