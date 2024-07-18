<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Auth, Hash;

class LoginController extends Controller
{
    public function login(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
            ],
            [
                'email.required'=> 'Email is required',
                'password.required'=> 'Password is required'
            ]);
        
            if ($validator->fails()) {
                return redirect()->withErrors($validator);
            }
            $userdata = array(
                'email' => $request->get('email') ,
                'password' => $request->get('password')
            );
            if (Auth::attempt($userdata))
            {
                if(Auth::user()->role == "admin"){
                    return redirect()->route('admin-dashboard');
                }
                elseif(Auth::user()->role == "user"){
                    return redirect()->route('user-dashboard');
                }
            }
            else
            {
                return back()->with('error','Login failed');
            }
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', 'Something Went Wrong');
        }
        
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login-page');
    }
}
