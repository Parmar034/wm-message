<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;


class LoginController extends Controller
{
    public function login(Request $request){

        $input = $request->all();
        $this->validate($request, [
            'user_name' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('user_name', 'password');
        
        $user_data = User::select('name', 'user_name', 'email', 'phone', 'id')->where('user_name',$input['user_name'])->first();



        if(isset( $user_data->user_name ) && $user_data->user_name == $input['user_name']) {

            if (Auth::attempt($credentials) ) {
                    $token = auth()->user()->createApiToken();
                    $user = Auth::user();
                    $user->device_type = $request->device_type;
                    $user->device_name = $request->device_name;
                    $user->os_version = $request->os_version;
                    $user->app_version = $request->app_version;
                    $user->save();

                    $user_details = [];
                    $user_details['user_id'] = isset($user_data->id) ? $user_data->id : '';
                    $user_details['name'] = isset($user_data->name) ? $user_data->name : '';
                    $user_details['user_name'] = isset($user_data->user_name) ? $user_data->user_name : '';
                    $user_details['email'] = isset($user_data->email) ? $user_data->email : '';
                    $user_details['phone'] = isset($user_data->phone) ? $user_data->phone : '';
                    $user_details['token'] = $token;



                return response()->json(['status' => 'Success','message' => 'You’ve logged in successfully', 'data' => $user_details], 200);
            }else{
                $result['status'] = 'Failed';
                $result['message'] = 'The user name or password is incorrect';
                return response()->json($result);
            }
        }else{
                $result['status'] = 'Failed';
                $result['message'] = 'The user name or password is incorrect';
                return response()->json($result);
        }
    }

}