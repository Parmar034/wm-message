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
use Illuminate\Support\Facades\Validator;



class MembersController extends Controller{

	public function list(Request $request)
    {
        $query = Member::select('id', 'member_code', 'member_name', 'phone', 'location')->orderBy('created_at', 'desc');

        if ($request->get('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('member_code', 'like', "%$search%")
                    ->orWhere('member_name', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhere('location', 'like', "%$search%");
            });
        }

        $members = $query->get();

        $members->transform(function ($item) {
                $item->member_location = isset($item->location) ? $item->location : '';
                return $item;
        });
        $members = $members->map(function ($item) {
                unset($item->location); // removes created_at
                return $item;
        });


        if ($members->count()) {
            return response()->json([
                'status' => 'Success',
                'message' => 'Members list found successfully.',
                'data' => $members
            ]);
        }

        return response()->json([
            'status' => 'Failed',
            'message' => 'No members found.',
        ]);
    }


    public function add(Request $request)
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
         
                 return response()->json(['status' => 'Failed', 'message' => $validator->errors()->first()], 200);


        }

        if (isset($request->member_id) && $request->member_id != ''){
            $member = Member::find($request->member_id);
            if (!$member) {
             
                 return response()->json(['status' => 'Failed', 'message' => 'Member not found.'], 200);

            }
            $save = $member->update($validator->validated());
        } else {
            $member = new Member($validator->validated());
            $save = $member->save();
        }

        if (!$save) {
            return response()->json(['status' => 'Failed', 'message' => 'Something went wrong.'], 200);
        } else {
        	if(isset($request->member_id) && $request->member_id != ''){
        		return response()->json(['status' => 'Success','message' => 'Member updated successfully.'], 200);
        	}else{
        		return response()->json(['status' => 'Success','message' => 'Member saved successfully.'], 200);
        	}
            
        }

    }

    public function destroy(Request $request)
    {
        $id = $request->member_id;
        $member = Member::find($id);

        if ($member) {
            $member->delete();

            return response()->json([
                'status' => 'Success',
                'message' => 'Member deleted successfully.',
            ]);
        } else {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Member not found.',
            ]);
        }
    }
}