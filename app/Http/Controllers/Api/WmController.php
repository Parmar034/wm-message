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
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;



class WmController extends Controller{

    protected $whatsapp;

    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

	public function sendMessage(Request $request)
    {

        $to = '+919712884659'; // your test number
        $message = 'Hello team, How are you?';

        $response = $this->whatsapp->sendMessage($to, $message);

        return response()->json($response);

    }
    public function sendTextMessage(Request $request)
    {

        $to = '+917984319868'; // your test number
        $message = 'Hello test';

        $response = $this->whatsapp->sendMessage($to, $message);

        return response()->json($response);

    }
    public function send_whatsapp(Request $request) {
        dd($request->all());
    }
}