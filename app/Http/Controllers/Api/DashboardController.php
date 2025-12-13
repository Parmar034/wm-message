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


class DashboardController extends Controller{
	public function index(Request $request)
	{
		$today_used_qrcode = QrCode::whereDate('used_date', Carbon::today())->count();
		$overall_qr_scans = QrCode::where('status', 'Used')->count();
		// $today_members = Member::whereDate('created_at', Carbon::today())->count();
		 $today_members = QrCode::where('status', 'Used')->whereDate('used_date', Carbon::today())->distinct('member_id')->count();
		// $overall_members = Member::count();
		 $overall_members = QrCode::where('status', 'Used')->distinct('member_id')->count();

		$today_price = QrCode::where('status', 'Used')->whereDate('used_date', Carbon::today())->sum('qr_serial_no');
        $overall_price = QrCode::where('status', 'Used')->sum('qr_serial_no');




		$qrcode_details['today_used_qrcode'] = isset($today_used_qrcode) ? $today_used_qrcode : 0;
		$qrcode_details['overall_qr_scans'] = isset($overall_qr_scans) ? $overall_qr_scans : 0;
		$qrcode_details['today_members'] = isset($today_members) ? $today_members : 0;
		$qrcode_details['overall_members'] = isset($overall_members) ? $overall_members : 0;
		 $qrcode_details['today_price'] = isset($today_price) ? $today_price : 0;
        $qrcode_details['overall_price'] = isset($overall_price) ? $overall_price : 0;


		return response()->json(['status' => 'Success','data' => $qrcode_details], 200);

	}


}