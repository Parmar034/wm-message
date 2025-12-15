<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function memebrManagement()
    {
        $plans = Plan::all();
        return view('frontend.member-management.index', ['plans' => $plans]);
    }

    public function list(Request $request)
    {
        $member_list = User::where('role', 'Admin')
            ->with(['latestSubscription.plan'])
            ->get()
            ->sortByDesc(fn ($user) => optional($user->latestSubscription)->id)
            ->values();

        $counter = 1;

        $member_list->transform(function ($item) use (&$counter) {

            $item['ser_id'] = $counter++;

          $item['assign_plan'] = isset($item->latestSubscription->plan->plan_name) ? $item->latestSubscription->plan->plan_name : '-';
          $item['start_date'] = isset($item->latestSubscription->start_date)
                ? Carbon::parse($item->latestSubscription->start_date)->format('d-m-Y')
                : '-';

            $item['end_date'] = isset($item->latestSubscription->end_date)
                ? Carbon::parse($item->latestSubscription->end_date)->format('d-m-Y')
                : '-';

            $item['expire_date'] = isset($item->latestSubscription->end_date) ? $item->latestSubscription->end_date : '-';    

            $item['plan_assign'] =
                '<a href="javascript:void(0);"
                    class="table-btn table-btn1 service_edit openPlanModal"
                    data-user-id="'.$item->id.'"
                    data-user-name="'.$item->name.'">
                    <span class="pcoded-micon">
                        <img src="'.asset('assets/images/edit_icon.svg').'" class="img-fluid white_logo">
                    </span>
                </a>';

            $checked = $item->status == 1 ? 'checked' : '';
            $item['status'] =
                '<div class="form-check form-switch">
                    <input class="form-check-input status-toggle"
                        type="checkbox"
                        data-id="'.$item->id.'" '.$checked.'>
                </div>';

            $item['action'] =
                '<a href="'.route('member-management.edit',$item->id).'"
                class="table-btn table-btn1 service_edit">
                    <img src="'.asset('assets/images/edit_icon.svg').'" class="img-fluid white_logo">
                </a>';

            $item['action'] .=
                '<a data-id="'.$item->id.'"
                class="table-btn table-btn1 delete-member-btn">
                    <img src="'.asset('assets/images/delete_icon.svg').'" class="img-fluid white_logo">
                </a>';

            return $item;
        });

        return response()->json(['data' => $member_list]);
    }


    // public function list(Request $request)
    // {
    //     // $member_list = User::where('role', 'Admin')->orderBy('created_at', 'desc')->get();
    //     $member_list = User::where('role', 'Admin')
    //         ->with(['latestSubscription.plan'])
    //         ->get()
    //         ->sortByDesc(fn ($user) => optional($user->latestSubscription)->id)->get();

    //     $counter = 1;
    //     $member_list->transform(function ($item) use (&$counter) {
    //         $item['ser_id'] = $counter++;
    //         $item['assign_plan'] = isset($item->latestSubscription->plan->plan_name) ? $item->latestSubscription->plan->plan_name : '-';
    //         $item['start_date'] = isset($item->latestSubscription->start_date) ? $item->latestSubscription->start_date : '-';
    //         $item['end_date'] = isset($item->latestSubscription->end_date) ? $item->latestSubscription->end_date : '-';


    //         // $item['plan_assign'] = '<a href="' . route('member-plan.assign',$item['id']) . '" class="table-btn table-btn1 service_edit"><span class="pcoded-micon"><img src="'. asset('assets/images/edit_icon.svg') .'" class="img-fluid white_logo" alt=""></span></a>';
    //         $item['plan_assign'] = '<a href="javascript:void(0);" class="table-btn table-btn1 service_edit openPlanModal" data-user-id="'.$item['id'].'" data-user-name="'.$item['name'].'"><span class="pcoded-micon"><img src="'. asset('assets/images/edit_icon.svg') .'" class="img-fluid white_logo" alt=""></span></a>';
    //         $checked = $item->status == 1 ? 'checked' : '';
    //         $item['status'] = '<div class="form-check form-switch"><input class="form-check-input status-toggle" type="checkbox" data-id="' . $item->id . '" ' . $checked . '></div>';
    //         $item['action'] = '<a href="' . route('member-management.edit',$item['id']) . '" class="table-btn table-btn1 service_edit"><span class="pcoded-micon"><img src="'. asset('assets/images/edit_icon.svg') .'" class="img-fluid white_logo" alt=""></span></a>';
    //         $item['action'] .= '<a data-id="' . $item['id'] . '"  data-original-title="Delete sections" class="table-btn table-btn1 delete-member-btn"><span class="pcoded-micon"><img src="' . asset('assets/images/delete_icon.svg') .'" class="img-fluid white_logo" alt=""></span></a>';
    //         return $item;
    //     });
    //     return response()->json(['data' => $member_list]);
    // }

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
        $member = User::find($id);

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

    public function plan_assign($id)
    {
        $member = User::find($id);
        $plans = Plan::all();
        if (!$member) {
            return to_route('member-management')->with('error', 'Member details not found');
        }
        return view('frontend.member-management.plan-assign', ['member' => $member, 'plans' => $plans]);
    }

    public function assign_store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
        ]);


        $user = User::find($request->user_id);
        $plan = Plan::find($request->plan_id);
        if (!$user || !$plan) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid user or plan selection.'
            ]);
        }
        $start_date = Carbon::now();
        $days = 1;
        $end_date   = null;

       
        if ($plan->plan_type === 'Monthly') {
            $end_date = $start_date->copy()
                ->addMonth()
                ->subDay();
        } elseif ($plan->plan_type === 'Annual') {
            $end_date = $start_date->copy()
                ->addYear()
                ->subDay();
        }

        $active = Subscription::where('user_id', $request->user_id)
            ->where('status', 'active')
            ->first();

      
        if ($active) {
            $active->update(['status' => 'expired']); // status change allowed
        }

        Subscription::create(
            [
                'user_id' => $request->user_id,
                'plan_id'    => $request->plan_id,
                'start_date' => $start_date,
                'end_date'   => $end_date->copy()->addDays($days),
                'status' => 'active'
            ]
       
        );

        return response()->json([
            'status' => true,
            'message' => 'Plan assigned successfully'
        ]);
    }

}
