<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\UserMember;
use App\Models\SendMessage;
use App\Models\MessageUser;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;
use App\Exports\MessageHistoryExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;



class MessageController extends Controller
{

    public function index()
    {
        $members = User::where('role', 'Admin')->get();
        $users = UserMember::all();

        return view('frontend.message.index', compact('members', 'users'));
    }

    public function list(Request $request)
    {
        $perPage = (int) $request->input('length', 10);
        $start   = (int) $request->input('start', 0);
        $search  = $request->input('search.value');
        $member_id  = $request->input('member_id');
        $user_id  = $request->input('user_id');
        


        // 1️⃣ Load data (OK for small datasets)
        $messages = Message::with(['userMembers.user'])
            ->orderBy('created_at', 'desc')
            ->get();  

        // 2️⃣ Build flat array
        $data = [];
        // $counter = 1;

        foreach ($messages as $message) {
            foreach ($message->userMembers as $member) {
                $data[] = [
                    // 'id'           => $counter++,
                    'member_checkbox' => '<input type="checkbox" class="member_checkbox" name="selected_items[]" value="' . $member->id . '">',
                    'member_id'      => optional($member->user)->id, // users.id
                    'user_member_id' => $member->id,      
                    'member_name'  => optional($member->user)->name,
                    'user_name'    => $member->member_name,
                    'phone'        => $member->phone,
                    'message_text' => $message->message,
                    'created_at'   => Carbon::parse($member->pivot->created_at)
                                        ->format('d-m-Y'),
                ];
            }
        }

        // 3️⃣ Convert to collection
        $collection = collect($data);

        // 🔍 4️⃣ SEARCH FILTER (IMPORTANT)
        if (!empty($search)) {
            $collection = $collection->filter(function ($row) use ($search) {
                return str_contains(strtolower($row['member_name'] ?? ''), strtolower($search))
                    || str_contains(strtolower($row['user_name'] ?? ''), strtolower($search))
                    || str_contains(strtolower($row['phone'] ?? ''), strtolower($search));
            });
        }

        if(Auth::user()->role == 'Admin'){
            $collection = $collection->where('member_id', Auth::user()->id);
        }

        if (!empty($member_id)) {
            $collection = $collection->where('member_id', (int) $member_id);
        }

        if (!empty($user_id)) {
            $collection = $collection->where('user_member_id', (int) $user_id);
        }

        // 5️⃣ Records count AFTER filter
        $recordsFiltered = $collection->count();
        $recordsTotal = DB::table('message_user')->count();

        // 6️⃣ Pagination
        $datas = $collection
            ->slice($start, $perPage)
            ->values();
        $counter = $start + 1;    

        $datas = $datas->map(function ($row) use (&$counter) {
            $row['id'] = $counter++;
            return $row;
        });    

        // 7️⃣ Response
        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $datas,
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
    public function addMemberManagement()
    {
        return view('frontend.user-management.add');
    }

    function get_user_list(Request $request){
        $user_id = $request->member_id;

        if(!$user_id){
            $users = UserMember::get();
        }else{
            $users = UserMember::where('user_id', $user_id)->get();
        }   

        return response()->json([
            'users' => $users
        ]);
        
    }

    public function exportMessages(Request $request)
    {
        // Determine selected items
        $selectedItems = $request->selectedItems === 'all'
            ? 'all'
            : explode(',', $request->selectedItems);

            if(Auth::user()->role == 'Admin'){
                $column_name = [
                    'User Name',
                    'Phone',
                    'Message',
                    'Created At'
                ];
            }else{
                $column_name = [
                    'Member Name',
                    'User Name',
                    'Phone',
                    'Message',
                    'Created At'
                ];
            }

        // Prepare filters array
        $filters = [
            'selected_items' => $selectedItems,
            'members_filter'    => $request->members_filter ?? null,
            'users_filter'      => $request->users_filter ?? null,
            'column_name'    => $column_name,
        ];

        return Excel::download(new MessageHistoryExport($filters), 'message-historys.xlsx');
    }




}
