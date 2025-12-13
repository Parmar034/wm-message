<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use App\Models\Member;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;



class DashboardController extends Controller
{
    public function index()
    {
        return redirect()->route('user-management');
        $today_used_qrcode = QrCode::whereDate('used_date', Carbon::today())->count();
        $overall_qr_scans = QrCode::where('status', 'Used')->count();
        // $today_members = Member::whereDate('created_at', Carbon::today())->count();
        $today_members = QrCode::where('status', 'Used')->whereDate('used_date', Carbon::today())->distinct('member_id')->count();
        $today_price = QrCode::where('status', 'Used')->whereDate('used_date', Carbon::today())->sum('qr_serial_no');
        $overall_price = QrCode::where('status', 'Used')->sum('qr_serial_no');


        // $overall_members = Member::count();
        $overall_members = QrCode::where('status', 'Used')->distinct('member_id')->count();



        $qrcode_details['today_used_qrcode'] = isset($today_used_qrcode) ? $today_used_qrcode : 0;
        $qrcode_details['overall_qr_scans'] = isset($overall_qr_scans) ? $overall_qr_scans : 0;
        $qrcode_details['today_members'] = isset($today_members) ? $today_members : 0;
        $qrcode_details['overall_members'] = isset($overall_members) ? $overall_members : 0;
        $qrcode_details['today_price'] = isset($today_price) ? $today_price : 0;
        $qrcode_details['overall_price'] = isset($overall_price) ? $overall_price : 0;
        

        return view('admin.dashboard', compact('qrcode_details'));
    }


    public function list(Request $request)
    {
        $query = QrCode::with(['member:id,member_code,member_name'])->with(['user:id,name'])
                ->select('id', 'used_date', 'member_id', 'qr_serial_no', 'qr_code', 'scan_by', 'message')
                ->where('status', 'Used')
                ->whereDate('used_date', Carbon::today())
                ->orderBy('used_date', 'desc');


            $members = $query->get();
            $counter = 1;
            $members->transform(function ($item) use (&$counter) {
                $item['ser_id'] = $counter++;
                $item['date'] = Carbon::parse($item->used_date)->format('d-m-Y');
                $item['member_name'] = isset($item->member->member_name) ? $item->member->member_name : '';
                $item['scan_by_name'] = isset($item->user->name) ? $item->user->name : '';
                return $item;
            });
            return response()->json(['data' => $members]);
    }
}
