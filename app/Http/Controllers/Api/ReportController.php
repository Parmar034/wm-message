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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;



class ReportController extends Controller{

	public function report_dropdown(Request $request)
    {
        // $m_code = Member::select('member_code')->get();
        // $member_name = Member::select('member_name')->get();

        // $qr_code_sr_no = QrCode::select('qr_serial_no')->where('status', 'Used')->get();
        // $qr_code = QrCode::select('qr_code')->where('status', 'Used')->get();


        $m_code = Member::pluck('member_code')->toArray();
        $member_name = Member::pluck('member_name')->toArray();

        $qr_code_sr_no = QrCode::where('status', 'Used')->pluck('qr_serial_no')->toArray();
        $qr_code = QrCode::where('status', 'Used')->pluck('qr_code')->toArray();
       
            return response()->json([
                'status' => 'Success',
                'message' => 'Report Dropdown list found successfully.',
                'm_code' => $m_code,
                'member_name' => $member_name,
                'qr_code_sr_no' => $qr_code_sr_no,
                'qr_code' => $qr_code
            ]);

    }


    public function list(Request $request)
    {

        $query = QrCode::with(['member:id,member_code,member_name'])->with(['user:id,name'])
                ->select('id', 'used_date', 'member_id', 'qr_serial_no', 'qr_code', 'scan_by','message')
                ->where('status', 'Used')
                ->orderBy('used_date', 'desc');

                if (!empty($request->m_code)) {
                    $query->where('member_id', $request->m_code);
                }

                if (!empty($request->m_name)) {
                    $query->whereHas('member', function ($q) use ($request) {
                        $q->where('member_name', $request->m_name);
                    });
                }

                if (!empty($request->qrcode)) {
                    $query->where('qr_code', $request->qrcode);
                }

                if (!empty($request->qr_code_sr_no)) {
                    $query->where('qr_serial_no', $request->qr_code_sr_no);
                }

                if (!empty($request->start_date)) {
                    $query->whereDate('used_date', '>=', Carbon::parse($request->start_date));
                }

                if (!empty($request->end_date)) {
                    $query->whereDate('used_date', '<=', Carbon::parse($request->end_date));
                }



            $members = $query->get();


            $members->transform(function ($item) {
                $item->member_name = $item->member->member_name;
                $item->scan_by_name = $item->user->name;
                $used_date = $item->used_date;
                $item->date = Carbon::parse($used_date)->format('d-m-Y');
                $item->note = isset($item->message) ? $item->message : '';


                return $item;
            });

            $members = $members->map(function ($item) {
                unset($item->member); // removes created_at
                unset($item->used_date); 
                unset($item->user); 
                unset($item->scan_by); 
                unset($item->message); 



                return $item;
            });


        if ($members->count()) {
            return response()->json([
                'status' => 'Success',
                'message' => 'Reports list found successfully.',
                'data' => $members
            ]);
        }

        return response()->json([
            'status' => 'Failed',
            'message' => 'No Reports found.',
        ]);
    }

    public function excel_export(Request $request)
    {
        $query = QrCode::with(['member:id,member_code,member_name'])->with(['user:id,name'])
                ->select('id', 'used_date', 'member_id', 'qr_serial_no', 'qr_code', 'scan_by', 'message')
                ->where('status', 'Used')
                ->orderBy('used_date', 'desc');

                if (!empty($request->m_code)) {
                    $query->where('member_id', $request->m_code);
                }

                if (!empty($request->m_name)) {
                    $query->whereHas('member', function ($q) use ($request) {
                        $q->where('member_name', $request->m_name);
                    });
                }

                if (!empty($request->qrcode)) {
                    $query->where('qr_code', $request->qrcode);
                }

                if (!empty($request->qr_code_sr_no)) {
                    $query->where('qr_serial_no', $request->qr_code_sr_no);
                }

                if (!empty($request->start_date)) {
                    $query->whereDate('used_date', '>=', Carbon::parse($request->start_date));
                }

                if (!empty($request->end_date)) {
                    $query->whereDate('used_date', '<=', Carbon::parse($request->end_date));
                }



            $members = $query->get();


                $exportData = $members->map(function ($item) {
                    return [
                        'Date' => Carbon::parse($item->used_date)->format('d-m-Y'),
                        'members_code' => $item->member_id,
                        'member_name' => optional($item->member)->member_name,
                        'qr_serial_no' => $item->qr_serial_no,
                        'qr_code' => $item->qr_code,
                        'scan_by_name' => optional($item->user)->name,
                        'note' => isset($item->message) ? $item->message : '',
                        
                    ];
                });



        if ($exportData->count()) {
           return Excel::download(new ReportExport($exportData), 'report.xlsx');
            // return response()->json([
            //     'status' => 'Success',
            //     'message' => 'Reports list export successfully.',
                
            // ]);
        }

        return response()->json([
            'status' => 'Failed',
            'message' => 'No Reports found.',
        ]);
    }

    public function download_file(Request $request)
    {
        $filePath = public_path('sample_files/report-sample.xlsx');

        if (file_exists($filePath)) {
            return response()->download($filePath, 'ReportSampleFile.xlsx');
            
        } else {
            // abort(404, 'Sample file not found.');
            return response()->json([
                'status' => 'Failed',
                'message' => 'Sample file not found.',
            ]);
        }
    }

  
}