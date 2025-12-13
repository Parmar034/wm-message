<?php

namespace App\Http\Controllers\QRCode;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QrCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Imports\QrCodeImport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;




class QRController extends Controller 
{
  public function index()
  {
    return view('frontend.qr-management.index');
  }

  public function addQrManagement()
  {
    return view('frontend.qr-management.add');
  }

  public function BulkEntryReport()
  {
    return view('frontend.qr-management.bulk-qr-entry-report');
  }

  public function Qredit($id)
  {
    $qr_code = QrCode::where('status', 'Unused')->find($id);
      if (!$qr_code) {
         return to_route('qr-management')->with('error', 'QrCode details not found');
      }

    return view('frontend.qr-management.add', compact('qr_code'));
  }

  public function list(Request $request)
  {
        if(isset($request->status) && $request->status != 'All' && $request->status != 'disabled'){
             $qrcode_list = QrCode::where('status', $request->status)->orderBy('created_at', 'desc')->get();

        }else{
            $qrcode_list = QrCode::orderBy('created_at', 'desc')->get();

        }
        $counter = 1;
        $qrcode_list->transform(function ($item) use (&$counter) {
            $item['ser_id'] = $counter++;
            // $item['action'] = "-";

            if($item->status == 'Unused'){
                $item['status'] = '<span style="color:orange">Unused</span>';
                $item['action'] = '<a href="' . route('qr-management.edit',$item['id']) . '" class="table-btn table-btn1 service_edit"><span class="pcoded-micon"><img src="'. asset('assets/images/edit_icon.svg') .'" class="img-fluid white_logo" alt=""></span></a>';
                $item['action'] .= '<a data-id="' . $item['id'] . '"  data-original-title="Delete sections" class="table-btn table-btn1 delete-qrcode-btn"><span class="pcoded-micon"><img src="' . asset('assets/images/delete_icon.svg') .'" class="img-fluid white_logo" alt=""></span></a>';
            }else{
                $item['status'] = '<span style="color:green">Used</span>';
              $item['action'] = '-';
            }
            
            return $item;
        });
        return response()->json(['data' => $qrcode_list]);
  }

  public function updateQRcode(Request $request)
  {
      $json = $request->expectsJson();
        $rules = [
            'qr_code' => 'required|unique:qr_codes,qr_code' . ($request->qrcode_id ? ',' . $request->qrcode_id : ''),
            'qr_serial_no' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        $response = null;

        if ($validator->fails()) {

            if ($json) {
                $response = response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422);
            } else {

              if(isset($request->qrcode_id) && $request->qrcode_id != ''){
                $response = to_route('qr-management.edit', $request->qrcode_id)
                    ->withErrors($validator)
                    ->withInput();
              }else{
                $response = to_route('qr-management.add')
                    ->withErrors($validator)
                    ->withInput();
              }
            }
            return $response;
        }

        if (isset($request->qrcode_id) && $request->qrcode_id != '') {
            $qr_code = QrCode::where('status', 'Unused')->find($request->qrcode_id);
            if (!$qr_code) {
                $response = $json
                    ? response()->json(['status' => false, 'message' => 'Qr Management not found.'], 404)
                    : to_route('qr-management')->with('error', 'Qr Management not found.');
                return $response;
            }
            $save = $qr_code->update($validator->validated());
        } else {
            $validated = $validator->validated();
            $validated['status'] = 'Unused';
            $validated['user_id'] = Auth::id();

            $qr_code = new QrCode($validated);
            $save = $qr_code->save();
        }

        if (!$save) {
            $response = $json
                ? response()->json(['status' => false, 'message' => 'Something went wrong.'])
                : to_route('qr-management')->with('error', 'Something went wrong while updating record');
        } else {
          if(isset($request->qrcode_id) && $request->qrcode_id != ''){
            $response = $json
                ? response()->json(['status' => true, 'message' => 'QR code management updated successfully.'])
                : to_route('qr-management')->with('success', 'QR code management updated successfully.');
          }else{
            $response = $json
                ? response()->json(['status' => true, 'message' => 'Qr Management saved successfully.'])
                : to_route('qr-management')->with('success', 'Qr Management saved successfully.');
          }
        }

        return $response;
  }

