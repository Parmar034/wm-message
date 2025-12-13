<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\UserMember;
use App\Models\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;
use App\Exports\MessageExport;
use Maatwebsite\Excel\Facades\Excel;


class MessageController extends Controller
{

    public function index()
    {
        return view('frontend.message.index');
    }

    public function list(Request $request)
    {
        $page     = $request->start / $request->length;
        $perPage  = $request->length;

        $searchName  = $request->name;
        $searchPhone = $request->phone;

        // $members = UserMember::query()
        //     ->when($searchName, function ($q) use ($searchName) {
        //         $q->where('member_name', 'like', "%$searchName%");
        //     })
        //     ->when($searchPhone, function ($q) use ($searchPhone) {
        //         $q->orWhere('phone', 'like', "%$searchPhone%");
        //     })
        //     ->pluck('id')
        //     ->toArray();

        $members = UserMember::query()

        ->when($searchName != null && $searchName != "", function ($q) use ($searchName) {
            $q->where('member_name', 'like', "%$searchName%");
        })

        ->when($searchPhone != null && $searchPhone != "", function ($q) use ($searchPhone) {
            $q->where('phone', 'like', "%$searchPhone%");
        })

        ->pluck('id')
        ->toArray();


         if (!empty($searchName) || !empty($searchPhone)) {
            if (empty($members)) {
                return response()->json([
                    "draw"            => intval($request->draw),
                    "recordsTotal"    => 0,
                    "recordsFiltered" => 0,
                    "data"            => []
                ]);
            }
        }


        $messages = SendMessage::with('userMembers')
            ->orderBy('created_at', 'desc')
            ->get();

        $final = collect();
        $seen = [];
    
        foreach ($messages as $msg) {
            $date = Carbon::parse($msg->created_at)->format('d-m-Y H:i:s');
            foreach ($msg->userMembers as $member) {

                // Filter only selected members
                if (!empty($members) && !in_array($member->id, $members)) {
                    continue;
                }

                $uniqueKey = $msg->id . "_" . $member->id;

                if (!isset($seen[$uniqueKey])) {
                    $final->push([
                        "send_message_id" => $msg->id,
                        "user_member_id"  => $member->id,
                        "message_text" => $msg->message_text,
                        "member_name"  => $member->member_name,
                        "phone"        => $member->phone,
                        "created_at" => $date,
                    ]);
                    $seen[$uniqueKey] = true;
                }
            }
        }

        $total = $final->count();

        // Pagination
        $paged = $final->slice($page * $perPage, $perPage)->values();

        // Add counter + action button
        $counter = $page * $perPage + 1;

        $paged->transform(function ($item) use (&$counter) {
            $item['id'] = $counter++;
               // $item['action'] = 
               // '<a href="'.route('message.export', ['sendmessageid' => $item['send_message_id'],'usermemberid' => $item['user_member_id']]).'" class="btn btn-success btn-sm" title="Export Excel"><i class="fa fa-file-excel-o"></i>
               //  </a>';
            return $item;
        });

       
        return response()->json([
            "draw"            => intval($request->draw),
            "recordsTotal"    => $total,
            "recordsFiltered" => $total,
            "data"            => $paged,
        ]);
    }


    public function search_get(Request $request)
    {
        $query = UserMember::query();

        if($request->filled('name')){
            $query->where('member_name','like','%'.$request->name.'%');
        }


        if($request->filled('phone')){
            $query->where('phone','like','%'.$request->phone.'%');
        }

        $members = $query->orderBy('member_name','asc')->get();

    $uniqueNames = [];
    $newMembers = collect();
        foreach($members as $key => $member){
            if(!in_array($member->member_name,$uniqueNames)){
                $uniqueNames[] = $member->member_name;
                $newMembers[$key] = $member;
            }
            // if(!in_array($member->phone,$uniqueNames)){
            //     $uniqueNames[] = $member->phone;
            //     $newMembers[$key] = $member;
            // }

        }

        return response()->json([
            'data' => $newMembers
        ]);


    }


    public function exportMessages(Request $request)
    {

        $searchName  = $request->name;
        $searchPhone = $request->phone;


        $members = UserMember::query()
            ->when($searchName, function ($q) use ($searchName) {
                $q->where('member_name', 'like', "%$searchName%");
            })
            ->when($searchPhone, function ($q) use ($searchPhone) {
                $q->orWhere('phone', 'like', "%$searchPhone%");
            })
            ->pluck('id')
            ->toArray();

    
        if (empty($searchName) && empty($searchPhone)) {
            $members = UserMember::pluck('id')->toArray();
        }

    
        $messages = SendMessage::with(['userMembers' => function ($q) use ($members) {
                $q->whereIn('user_members.id', $members);
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($messages as $msg) {
            $msg->formatted_date = Carbon::parse($msg->created_at)->format('d-m-Y H:i:s');
        }

        
        return Excel::download(new MessageExport($messages), 'message_user.xlsx');
    }

    
    public function addMemberManagement()
    {
        return view('frontend.user-management.add');
    }


    // public function datastore(Request $request){

    //         $messages = SendMessage::take(13)->get();
       
    //         foreach($messages as $msg){
               
           

    //         $onemassage = explode(",", $msg->user_members_id);
    //         foreach($onemassage as $msg1){

    //             $pivot = DB::table('send_message_user_member')->insert([
    //                     'user_member_id' =>$msg1,
    //                     'send_message_id' => $msg->id
    //                 ]);
    //         }
    //     }
        

    // }

}
