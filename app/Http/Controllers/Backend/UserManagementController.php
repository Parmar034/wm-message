<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\UserMember;
use App\Models\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserManagementController extends Controller
{

    public function memebrManagement()
    {
        return view('frontend.user-management.index');
    }

    public function list(Request $request)
    {
        $perPage = $request->input('length', 10);
        $page = $request->input('start', 0) / $perPage;
        $searchValue = $request->input('search.value');
        $query = UserMember::orderBy('created_at', 'desc');

        if (!empty($searchValue)) {
            $query->where(function ($querys) use ($searchValue) {
                $querys->where('member_name', 'LIKE', "%{$searchValue}%")
                    ->orWhere('phone', 'LIKE', "%{$searchValue}%");
            });
        }

        $totalRecords = $query->count();
        // $member_list = $query->get();

        $member_list = $query->skip($page * $perPage)
            ->take($perPage)
            ->get();
        $counter = $page * $perPage + 1;
        $member_list->transform(function ($item) use (&$counter) {
            $item['ser_id'] = $counter++;
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
        return view('frontend.user-management.add');
    }

    public function updateMember(Request $request)
    {
        $json = $request->expectsJson();
        $rules = [
            'member_name' => 'required|string|max:100',
            'phone' => 'required|numeric',
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
                // $response = to_route('member-management.add')
                //     ->withErrors($validator)
                //     ->withInput();
                if(isset($request->member_id) && $request->member_id != ''){
                    $response = to_route('user-management.edit', $request->member_id)
                        ->withErrors($validator)
                        ->withInput();
                }else{
                    $response = to_route('user-management.add')
                        ->withErrors($validator)
                        ->withInput();
                }
            }
            return $response;
        }

        if ($request->member_id) {
            $member = UserMember::find($request->member_id);
            if (!$member) {
                $response = $json
                    ? response()->json(['status' => false, 'message' => 'UserMember not found.'], 404)
                    : to_route('user-management')->with('error', 'UserMember not found.');
                return $response;
            }
                $data = $validator->validated();
                $data['description'] = $request->description ?? '';

                $save = $member->update($data);
        } else {
             $data = $validator->validated();
             $data['description'] = $request->description ?? '';
            $member = new UserMember($data);
            $save = $member->save();
        }

        if (!$save) {
            $response = $json
                ? response()->json(['status' => false, 'message' => 'Something went wrong.'])
                : to_route('user-management')->with('error', 'Something went wrong while updating record');
        } else {
            $response = $json
                ? response()->json(['status' => true, 'message' => 'UserMember saved successfully.'])
                : to_route('user-management')->with('success', 'UserMember saved successfully.');
        }

        return $response;
    }

    public function memeberedit(string $id)
    {
        $member = UserMember::find($id);
        if (!$member) {
            return to_route('user-management')->with('error', 'Member details not found');
        }
        return view('frontend.user-management.add', ['member' => $member]);
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $member = UserMember::find($id);

        if ($member) {
            $member->delete();

            return response()->json([
                'status' => 1,
                'message' => 'Member deleted successfully.',
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Member not found.',
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

        dd($request->all());
        
    }
}
