<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\UserMember;
use App\Models\SendMessage;
use App\Models\BulkUploadUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BulkUsersImport;
use Illuminate\Support\Facades\Storage;


class BulkUploadUserController extends Controller
{


    public function bulkuserupload()
    {
        return view('frontend.user-management.bulkuploaduser');
    }

    public function storeuploaduser(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,xls',
        ]);

        try {
            
            $filename =$request->file('file')->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('uploads/users', $filename, 'public');

            $upload=Excel::import(new BulkUsersImport, $request->file('file'));

                if($upload){
                    BulkUploadUser::Create([
                        'file'=>$filename,
                    ]);
                }

            return back()->with('success', 'UserMembers uploaded successfully!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                foreach ($failure->errors() as $message) {
                    $errorMessages[] = $message;
                }   
            }
            return back()->with('error', implode(' | ', $errorMessages));

        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: '.$e->getMessage());
        }
    }

    public function list(Request $request){
        $perPage = $request->input('length', 10);
        $page = $request->input('start', 0) / $perPage;
        $searchValue = $request->input('search.value');

        $query = BulkUploadUser::orderBy('created_at', 'desc');

        if (!empty($searchValue)) {
            $query->where('file', 'LIKE', "%{$searchValue}%");
        }

        $totalRecords = $query->count();

        $member_list = $query->skip($page * $perPage)
            ->take($perPage)
            ->get();

        $counter = $page * $perPage + 1;

        $member_list->transform(function ($item) use (&$counter) {
            $item->serial_no = $counter++;
            $item->file_name = $item->file;
            $item->action =
                '<a href="'.route('bulk-download',$item['id']).'" class="table-btn table-btn1">
                    <span class="pcoded-micon">
                        <img src="'. asset('assets/images/download.svg') .'" class="img-fluid white_logo">
                    </span>
                </a>';

            return $item;
        });
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $member_list,
        ]);
    }


    public function download($id)
    {
        $userFile = BulkUploadUser::findOrFail($id);

        if (!Storage::disk('public')->exists('uploads/users/' . $userFile->file)) {
            return back()->with('error', 'File not found.');
        }

        return Storage::disk('public')->download('uploads/users/' . $userFile->file);
    }

}