  public function destroy(Request $request)
  {
        $id = $request->id;
        $member = QrCode::where('status', 'Unused')->find($id);

        if ($member) {
            $member->delete();

            return response()->json([
                'status' => 1,
                'message' => 'QR code management deleted successfully.',
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'QR code management not found.',
            ]);
        }
  }

  public function used_destroy(Request $request)
  {
        // $id = $request->id;
    $startDate = Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d');
    $endDate = Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d');
        $deletedCount = QrCode::where('status', 'Used')
        ->whereBetween('used_date', [$startDate, $endDate])
        ->forceDelete();


        if ($deletedCount) {

            return response()->json([
                'status' => 1,
                'message' => 'Used QR code deleted successfully.',
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Used QR code not found.',
            ]);
        }
  }

  public function sample_file_download()
  {
      $filePath = public_path('sample_files/report-sample.xlsx');
      // if (file_exists($filePath)) {
      //       return response()->download($filePath, 'ReportSampleFile.xlsx');
            
      //   }
          if (file_exists($filePath)) {
                return response()->download($filePath, 'ReportSampleFile.xlsx', [
                    'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                    'Pragma' => 'no-cache',
                    'Expires' => '0',
                ]);
            }
  }

   public function qr_code_import(Request $request)
  {
      $json = $request->expectsJson();
      // $request->validate([
      //       'excel_file' => 'required|mimes:xlsx,xls',
      //   ]);
      $rules = [
            'excel_file' => 'required|mimes:xlsx,xls,csv',
        ];

        $validator = Validator::make($request->all(), $rules);

        $response = null;

        if ($validator->fails()) {

            if ($json) {
                $response = response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422);
            } else {
             
                $response = to_route('qr-management.bulk-qr-entry-report')
                    ->withErrors($validator)
                    ->withInput();
              
            }
            return $response;
        }

        $import = new QrCodeImport();
                      Excel::import($import, $request->file('excel_file'));
		
            if($import->saved > 0){
                return $json
                ? response()->json(['status' => true, 'message' => 'QR Codes imported successfully.'])
                : to_route('qr-management')->with('success', 'QR Codes imported successfully.');
            }else if($import->wrong_file > 0){
                 return $json
                ? response()->json(['status' => true, 'message' => 'Something went wrong.'])
                : to_route('qr-management.bulk-qr-entry-report')->with('error', 'Invalid file format. Please upload a valid file.');
            }else{
                return $json
                ? response()->json(['status' => true, 'message' => 'These records were not inserted because they already exist.'])
                : to_route('qr-management.bulk-qr-entry-report')->with('error', 'These records were not inserted because they already exist.');
            }

  
  }

  // public function qr_code_import(Request $request)
  // {
  //     $json = $request->expectsJson();
  //     $request->validate([
  //           'excel_file' => 'required|mimes:xlsx,xls',
  //       ]);

  //       try {
  //           // Excel::import(new QrCodeImport, $request->file('excel_file'));
  //           $import = new QrCodeImport();
  //                     Excel::import($import, $file);
  //           if($import->saved > 0){
  //               return $json
  //               ? response()->json(['status' => true, 'message' => 'QR Codes imported successfully.'])
  //               : to_route('qr-management')->with('success', 'QR Codes imported successfully.');
  //           }else{
  //               return $json
  //               ? response()->json(['status' => true, 'message' => 'These records were not inserted because they already exist.'])
  //               : to_route('qr-management')->with('success', 'These records were not inserted because they already exist.');
  //           }
            
  //       } catch (\Exception $e) {
      
  //           return  to_route('qr-management.bulk-qr-entry-report')
  //                   ->withErrors($validator)
  //                   ->withInput();
  //       }
  // }


}
