<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function memebrManagement()
    {
        return view('frontend.member-management.index');
    }

    public function list(Request $request)
    {
        $member_list = User::where('role', 'Admin')->orderBy('created_at', 'desc')->get();
        $counter = 1;
        $member_list->transform(function ($item) use (&$counter) {
            $item['ser_id'] = $counter++;
            $checked = $item->status == 1 ? 'checked' : '';
            $item['status'] = '<div class="form-check form-switch"><input class="form-check-input status-toggle" type="checkbox" data-id="' . $item->id . '" ' . $checked . '></div>';
            $item['action'] = '<a href="' . route('member-management.edit',$item['id']) . '" class="table-btn table-btn1 service_edit"><span class="pcoded-micon"><img src="'. asset('assets/images/edit_icon.svg') .'" class="img-fluid white_logo" alt=""></span></a>';
            $item['action'] .= '<a data-id="' . $item['id'] . '"  data-original-title="Delete sections" class="table-btn table-btn1 delete-member-btn"><span class="pcoded-micon"><img src="' . asset('assets/images/delete_icon.svg') .'" class="img-fluid white_logo" alt=""></span></a>';
            return $item;
        });
        return response()->json(['data' => $member_list]);
    }

    public function addMemberManagement()
    {
        return view('frontend.member-management.add');
    }

    public function updateMember(Request $request)
    {
        $json = $request->expectsJson();


        // Validation rules
        $rules = [
            'email' => 'required|email|max:255|unique:users,email,' . ($request->user_id ?? 'NULL') . ',id',
            'name' => 'required|string|max:100',
            'phone' => 'required|digits:10',
            // 'password' => $request->user_id ? 'nullable|min:8|confirmed' : 'required|min:8|confirmed', 
            // Use password_confirmation field
        ];

        if ((isset($request->change_password) && $request->change_password == 'on') || !$request->user_id) {
            $rules['password'] = 'required|min:8|confirmed';
        }

        $validator = Validator::make($request->all(), $rules);

        // Check validation
        if ($validator->fails()) {
            if ($json) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422);
            } else {
                // Redirect back with errors and input
                $redirect = $request->user_id
                    ? to_route('member-management.edit', $request->user_id)
                    : to_route('member-management.add');

                return $redirect->withErrors($validator)->withInput();
            }
        }

        // Store/update user
        if ($request->user_id) {
            $user = User::find($request->user_id);
            if (!$user) {
                $message = 'User not found.';
                return $json
                    ? response()->json(['status' => false, 'message' => $message], 404)
                    : to_route('member-management')->with('error', $message);
            }
        } else {
            $user = new User();
        }

        // Fill validated data
        $data = $validator->validated();

        // Hash password only if provided
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
            $data['role'] = 'Admin';

        } else {
            unset($data['password']); 
        }

        $user->fill($data);
        $save = $user->save();

        if (!$save) {
            $message = 'Something went wrong while saving user.';
            return $json
                ? response()->json(['status' => false, 'message' => $message])
                : to_route('member-management')->with('error', $message);
        }

        $message = 'User saved successfully.';
        return $json
            ? response()->json(['status' => true, 'message' => $message])
            : to_route('member-management')->with('success', $message);
    }


    public function memeberedit(string $id)
    {
        $member = User::find($id);
        if (!$member) {
            return to_route('member-management')->with('error', 'Member details not found');
        }
        return view('frontend.member-management.add', ['member' => $member]);
    }

    public function updateStatus(Request $request)
    {
        $user = User::find($request->id);

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
  
    public function destroy(Request $request)
    {
        $id = $request->id;
        $member = Member::find($id);

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
}
