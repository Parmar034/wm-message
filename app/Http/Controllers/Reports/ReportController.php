<?php

namespace App\Http\Controllers\Reports;

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


class ReportController extends Controller
{
  public function index()
  {
        $m_codes = Member::select('member_code', 'member_name')->get();
        $qr_code_sr_nos = QrCode::select('qr_serial_no', 'qr_code')->where('status', 'Used')->get();
        $qr_codes = QrCode::select('qr_code', 'qr_serial_no')->where('status', 'Used')->get();

    return view('frontend.reports.index', compact('m_codes', 'qr_code_sr_nos', 'qr_codes'));
  }
  public function list(Request $request)
  {
    $query = QrCode::with(['member:id,member_code,member_name'])->with(['user:id,name'])
                ->select('id', 'used_date', 'member_id', 'qr_serial_no', 'qr_code', 'scan_by', 'message')
                ->where('status', 'Used')
                ->orderBy('used_date', 'desc');

                if (isset($request->member_code) && $request->member_code != 'disabled') {
                    $query->where('member_id', $request->member_code);
                }

                if (isset($request->member_name) && $request->member_name != 'disabled') {
                    $query->whereHas('member', function ($q) use ($request) {
                        $q->where('member_name', $request->member_name);
                    });
                }

                if (isset($request->qr_code) && $request->qr_code != 'disabled') {
                    $query->where('qr_code', $request->qr_code);
                }

                if (isset($request->sr_no) && $request->sr_no != 'disabled') {
                    $query->where('qr_serial_no', $request->sr_no);
                }

                if (isset($request->start_date)) {
                    $query->whereDate('used_date', '>=', Carbon::parse($request->start_date));
                }

                if (isset($request->end_date)) {
                    $query->whereDate('used_date', '<=', Carbon::parse($request->end_date));
                }



            $members = $query->get();
            $counter = 1;
            $members->transform(function ($item) use (&$counter) {
              $item['ser_id'] = $counter++;
                $item['member_name'] = isset($item->member->member_name) ? $item->member->member_name : '';
                $item['scan_by_name'] = $item->user->name;
                $used_date = $item->used_date;
                $item['date'] = Carbon::parse($used_date)->format('d-m-Y');

                return $item;
            });

            return response()->json(['data' => $members]);

  }

  public function excel_export(Request $request)
  {
        $query = QrCode::with(['member:id,member_code,member_name'])->with(['user:id,name'])
                ->select('id', 'used_date', 'member_id', 'qr_serial_no', 'qr_code', 'scan_by', 'message')
                ->where('status', 'Used')
                ->orderBy('used_date', 'desc');

                if (isset($request->member_code) && $request->member_code != 'disabled') {
                    $query->where('member_id', $request->member_code);
                }

                if (isset($request->member_name) && $request->member_name != 'disabled') {
                    $query->whereHas('member', function ($q) use ($request) {
                        $q->where('member_name', $request->member_name);
                    });
                }

                if (isset($request->qr_code)  && $request->qr_code != 'disabled') {
                    $query->where('qr_code', $request->qr_code);
                }

                if (isset($request->sr_no) && $request->sr_no != 'disabled') {
                    $query->where('qr_serial_no', $request->sr_no);
                }

                if (isset($request->start_date)) {
                    $query->whereDate('used_date', '>=', Carbon::parse($request->start_date));
                }

                if (isset($request->end_date)) {
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



     
            return Excel::download(new ReportExport($exportData), 'report.xlsx');

    }
}
