<?php

namespace App\Http\Controllers;

use App\Models\CommonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class AuthController extends Controller
{
    public function login(){
        return view('auth.login');
    }

    public function login_attempt(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $userInfo = User::where('email',$request->email)->first();
        if($userInfo){
            if(Hash::check($request->password, $userInfo->password)){
                Auth::guard('auth')->login($userInfo);
                session()->put('auth', $userInfo);
                return response()->json(requestSuccess('Login Success', '', '/',500),200);
            }else{
                return response()->json([
                    'message' => 'Password Not Matched',
                ],421);
            }
        }else{
            return response()->json([
                'message' => 'Email Not Found',
            ],421);
        }
    }

    public function register(){
        return view('auth.register');
    }

    public function register_attempt(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$request->row_id,
            'password' => 'required|min:3',
            'company_name' => 'required'
        ]);

        $data_company = [
            'name' => $request->company_name
        ];
        CommonModel::insertRow('company',$data_company);
        $company_info = CommonModel::findRow('company','name',$request->company_name,1);

        $data_user = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_id' => $company_info[0]->id
        ];
        CommonModel::insertRow('users',$data_user);
        return response()->json(requestSuccess('Register Success', '', '/',500),200);
    }

    function logout(){
        Auth::guard('auth')->logout();
        return redirect()->route('login');
    }
}
