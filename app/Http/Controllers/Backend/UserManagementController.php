<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\UserMember;
use App\Models\SendMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MemberUserExport;
use Illuminate\Support\Facades\Auth;

class UserManagementController extends Controller
{

    public function memebrManagement()
    {
        $members = User::where('role', 'Admin')->get();
        return view('frontend.user-management.index', compact('members'));
    }

    public function list(Request $request)
    {
        $perPage = $request->input('length', 10);
        $page = $request->input('start', 0) / $perPage;
        $searchValue = $request->input('search.value');
        $query = UserMember::with('user')->orderBy('created_at', 'desc');

        if (Auth::user()->role == 'Admin') {
            $query->where('user_id', Auth::id());
        }


        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('member_name', 'LIKE', "%{$searchValue}%")
                ->orWhere('phone', 'LIKE', "%{$searchValue}%")
                ->orWhereHas('user', function ($uq) use ($searchValue) {
                    $uq->where('name', 'LIKE', "%{$searchValue}%");
                });
            });
        }

        if (!empty($request->input('members_filter'))) {
            $query->where('user_id', $request->input('members_filter'));
        }

        $totalRecords = $query->count();
        // $member_list = $query->get();

        $member_list = $query->skip($page * $perPage)
            ->take($perPage)
            ->get();
        $counter = $page * $perPage + 1;
        $member_list->transform(function ($item) use (&$counter) {
            $item['ser_id'] = $counter++;
            $item['user_name'] = $item->user ? $item->user->name : '';
            $item['user_phone'] = $item->country_code .' '. $item->phone ;

            $checked = $item->status == 1 ? 'checked' : '';
            $item['status'] =
                '<div class="form-check form-switch">
                    <input class="form-check-input status-toggle"
                        type="checkbox"
                        data-id="'.$item->id.'" '.$checked.'>
                </div>';

            $item['member_checkbox'] = '<input type="checkbox" class="member_checkbox" name="selected_items[]" value="' . $item->id . '">';
            // $item['action'] = "-";
            $item['action'] = '<a href="' . route('user-management.edit',$item['id']) . '" class="table-btn table-btn1 service_edit"><span class="pcoded-micon"><img src="'. asset('assets/images/edit_icon.svg') .'" class="img-fluid white_logo" alt=""></span></a>';
            $item['action'] .= '<a data-id="' . $item['id'] . '"  data-original-title="Delete sections" class="table-btn table-btn1 delete-member-btn"><span class="pcoded-micon"><img src="' . asset('assets/images/delete_icon.svg') .'" class="img-fluid white_logo" alt=""></span></a>';
            // $item['action'] .= '<a data-id="' . $item['id'] . '"   class="table-btn table-btn1 send-message-btn"><span class="pcoded-micon"><img src="' . asset('assets/images/edit_icon.svg') .'" class="img-fluid white_logo" alt=""></span></a>';
            return $item;
        });
        // return response()->json(['data' => $member_list]);
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $member_list,
        ]);
    }

    public function addMemberManagement()
    {
        $members = User::where('role','Admin')->get();
        return view('frontend.user-management.add', compact('members'));
    }

    public function updateMember(Request $request)
    {
        $json = $request->expectsJson();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)    
                ->withInput();
        }

        if ($request->user_id) {
            $member = UserMember::find($request->user_id);
            if (!$member) {
                $message = 'User not found.';
                return $json
                    ? response()->json(['status' => false, 'message' => $message], 404)
                    : to_route('user-management')->with('error', $message);
            }
       
        } else {
            $member = new UserMember();
        }

        $data['user_id'] = (Auth::user()->role === 'Super Admin') ? $request->member : Auth::id();
        $data['member_name'] = $request->name;
        $data['member_email'] = isset($request->email) ? $request->email : null;
        $data['phone'] = isset($request->phone) ? $request->phone : null;
        $data['country_code'] = isset($request->country_code) ? $request->country_code : null;
        $data['description'] = isset($request->description) ? $request->description : null;


        $member->fill($data);
        $save = $member->save();

        if (!$save) {
            $message = 'Something went wrong while saving user.';
            return $json
                ? response()->json(['status' => false, 'message' => $message])
                : to_route('user-management')->with('error', $message);
        }

        $message = 'User saved successfully.';
        return $json
            ? response()->json(['status' => true, 'message' => $message])
            : to_route('user-management')->with('success', $message);
    }

    public function updateStatus(Request $request)
    {
        $user = UserMember::find($request->id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ]);
        }

        $user->status = $request->status;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Status updated successfully'
        ]);
    }

    public function memeberedit(string $id)
    {
        $member = UserMember::find($id);
        $members = User::where('role','Admin')->get();

        if (!$member) {
            return to_route('user-management')->with('error', 'Member details not found');
        }
        return view('frontend.user-management.add', ['member' => $member, 'members' => $members]);
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $member = UserMember::find($id);

        if ($member) {
            $member->delete();

            return response()->json([
                'status' => 1,
                'message' => 'User deleted successfully.',
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'User not found.',
            ]);
        }
    }

    public function send_message(Request $request)
    {
        //     $member = new SendMessage();
        //     $member->message_text = $request->message_text;
        //     $member->user_members_id = $request->selectedItemsString;
        //     $member->save();

        // return response()->json([
        //     'status' => 1,
        //     'message' => 'Message sent successfully!'
        // ]);


        $member = new SendMessage();
        $member->message_text = $request->message_text;
        $member->save(); 

        $member_ids = explode(',', $request->selectedItemsString);

        $member->userMembers()->attach($member_ids);

        return response()->json([
            'status' => 1,
            'message' => 'Message sent successfully!'
        ]);
    }

    public function exportExcel(Request $request) {

        $selectedItems = $request->selectedItems === 'all'
            ? 'all'
            : explode(',', $request->selectedItems);
            if(Auth::user()->role == 'Admin'){
                $column_name = [
                    'Name',
                    'Email',
                    'Phone',
                    'Status'
                ];
            }else{
                $column_name = [
                    'Name',
                    'Email',
                    'Phone',
                    'Member',
                    'Status'
                ];
            }

        // Prepare filters array
        $filters = [
            'selected_items' => $selectedItems,
            'members_filter'    => $request->members_filter ?? null,
            'column_name'    => $column_name,
        ];

        return Excel::download(new MemberUserExport($filters), 'members-user.xlsx');
        
    }
}
