<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\QrCode;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Validator;
use App\Imports\QrCodeImport;
use Maatwebsite\Excel\Facades\Excel;



class QrmanagementController extends Controller{

	public function list(Request $request)
    {
        $query = QrCode::select('id', 'qr_serial_no', 'qr_code', 'created_at', 'status')->orderBy('created_at', 'desc');

        if ($request->get('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('qr_serial_no', 'like', "%$search%")
                    ->orWhere('qr_code', 'like', "%$search%");
            });
        }

        if (isset($request->filter) && $request->filter != 'All') {
            $query->where('status', $request->filter);
        }

        $qr_management = $query->get();

        $qr_management->transform(function ($item) {
            $created_at = $item->created_at;
            $item->date = $created_at->format('d-m-Y');

            return $item;
        });

        $qr_management = $qr_management->map(function ($item) {
            unset($item->created_at); // removes created_at
            return $item;
        });


        if ($qr_management->count()) {
            return response()->json([
                'status' => 'Success',
                'message' => 'Qr Management list found successfully.',
                'data' => $qr_management
            ]);
        }

        return response()->json([
            'status' => 'Failed',
            'message' => 'No Qr Management found.',
        ]);
    }


    public function add(Request $request)
    {
    	$json = $request->expectsJson();
        $rules = [
            'qr_serial_no' => 'required',
            'qr_code' => 'required|unique:qr_codes,qr_code' . ($request->qr_id ? ',' . $request->qr_id : '')
        ];
        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
         
            return response()->json(['status' => 'Failed', 'message' => $validator->errors()->first()], 200);

        }

        if (isset($request->qr_id) && $request->qr_id != ''){
            $qr_management = QrCode::find($request->qr_id);
            if (!$qr_management) {
             
                 return response()->json(['status' => 'Failed', 'message' => 'QR entry not found.'], 200);

            }
            $save = $qr_management->update($validator->validated());
        } else {
            $validated = $validator->validated();
            $validated['status'] = 'Unused';
            $validated['user_id'] = Auth::id();

            $qr_management = new QrCode($validated);
            $save = $qr_management->save();
        }

        if (!$save) {
            return response()->json(['status' => 'Failed', 'message' => 'Something went wrong.'], 200);
        } else {
        	if(isset($request->qr_id) && $request->qr_id != ''){
        		return response()->json(['status' => 'Success','message' => 'QR entry updated successfully.'], 200);
        	}else{
        		return response()->json(['status' => 'Success','message' => 'QR entry saved successfully.'], 200);
        	}
            
        }

    }

    public function destroy(Request $request)
    {
        $id = $request->qr_id;
        $qr_management = QrCode::find($id);

        if ($qr_management) {
            $qr_management->delete();

            return response()->json([
                'status' => 'Success',
                'message' => 'QR entry deleted successfully.',
            ]);
        } else {
            return response()->json([
                'status' => 'Failed',
                'message' => 'QR entry not found.',
            ]);
        }
    }

    public function scan(Request $request)
    {

        $rules = [
            'member_id' => 'required',
            'qr_code' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
         
            return response()->json(['status' => 'Failed', 'message' => $validator->errors()], 200);

        }
        // $qr_management = QrCode::where('qr_code', $request->qr_code)->where('status', 'Unused')->first();
        $qr_management = QrCode::where('qr_code', $request->qr_code)->first();


        if (!$qr_management) {
             
            return response()->json(['status' => 'Failed', 'message' => 'QR entry not found.'], 200);

        }else if(isset($qr_management->status) && $qr_management->status != 'Unused') {
            return response()->json(['status' => 'Failed', 'message' => 'This QR code has already been used by another member.'], 200);
        }
        $validated = $validator->validated();
        $validated['status'] = 'Used';
        $validated['message'] = isset($request->note) ? $request->note : '';
        $validated['used_date'] = Carbon::now();
        $validated['scan_by'] = Auth::id();




            $save = $qr_management->update($validated);
        if (!$save) {
            return response()->json(['status' => 'Failed', 'message' => 'Something went wrong.'], 200);
        } else {
            return response()->json(['status' => 'Success','message' => 'QR code scanned successfully.'], 200);

        }    


    }

    public function excel_import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {

            // Excel::import(new QrCodeImport, $request->file('file'));
            $import = new QrCodeImport();
                      Excel::import($import, $request->file('file'));

            if($import->saved > 0){
                 return response()->json(['status' => 'Success', 'message' => 'QR Codes imported successfully.']);
            }else if($import->wrong_file > 0){
                 return response()->json(['status' => 'Failed', 'message' => 'Invalid file format. Please upload a valid file.']);
            }else{
                 return response()->json(['status' => 'Failed', 'message' => 'These records were not inserted because they already exist.']);
            }

           
        } catch (\Exception $e) {
            return response()->json(['status' => 'Failed', 'message' => 'Import failed: ' . $e->getMessage()], 200);
        }
    }
}