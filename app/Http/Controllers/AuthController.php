<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
// use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// use Validator;
use Cookie;
class AuthController extends Controller
{
    // use ApiResponser;
    
    public function register(Request $request){
        
        $validator=Validator::make($request->all(),[
            'name'=>'bail|required|string|max:255',
            'email'=>'bail|required|string|email|max:255|unique:users,email',
            'password'=>'required|string|min:6|max:100|same:confirm_password',
            'confirm_password'=>'required|string|min:6|max:100'
        ],[
            'required'=>':attribute belum diinputkan',
            'max'=>':attribute tidak boleh lebih dari :max karakter',
            'email'=>'email tidak valid',
            'unique'=>':attribute :input sudah digunakan',
            'min'=>':attribute minimal :min karakter',
            'same'=>':attribute tidak sama'
        ]);
        if($validator->fails()){
            return response([
                "message"=>$validator->errors()->first()
            ],400);
        }
        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);
        
        return response([
            "message"=>"Register success"
        ],200);
    }
    
    public function login(Request $request){
        $validator=Validator::make($request->all(),[
            'email'=>'bail|required|string|email|max:255',
            'password'=>'required|string|min:6|max:100'
        ],[
            'required'=>':attribute harus diinputkan',
            'max'=>':attribute tidak boleh lebih dari :max karakter',
            'email'=>'email tidak valid',
            'min'=>':attribute minimal :min karakter'
        ]);
        if($validator->fails()){
            return response([
                "message"=>$validator->errors()->first()
            ],400);
        }
        if(!Auth::attempt($request->only('email','password'))){
            return response([
                "message"=>'Email atau password salah'
            ],401);;
        }
        $user=Auth::user();
        $token=$user->createToken('auth_token')->plainTextToken;
        $cookie=cookie('token-jwt',$token,60*24*7);
        return response([
            "message"=>"Login success",
            "token"=>$token
        ],200)->withCookie($cookie);
    }
    
    public function logout(){
        auth()->user()->tokens()->delete();
        $cookie=Cookie::forget('token-jwt');
        return response([
            'message'=>'Logout success'
        ],200)->withCookie($cookie);
    }
}
