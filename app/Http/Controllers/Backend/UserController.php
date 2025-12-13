<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function memebrManagement()
    {
        return view('frontend.member-management.index');
    }

    // public function list(Request $request)
    // {
    //     $query = Member::query();

    //     if ($request->get('search')) {
    //         $search = $request->get('search');
    //         $query->where(function ($q) use ($search) {
    //             $q->where('member_code', 'like', "%$search%")
    //                 ->orWhere('member_name', 'like', "%$search%")
    //                 ->orWhere('phone', 'like', "%$search%")
    //                 ->orWhere('location', 'like', "%$search%");
    //         });
    //     }

    //     $members = $query->get();

    //     if ($members->count()) {
    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Members list found successfully.',
    //             'data' => $members
    //         ]);
    //     }

    //     return response()->json([
    //         'status' => false,
    //         'message' => 'No members found.',
    //     ]);
    // }

    public function list(Request $request)
    {
        $member_list = Member::orderBy('created_at', 'desc')->get();
        $counter = 1;
        $member_list->transform(function ($item) use (&$counter) {
            $item['ser_id'] = $counter++;
            // $item['action'] = "-";
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
        $rules = [
            'member_code' => 'required|max:255|unique:members,member_code' . ($request->member_id ? ',' . $request->member_id : ''),
            'member_name' => 'required|string|max:100',
            'phone' => 'required|numeric',
            'location' => '',
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
                    $response = to_route('member-management.edit', $request->member_id)
                        ->withErrors($validator)
                        ->withInput();
                }else{
                    $response = to_route('member-management.add')
                        ->withErrors($validator)
                        ->withInput();
                }
            }
            return $response;
        }

        if ($request->member_id) {
            $member = Member::find($request->member_id);
            if (!$member) {
                $response = $json
                    ? response()->json(['status' => false, 'message' => 'Member not found.'], 404)
                    : to_route('member-management')->with('error', 'Member not found.');
                return $response;
            }
            $save = $member->update($validator->validated());
        } else {
            $member = new Member($validator->validated());
            $save = $member->save();
        }

        if (!$save) {
            $response = $json
                ? response()->json(['status' => false, 'message' => 'Something went wrong.'])
                : to_route('member-management')->with('error', 'Something went wrong while updating record');
        } else {
            $response = $json
                ? response()->json(['status' => true, 'message' => 'Member saved successfully.'])
                : to_route('member-management')->with('success', 'Member saved successfully.');
        }

        return $response;
    }

    public function memeberedit(string $id)
    {
        $member = Member::find($id);
        if (!$member) {
            return to_route('member-management')->with('error', 'Member details not found');
        }
        return view('frontend.member-management.add', ['member' => $member]);
    }
    // public function destroy(Request $request, string $id)
    // {
    //     $json = $request->expectsJson();
    //     $member = Member::find($id);

    //     if (!$member) {
    //         $response = $json
    //             ? response()->json(['status' => false, 'message' => 'Member not found.'], 404)
    //             : to_route('member-management')->with('error', 'Member not found.');
    //         return $response;
    //     }

    //     $deleted = $member->delete();

    //     if (!$deleted) {
    //         $response = $json
    //             ? response()->json(['status' => false, 'message' => 'Something went wrong.'])
    //             : to_route('member-management')->with('error', 'Something went wrong while deleting record');
    //     } else {
    //         $response = $json
    //             ? response()->json(['status' => true, 'message' => 'Member deleted successfully.'])
    //             : to_route('member-management')->with('success', 'Member deleted successfully.');
    //     }

    //     return $response;
    // }
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
