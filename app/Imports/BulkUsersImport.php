<?php
namespace App\Imports;


use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\UserMember;
use Maatwebsite\Excel\Concerns\WithValidation;


class BulkUsersImport implements ToModel,WithHeadingRow,WithValidation
{
    public function model(array $row)
    {
        return new UserMember([
            'member_name' => $row['name'],
            'phone'       => $row['phone'],
            'description' => $row['description'],
        ]);
    }

    public function rules(): array
    {
        return [
            'name'  => 'required|string|max:255',
            'phone' => 'required|numeric',
        ];
    }

}