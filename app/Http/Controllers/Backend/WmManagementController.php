<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\UserMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WmManagementController extends Controller
{

    public function addMemberManagement()
    {
        $userMember = UserMember::get();
        return view('frontend.wm-management.add', compact('userMember'));
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
}
