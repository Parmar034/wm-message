<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Support\Facades\Hash;

class PlansController extends Controller
{
    public function index()
    {
        return view('admin.plans.index');
    }
    public function add()
    {
        return view('admin.plans.add');
    }

    public function store(Request $request)
    {
        $json = $request->expectsJson();
        $validator = Validator::make($request->all(), [
            'plan_name' => 'required',
            'plan_price' => 'required',
            'billing_cycle' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)    
                ->withInput();
        }

        if ($request->plan_id) {
            $plan = Plan::find($request->plan_id);
            if (!$plan) {
                $message = 'Plan not found.';
                return $json
                    ? response()->json(['status' => false, 'message' => $message], 404)
                    : to_route('plans')->with('error', $message);
            }
        } else {
            $plan = new Plan();
        }

        $data['plan_name'] = $request->plan_name;
        $data['plan_type'] = $request->plan_type;
        $data['message_type'] = $request->billing_cycle;
        $data['message_count'] = isset($request->message_limit) ? $request->message_limit : null;
        $data['price'] = $request->plan_price;
        $data['description'] = $request->plan_description;

        $plan->fill($data);
        $save = $plan->save();

        if (!$save) {
            $message = 'Something went wrong while saving user.';
            return $json
                ? response()->json(['status' => false, 'message' => $message])
                : to_route('plans')->with('error', $message);
        }

        $message = 'Plan saved successfully.';
        return $json
            ? response()->json(['status' => true, 'message' => $message])
            : to_route('plans')->with('success', $message);

    }

    public function list(Request $request) {
        $paln_list = Plan::orderBy('created_at', 'desc')->get();
        $counter = 1;
        $paln_list->transform(function ($item) use (&$counter) {
            $item['ser_id'] = $counter++;
            $checked = $item->status == 1 ? 'checked' : '';
            $item['status'] = '<div class="form-check form-switch"><input class="form-check-input status-toggle" type="checkbox" data-id="' . $item->id . '" ' . $checked . '></div>';
            // $item['action'] = '<a href="' . route('plan.edit',$item['id']) . '" class="table-btn table-btn1 service_edit"><span class="pcoded-micon"><img src="'. asset('assets/images/edit_icon.svg') .'" class="img-fluid white_logo" alt=""></span></a>';
            $item['action'] = '<a href="javascript:void(0);" class="table-btn table-btn1 service_edit planEditConfirm" data-url="' . route('plan.edit', $item['id']) . '"><span class="pcoded-micon"><img src="'. asset('assets/images/edit_icon.svg') .'" class="img-fluid white_logo" alt=""></span></a>';
            $item['action'] .= '<a data-id="' . $item['id'] . '"  data-original-title="Delete sections" class="table-btn table-btn1 delete-plan-btn"><span class="pcoded-micon"><img src="' . asset('assets/images/delete_icon.svg') .'" class="img-fluid white_logo" alt=""></span></a>';
            return $item;
        });
        return response()->json(['data' => $paln_list]);
    }

    public function updateStatus(Request $request)
    {
        $plan = Plan::find($request->id);

        if (!$plan) {
            return response()->json([
                'status' => false,
                'message' => 'Plan not found'
            ]);
        }

        $plan->status = $request->status;
        $plan->save();

        return response()->json([
            'status' => true,
            'message' => 'Plan status updated successfully.'
        ]);
    }

    public function planedit($id)
    {
        $plan = Plan::find($id);
        if (!$plan) {
            return redirect()->route('plans')->with('error', 'Plan not found.');
        }
        return view('admin.plans.add', compact('plan'));
    }
    public function destroy(Request $request)
    {
        $plan = Plan::find($request->id);

        if (!$plan) {
            return response()->json([
                'status' => false,
                'message' => 'Plan not found'
            ]);
        }

        $plan->delete();

        return response()->json([
            'status' => true,
            'message' => 'Plan deleted successfully.'
        ]);
    }

}